<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Exception;

/**
 * Class AuthService
 *
 * Coordinates user authentication and registration using separate repository layers.
 * Relies on JWT for issuing and managing tokens.
 */
class AuthService
{
    /**
     * AuthService constructor.
     *
     * @param AuthRepositoryInterface $authRepo Handles token-based authentication logic (JWT).
     * @param UserRepositoryInterface $userRepo Handles persistence of user records.
     */

    public function __construct(protected AuthRepositoryInterface $authRepo, protected UserRepositoryInterface $userRepo) {}

    /**
     * Register a new user and return JWT token.
     *
     * This method creates a new user using the UserService and generates a JWT token for the user.
     *
     * @param array $data Validated user registration data (name, email, password, etc.)
     * @return array{token: string} An array containing the JWT token.
     */
    public function register(array $data): array
    {
        $user = $this->userRepo->create($data);
        $token = $this->authRepo->makeToken($user);
        return ['token' => $token];
    }

    /**
     * Authenticate user and return JWT token.
     *
     * Attempts to authenticate the user with given credentials.
     * Throws an exception if authentication fails.
     *
     * @param array $credentials Must include 'email' and 'password'
     * @return array{token: string} An array containing the JWT token.
     *
     * @throws Exception if credentials are invalid.
     */
    public function login(array $credentials): array
    {
        if (!$token = $this->authRepo->attempt($credentials)) {
            throw new Exception('Invalid credentials.');
        }
        return ['token' => $token];
    }

    /**
     * Get the currently authenticated user.
     *
     * @return User The authenticated user.
     */
    public function me(): User
    {
        return $this->authRepo->current();
    }

    /**
     * Logout the authenticated user.
     *
     * Invalidates the current JWT token.
     *
     * @return void
     */
    public function logout(): void
    {
        $this->authRepo->logout();
    }

    /**
     * Refresh the current JWT token.
     *
     * @return array{token: string} An array containing the new JWT token.
     */
    public function refresh(): array
    {
        return ['token' => $this->authRepo->refresh()];
    }
}
