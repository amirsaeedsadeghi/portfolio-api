<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\ProjectImageFilter;
use App\Http\Requests\V1\ProjectImage\StoreProjectImageRequest;
use App\Http\Requests\V1\ProjectImage\UpdateProjectImageRequest;
use App\Http\Resources\ProjectImageResource;
use App\Services\ImageService;
use App\Services\ProjectImageService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Project Images
 *
 * Manage images that belong to a project.
 */
class ProjectImageController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ProjectImageService $projectImageService,
        protected ImageService $imageService
    ) {}

    /**
     * Get project (for images page)
     *
     * Returns the project info by slug (used by images page).
     *
     * @unauthenticated 
     * 
     * @urlParam slug string required Project slug. Example: personal-portfolio-backend
     *
     * @response 200 {
     *   "status": true,
     *   "message": "OK",
     *   "data": {
     *     "type": "projects",
     *     "id": 1,
     *     "title": "Personal Portfolio Backend",
     *     "summary": "Custom PHP framework with MVC, validation, and test coverage built from scratch.",
     *     "description": "<p>...</p>",
     *     "primaryImage": "/storage/images/projects/portfolio/portfolio-1.webp",
     *     "client": "Personal Project",
     *     "demoLink": "https://portfolio-demo-link.com",
     *     "github": null,
     *     "category": "Framework",
     *     "order": 1,
     *     "slug": "personal-portfolio-backend",
     *     "role": "Full-Stack Web Developer",
     *     "startDate": "2025-07-02",
     *     "links": {
     *       "self": "/api/v1/projects/personal-portfolio-backend"
     *     }
     *   },
     *   "meta": null,
     *   "links": null
     * }
     */
    public function index(ProjectImageFilter $filters, string $slug): JsonResponse
    {
        $projectImages = $this->projectImageService->all($filters, $slug);
        return $this->success(ProjectImageResource::collection($projectImages));
    }

    /**
     * Add project images
     *
     * Upload and attach one or more images to the project.
     *
     * @urlParam slug string required Project slug. Example: personal-portfolio-backend
     *
     * @bodyParam images array required Array of images.
     * @bodyParam images[].file file required Image file.
     * @bodyParam images[].alt string optional Alt text. Example: portfolio image 2
     * @bodyParam images[].order integer optional Display order. Example: 1
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Image(s) added successfully.",
     *   "data": [
     *     {
     *       "type": "projectImages",
     *       "id": 21,
     *       "image": "/storage/images/projects/portfolio/portfolio-2.webp",
     *       "alt": "portfolio image 2",
     *       "order": 1,
     *       "link": { "self": "/api/v1/project-images/21" }
     *     }
     *   ]
     * }
     */
    public function store(StoreProjectImageRequest $request, string $slug): JsonResponse
    {
        $data = $request->validated();
        $data['images'] = [];
        foreach ($request->file('images') as $index => $image) {
            $filePath = $this->imageService->handleImageUpload(
                $image['file'],
                config('paths.project_images')
            );
            $data['images'][] = [
                'image' => $filePath,
                'alt'   => $request->input("images.{$index}.alt") ?? null,
                'order' => $request->input("images.{$index}.order") ?? $index,
            ];
        }
        $projectImages = $this->projectImageService->create($slug, $data);

        return $this->success(ProjectImageResource::collection($projectImages), 'Image(s) added successfully.', 200);
    }

    /**
     * Update a project image
     *
     * Replace the file and/or metadata of a single project image.
     *
     * @urlParam slug string required Project slug. Example: personal-portfolio-backend
     * @urlParam id integer required Project image ID. Example: 21
     *
     * @bodyParam image file optional New image file.
     * @bodyParam alt string optional Alt text. Example: portfolio image 2
     * @bodyParam order integer optional Display order. Example: 1
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Image updated successfully.",
     *   "data": {
     *     "type": "projectImages",
     *     "id": 21,
     *     "image": "/storage/images/projects/portfolio/portfolio-2.webp",
     *     "alt": "portfolio image 2",
     *     "order": 1,
     *     "link": { "self": "/api/v1/project-images/21" }
     *   }
     * }
     */
    public function update(UpdateProjectImageRequest $request, string $slug, string $id): JsonResponse
    {
        $data = $request->validated();
        $projectImage = $this->projectImageService->findById($id);
        $data['image'] = $this->imageService->handleImageUpload(
            $request->file('image'),
            config('paths.project_images'),
            $projectImage->image
        );
        $projectImage = $this->projectImageService->updateModel($slug, $projectImage, $data);
        return $this->success(new ProjectImageResource($projectImage), 'Image updated successfully.');
    }

    /**
     * Delete a project image
     *
     * Removes a single project image by ID.
     *
     * @urlParam slug string required Project slug. Example: personal-portfolio-backend
     * @urlParam id integer required Project image ID. Example: 21
     *
     * @response 204 {}
     */
    public function destroy(string $slug, string $id): Response
    {
        $this->projectImageService->deleteById($slug, $id);
        return $this->noContent();
    }
}
