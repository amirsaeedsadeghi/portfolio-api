<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class AuthRepository
 *
 * Repository handling authentication logic via JWT.
 *
 * Responsibilities:
 * - Authenticating users with credentials.
 * - Generating and refreshing JWT tokens.
 * - Logging out and invalidating tokens.
 * - Retrieving the currently authenticated user.
 *
 * This repository decouples authentication logic from controllers,
 * providing a single point for all JWT-based operations.
 */
class AuthRepository implements AuthRepositoryInterface
{
    /**
     * Generate a JWT token for the given user.
     *
     * @param User $user The user for whom to create the token.
     * @return string A JWT token representing the authenticated session.
     */
    public function makeToken(User $user): string
    {
        return JWTAuth::fromUser($user);
    }

    /**
     * Attempt to authenticate the user with the given credentials.
     *
     * @param array $credentials The user credentials (e.g. ['email' => ..., 'password' => ...]).
     * @return string|null The JWT token if authentication succeeds, or null if it fails.
     */
    public function attempt(array $credentials): ?string
    {
        return Auth::guard('api')->attempt($credentials) ?: null;
    }

    /**
     * Log out the currently authenticated user.
     *
     * Invalidates the current JWT token if present.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::guard('api')->logout();
    }

    /**
     * Refresh the current JWT token.
     *
     * @return string A new JWT token.
     *
     * @throws \Tymon\JWTAuth\Exceptions\JWTException If token cannot be refreshed.
     */
    public function refresh(): string
    {
        /** @var \Tymon\JWTAuth\JWTGuard $guard */
        $guard = Auth::guard('api');
        return $guard->refresh();
    }

    /**
     * Retrieve the currently authenticated user.
     *
     * @return User|null The authenticated user instance, or null if not logged in.
     */
    public function current(): ?User
    {
        return Auth::guard('api')->user();
    }
}
