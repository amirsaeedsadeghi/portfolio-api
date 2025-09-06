<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\ProjectFilter;
use App\Http\Requests\V1\Project\StoreProjectRequest;
use App\Http\Requests\V1\Project\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ImageService;
use App\Services\ProjectService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Projects
 *
 * Manage portfolio projects (list, create, view, update, delete).
 */
class ProjectController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ProjectService $projectService,
        protected ImageService $imageService
    ) {}

    /**
     * Get all projects
     *
     * Returns the ordered list of projects.
     *
     * @unauthenticated 
     * 
     * @response 200 {
     *  "status": true,
     *  "message": "OK",
     *  "data": [
     *    {
     *      "type": "projects",
     *      "id": 1,
     *      "title": "Personal Portfolio Backend",
     *      "summary": "Custom PHP framework with MVC, validation, and test coverage built from scratch.",
     *      "description": "<p>...</p>",
     *      "primaryImage": "/storage/images/projects/portfolio/portfolio-1.webp",
     *      "client": "Personal Project",
     *      "demoLink": "https://portfolio-demo-link.com",
     *      "github": null,
     *      "category": "Framework",
     *      "order": 1,
     *      "slug": "personal-portfolio-backend",
     *      "role": "Full-Stack Web Developer",
     *      "startDate": "2025-07-02",
     *      "stacks": [
     *        {
     *          "type": "stacks",
     *          "id": 1,
     *          "name": "php",
     *          "image": "/storage/images/stacks/php.png",
     *          "included": ["new ProjectResource($this->project)"],
     *          "links": { "self": "/api/v1/stacks/1" }
     *        }
     *      ],
     *      "images": [
     *        {
     *          "type": "projectImages",
     *          "id": 1,
     *          "image": "/storage/images/projects/portfolio/portfolio-2.webp",
     *          "alt": "portfolio image 2",
     *          "order": 1,
     *          "link": { "self": "/api/v1/project-images/1" }
     *        }
     *      ],
     *      "links": { "self": "/api/v1/projects/personal-portfolio-backend" }
     *    }
     *  ],
     *  "meta": null,
     *  "links": null
     * }
     */
    public function index(ProjectFilter $filters): JsonResponse
    {
        $projects = $this->projectService->all($filters);
        return $this->success(ProjectResource::collection($projects));
    }

    /**
     * Create project
     *
     * Stores a new project.
     *
     * @authenticated
     *
     * @bodyParam title string required Project title. Example: Personal Portfolio Backend
     * @bodyParam summary string required Short summary. Example: Custom PHP framework with MVC...
     * @bodyParam description string required HTML description. Example: <p>...</p>
     * @bodyParam client string required Client/owner. Example: Personal Project
     * @bodyParam demoLink string optional Public demo URL. Example: https://portfolio-demo-link.com
     * @bodyParam github string optional GitHub URL. Example: https://github.com/user/repo
     * @bodyParam category string required Category. Example: Framework
     * @bodyParam order integer required Display order. Example: 1
     * @bodyParam slug string required URL slug. Example: personal-portfolio-backend
     * @bodyParam role string required Role. Example: Full-Stack Web Developer
     * @bodyParam startDate date required Start date (YYYY-MM-DD). Example: 2025-07-02
     * @bodyParam primaryImage file required Primary image file.
     *
     * @response 201 {
     *  "status": true,
     *  "message": "Project created successfully.",
     *  "data": {
     *    "type": "projects",
     *    "id": 8,
     *    "title": "Personal Portfolio Backend",
     *    "summary": "...",
     *    "description": "<p>...</p>",
     *    "primaryImage": "/storage/images/projects/portfolio/portfolio-1.webp",
     *    "client": "Personal Project",
     *    "demoLink": "https://portfolio-demo-link.com",
     *    "github": null,
     *    "category": "Framework",
     *    "order": 1,
     *    "slug": "personal-portfolio-backend",
     *    "role": "Full-Stack Web Developer",
     *    "startDate": "2025-07-02",
     *    "stacks": [],
     *    "images": [],
     *    "links": { "self": "/api/v1/projects/personal-portfolio-backend" }
     *  }
     * }
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $this->authorize('create', Project::class);

        $data = $request->validated();
        $data['primary_image'] = $this->imageService->handleImageUpload(
            $request->file('primaryImage'),
            config('paths.project_images')
        );

        $project = $this->projectService->create($data);

        return $this->success(new ProjectResource($project), 'Project created successfully.', 201);
    }

    /**
     * Get one project (by slug)
     *
     * Returns a single project by its slug.
     *
     * @urlParam slug string required Project slug. Example: personal-portfolio-backend
     *
     * @response 200 {
     *  "status": true,
     *  "message": "OK",
     *  "data": {
     *    "type": "projects",
     *    "id": 1,
     *    "title": "Personal Portfolio Backend",
     *    "summary": "...",
     *    "description": "<p>...</p>",
     *    "primaryImage": "/storage/images/projects/portfolio/portfolio-1.webp",
     *    "client": "Personal Project",
     *    "demoLink": "https://portfolio-demo-link.com",
     *    "github": null,
     *    "category": "Framework",
     *    "order": 1,
     *    "slug": "personal-portfolio-backend",
     *    "role": "Full-Stack Web Developer",
     *    "startDate": "2025-07-02",
     *    "stacks": [],
     *    "images": [],
     *    "links": { "self": "/api/v1/projects/personal-portfolio-backend" }
     *  }
     * }
     */
    public function show(ProjectFilter $filters, string $slug): JsonResponse
    {
        $project = $this->projectService->findBySlug($filters, $slug);
        return $this->success(new ProjectResource($project));
    }

    /**
     * Update project
     *
     * Updates the specified project by ID.
     *
     * @authenticated
     *
     * @urlParam id integer required Project ID. Example: 1
     * @bodyParam title string optional Project title.
     * @bodyParam summary string optional Short summary.
     * @bodyParam description string optional HTML description.
     * @bodyParam client string optional Client/owner.
     * @bodyParam demoLink string optional Public demo URL.
     * @bodyParam github string optional GitHub URL.
     * @bodyParam category string optional Category.
     * @bodyParam order integer optional Display order.
     * @bodyParam slug string optional URL slug.
     * @bodyParam role string optional Role.
     * @bodyParam startDate date optional Start date (YYYY-MM-DD).
     * @bodyParam imagePrimary file optional New primary image file.
     *
     * @response 200 {
     *  "status": true,
     *  "message": "Project updated successfully.",
     *  "data": {
     *    "type": "projects",
     *    "id": 1,
     *    "title": "Personal Portfolio Backend",
     *    "summary": "...",
     *    "description": "<p>...</p>",
     *    "primaryImage": "/storage/images/projects/portfolio/portfolio-1.webp",
     *    "client": "Personal Project",
     *    "demoLink": "https://portfolio-demo-link.com",
     *    "github": null,
     *    "category": "Framework",
     *    "order": 1,
     *    "slug": "personal-portfolio-backend",
     *    "role": "Full-Stack Web Developer",
     *    "startDate": "2025-07-02",
     *    "stacks": [],
     *    "images": [],
     *    "links": { "self": "/api/v1/projects/personal-portfolio-backend" }
     *  }
     * }
     */
    public function update(UpdateProjectRequest $request, string $id): JsonResponse
    {
        $project = $this->projectService->findById($id);

        $data = $request->validated();
        $data['image_primary'] = $this->imageService->handleImageUpload(
            $request->file('imagePrimary'),
            config('paths.project_images', '/images/projects'),
            $project->image_primary
        );

        $project = $this->projectService->updateModel($project, $data);

        return $this->success(new ProjectResource($project), 'Project updated successfully.');
    }

    /**
     * Delete project
     *
     * Removes a project by ID.
     *
     * @authenticated
     *
     * @urlParam id integer required Project ID. Example: 1
     *
     * @response 204 {}
     */
    public function destroy(string $id): Response
    {
        $this->projectService->deleteById($id);
        return $this->noContent();
    }
}
