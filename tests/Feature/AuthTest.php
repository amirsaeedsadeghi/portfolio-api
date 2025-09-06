<?php

namespace Tests\Feature;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure sqlite testing is configured; fallback will use default connection.
        // Consider adding to phpunit.xml:
        // <env name="DB_CONNECTION" value="sqlite"/>
        // <env name="DB_DATABASE" value=":memory:"/>
    }

    public function test_register_and_login_and_me_flow(): void
    {
        // Register
        $payload = [
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'Password123!',
            'passwordConfirmation' => 'Password123!',
            'role' => UserRoleEnum::ADMIN->value,
        ];

        $res = $this->postJson('/api/v1/auth/register', $payload)
            ->assertCreated()
            ->assertJsonStructure(['status', 'message', 'data' => ['token']]);

        $token = $res->json('data.token');
        $this->assertNotEmpty($token, 'JWT token must be returned on register');

        // Login
        $res = $this->postJson('/api/v1/auth/login', [
            'email' => 'alice@example.com',
            'password' => 'Password123!',
        ])->assertOk()
            ->assertJsonStructure(['status', 'message', 'data' => ['token']]);

        $loginToken = $res->json('data.token');
        $this->assertNotEmpty($loginToken);

        // Me (protected)
        $me = $this->withHeader('Authorization', "Bearer {$loginToken}")
            ->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('data.user.email', 'alice@example.com');
    }

    public function test_logout_and_refresh(): void
    {
        $user = User::factory()->create([
            'password' => 'Password123!'
        ]);

        // login to get token
        $token = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'Password123!'
        ])->json('data.token');

        // refresh
        $refreshed = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/refresh')
            ->assertOk()
            ->json('data.token');
        $this->assertNotEmpty($refreshed);

        // logout
        $this->withHeader('Authorization', "Bearer {$refreshed}")
            ->postJson('/api/v1/auth/logout')
            ->assertNoContent();
    }
}
