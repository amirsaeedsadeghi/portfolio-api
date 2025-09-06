<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\StackFilter;
use App\Http\Requests\V1\Stack\StoreStackRequest;
use App\Http\Requests\V1\Stack\UpdateStackRequest;
use App\Http\Resources\StackResource;
use App\Services\ImageService;
use App\Services\StackService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class StackController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected StackService $stackService,
        protected ImageService $imageService
    ) {}

    /**
     * Display a paginated list of stacks.
     *
     * @unauthenticated 
     * 
     * @param  StackFilter  $filters  Query filters for stacks
     * @return JsonResponse Paginated list of Stack resources
     *
     * @response 200 {
     *   "data": [ { "id": 1, "name": "Laravel", "image": "..." } ],
     *   "meta": { "current_page": 1, "last_page": 1 }
     * }
     */
    public function index(StackFilter $filters): JsonResponse
    {
        $stacks = $this->stackService->paginate($filters);

        return $this->success(StackResource::collection($stacks));
    }

    /**
     * Store a newly created stack.
     *
     * @param  StoreStackRequest  $request  Validated request data
     * @return JsonResponse Created Stack resource
     *
     * @response 201 {
     *   "data": { "id": 1, "name": "React", "image": "..." },
     *   "message": "Stack created successfully"
     * }
     */
    public function store(StoreStackRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['image'] = $this->imageService->handleImageUpload(
            $request->file('image'),
            config('paths.stack_images'),
        );

        $stack = $this->stackService->create($data);

        return $this->success(new StackResource($stack), 'Stack created successfully', 201);
    }

    /**
     * Display the specified stack.
     *
     * @param  string  $id  Stack ID
     * @return JsonResponse Stack resource
     *
     * @response 200 {
     *   "data": { "id": 1, "name": "Vue.js", "image": "..." }
     * }
     */
    public function show(string $id): JsonResponse
    {
        $stack = $this->stackService->findById($id);

        return $this->success(new StackResource($stack));
    }

    /**
     * Update the specified stack.
     *
     * @param  UpdateStackRequest  $request  Validated request data
     * @param  string|int          $id    Stack model to update
     * @return JsonResponse Updated Stack resource
     *
     * @response 200 {
     *   "data": { "id": 1, "name": "Laravel 11", "image": "..." },
     *   "message": "Stack updated successfully"
     * }
     */
    public function update(UpdateStackRequest $request, string|int $id): JsonResponse
    {

        $stack = $this->stackService->findById($id);
        $data = $request->validated();
        $data['image'] = $this->imageService->handleImageUpload(
            $request->file('image'),
            config('paths.stack_images'),
            $stack->image
        );

        $stack = $this->stackService->updateModel($stack, $data);

        return $this->success(new StackResource($stack), 'Stack updated successfully');
    }

    /**
     * Remove the specified stack.
     *
     * @param  string|int  $id  Stack model to delete
     * @return Response 204 No Content
     *
     * @response 204 {}
     */
    public function destroy(string|int $id): Response
    {
        $this->stackService->deleteById($id);
        return $this->noContent();
    }
}
