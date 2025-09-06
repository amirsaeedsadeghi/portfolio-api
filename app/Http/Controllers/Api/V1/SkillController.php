<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\SkillFilter;
use App\Http\Requests\V1\Skill\StoreSkillRequest;
use App\Http\Requests\V1\Skill\UpdateSkillRequest;
use App\Http\Resources\SkillResource;
use App\Services\SkillService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @group Skills
 *
 * APIs for managing skills.
 */
class SkillController extends Controller
{
    use ApiResponse;

    public function __construct(protected SkillService $service) {}

    /**
     * Display a paginated list of skills.
     *
     * @param  SkillFilter  $filters  Query filters for skills
     * @return JsonResponse Paginated list of skill resources
     *
     * @unauthenticated 
     * 
     * @response 200 {
     *   "data": [
     *     { "id": 1, "name": "PHP", "level": "advanced" }
     *   ],
     *   "meta": { "current_page": 1, "last_page": 5 }
     * }
     */
    public function index(SkillFilter $filters): JsonResponse
    {
        $skills = $this->service->paginate($filters);

        return $this->success(SkillResource::collection($skills));
    }

    /**
     * Store a newly created skill.
     *
     * @param  StoreSkillRequest  $request
     * @return JsonResponse Created skill resource
     *
     * @response 201 {
     *   "data": { "id": 1, "name": "PHP", "level": "advanced" },
     *   "message": "Skill created successfully"
     * }
     */
    public function store(StoreSkillRequest $request): JsonResponse
    {
        $data = $request->validated();
        $skill = $this->service->create($data);

        return $this->success(new SkillResource($skill), 'Skill created successfully', 201);
    }

    /**
     * Display the specified skill.
     *
     * @param  string  $id
     * @return JsonResponse Skill resource
     *
     * @response 200 {
     *   "data": { "id": 1, "name": "PHP", "level": "advanced" }
     * }
     */
    public function show(string $id): JsonResponse
    {
        $skill = $this->service->findById($id);

        return $this->success(new SkillResource($skill));
    }

    /**
     * Update the specified skill.
     *
     * @param  UpdateSkillRequest  $request
     * @param  string $id
     * @return JsonResponse Updated skill resource
     *
     * @response 200 {
     *   "data": { "id": 1, "name": "PHP", "level": "expert" },
     *   "message": "Skill updated successfully"
     * }
     */
    public function update(UpdateSkillRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        $skill = $this->service->update($id, $data);

        return $this->success(new SkillResource($skill), 'Skill updated successfully.');
    }

    /**
     * Remove the specified skill.
     *
     * @param  string $id
     * @return Response 204 No Content
     *
     * @response 204 {}
     */
    public function destroy(string $id): Response
    {
        $this->service->delete($id);

        return $this->noContent();
    }
}
