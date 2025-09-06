<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @group Authentication
 *
 * APIs for user authentication using JWT
 */
class AuthController extends Controller
{
    use ApiResponse;

    /**
     * AuthController constructor.
     *
     * @param AuthService $authService The authentication service layer.
     */
    public function __construct(protected AuthService $authService) {}

    /**
     * Register a new user
     *
     * This endpoint creates a new user and returns a JWT token upon successful registration.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     *
     * @unauthenticated 
     * 
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email of the user. Example: john@example.com
     * @bodyParam password string required The password for the user. Example: Password123!
     * @bodyParam password_confirmation string required Must match the password. Example: Password123!     
     * @bodyParam image string The URL or path to the user profile image (optional). Example: /storage/images/avatar.png
     *
     * @response 201 {
     *  "status": true,
     *  "message": "User registered successfully.",
     *  "data": {
     *      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOi..."
     *  }
     * }
     * @response 422 {
     *  "message": "The given data was invalid.",
     *  "errors": {
     *      "email": ["The email has already been taken."]
     *  }
     * }
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $token = $this->authService->register($request->validated());

        return $this->success($token, 'User registered successfully.', 201);
    }

    /**
     * Login user and retrieve JWT token
     *
     * Attempts to authenticate the user using email and password and returns a JWT token upon success.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     *
     * @unauthenticated 
     * 
     * @bodyParam email string required The email of the user. Example: john@example.com
     * @bodyParam password string required The user's password. Example: Password123!
     *
     * @response 200 {
     *  "status": true,
     *  "message": "You are logged in.",
     *  "data": {
     *      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOi..."
     *  }
     * }
     * @response 401 {
     *  "status": false,
     *  "message": "Invalid credentials"
     * }
     * @response 422 {
     *  "message": "The given data was invalid.",
     *  "errors": {
     *      "email": ["The email field is required."]
     *  }
     * }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        try {
            $token = $this->authService->login($credentials);
            return $this->success($token, 'You are logged in.');
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage(), 401);
        }
    }

    /**
     * Logout the authenticated user
     *
     * Invalidates the current token and logs the user out.
     *
     * @authenticated
     *
     * @response 204 {}
     */
    public function logout(): Response
    {
        $this->authService->logout();
        return $this->noContent();
    }

    /**
     * Get current authenticated user
     *
     * Returns the information of the currently authenticated user.
     *
     * @authenticated
     *
     * @response 200 {
     *  "status": true,
     *  "message": "OK",
     *  "data": {
     *      "user": {
     *          "id": 1,
     *          "name": "John Doe",
     *          "email": "john@example.com",
     *          "image": "/storage/images/avatar.png"
     *      }
     *  }
     * }
     */
    public function me(): JsonResponse
    {
        $user = $this->authService->me();
        return $this->success(['user' => $user]);
    }

    /**
     * Refresh JWT token
     *
     * Returns a new JWT token by refreshing the current one.
     *
     * @authenticated
     *
     * @response 200 {
     *  "status": true,
     *  "message": "",
     *  "data": {
     *      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOi..."
     *  }
     * }
     * @response 401 {
     *  "status": false,
     *  "message": "Token has expired"
     * }
     */
    public function refresh(): JsonResponse
    {
        $token = $this->authService->refresh();
        return $this->success($token, '');
    }
}
