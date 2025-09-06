<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

/**
 * Interface AuthRepositoryInterface
 *
 * Defines the contract for JWT-based authentication operations.
 * Implementations should handle login, logout, token creation,
 * token refresh, and retrieving the current authenticated user.
 */
interface AuthRepositoryInterface
{
    /**
     * Generate a JWT token for the given user.
     *
     * @param User $user The user for whom the token should be created.
     * @return string The generated JWT token.
     */
    public function makeToken(User $user): string;

    /**
     * Attempt to authenticate the user with provided credentials.
     *
     * @param array $credentials Must contain 'email' and 'password'.
     * @return string|null A JWT token if authentication succeeds, or null if it fails.
     */
    public function attempt(array $credentials): ?string;

    /**
     * Log out the currently authenticated user and invalidate their token.
     *
     * @return void
     */
    public function logout(): void;

    /**
     * Refresh the current JWT token and return a new one.
     *
     * @return string The new JWT token.
     */
    public function refresh(): string;

    /**
     * Get the currently authenticated user.
     *
     * @return User|null The authenticated user instance.
     */
    public function current(): ?User;
}
