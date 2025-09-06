<?php

namespace App\Http\Controllers\Api\V1;

use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\ImageService;
use App\Http\Filters\UserFilter;
use App\Http\Resources\UserResource;
use App\Http\Requests\V1\User\StoreUserRequest;
use App\Http\Requests\V1\User\UpdateUserRequest;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected UserService $userService,
        protected ImageService $imageService
    ) {}

    /**
     * Display a paginated list of users.
     *
     * @param  UserFilter  $filters  Query filters for users
     * @return JsonResponse Paginated list of user resources
     *
     * @response 200 {
     *   "data": [
     *     { "id": 1, "name": "John Doe", "email": "john@example.com", "image": "..." }
     *   ],
     *   "meta": { "current_page": 1, "last_page": 10 }
     * }
     */
    public function index(UserFilter $filters): JsonResponse
    {
        $users = $this->userService->paginate($filters);

        return $this->success(UserResource::collection($users));
    }

    /**
     * Display the specified user.
     *
     * @param  string|int  $id
     * @return JsonResponse User resource
     *
     * @response 200 {
     *   "data": { "id": 1, "name": "John Doe", "email": "john@example.com", "image": "..." }
     * }
     */
    public function show(string|int $id): JsonResponse
    {
        $user = $this->userService->findById($id);

        return $this->success(new UserResource($user));
    }

    /**
     * Store a newly created user.
     *
     * @param  StoreUserRequest  $storeUserRequest
     * @return JsonResponse Created user resource
     *
     * @response 201 {
     *   "data": { "id": 1, "name": "Jane Doe", "email": "jane@example.com", "image": "..." },
     *   "message": "User created successfully."
     * }
     */
    public function store(StoreUserRequest $storeUserRequest): JsonResponse
    {
        $data = $storeUserRequest->validated();
        $data['image'] = $this->imageService->handleImageUpload(
            $storeUserRequest->file('image'),
            config('paths.user_images')
        );

        $user = $this->userService->create($data);

        return $this->success(new UserResource($user), 'User created successfully.', 201);
    }

    /**
     * Update the specified user.
     *
     * @param  UpdateUserRequest  $updateUserRequest
     * @param  string|int         $id 
     * @return JsonResponse Updated user resource
     *
     * @response 200 {
     *   "data": { "id": 1, "name": "Jane Doe", "email": "jane@example.com", "image": "..." },
     *   "message": "User updated successfully."
     * }
     */
    public function update(UpdateUserRequest $updateUserRequest, string|int $id): JsonResponse
    {
        $user = $this->userService->findById($id);
        $data = $updateUserRequest->safe()->except('role');
        if ($updateUserRequest->user()->isAdmin()) {
            $data = $updateUserRequest->validated();
        }

        $data['image'] = $this->imageService->handleImageUpload(
            $updateUserRequest->file('image'),
            config('paths.user_images'),
            $user->image
        );

        $user = $this->userService->updateModel($user, $data);

        return $this->success(new UserResource($user), 'User updated successfully.', 200);
    }

    /**
     * Remove the specified user.
     *
     * @param  string|int  $id
     * @return Response 204 No Content
     *
     * @response 204 {}
     */
    public function destroy(string|int $id): Response
    {
        $this->userService->deleteById($id);

        return $this->noContent();
    }
}
