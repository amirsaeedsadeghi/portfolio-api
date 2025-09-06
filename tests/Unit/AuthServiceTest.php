<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\AuthService;
use Mockery;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function test_register_delegates_to_user_repo_and_auth_repo(): void
    {
        $authRepo = Mockery::mock(AuthRepositoryInterface::class);
        $userRepo = Mockery::mock(UserRepositoryInterface::class);

        $user = new User();
        $user->id = 1;
        $user->email = 'a@b.c';

        $userRepo->shouldReceive('create')->once()->andReturn($user)->ordered();
        $authRepo->shouldReceive('makeToken')->once()->with($user)->andReturn('token');

        $svc = new AuthService($authRepo, $userRepo,);

        $result = $svc->register(['email' => 'a@b.c', 'password' => 'pass']);



        $this->assertIsArray($result);
        $this->assertArrayHasKey('token', $result);
        $this->assertSame('token', $result['token']);
    }

    public function test_refresh_and_logout_and_current_user(): void
    {
        $authRepo = Mockery::mock(AuthRepositoryInterface::class);
        $userRepo = Mockery::mock(UserRepositoryInterface::class);

        $authRepo->shouldReceive('refresh')->once()->andReturn('new-token');
        $authRepo->shouldReceive('logout')->once();
        $authRepo->shouldReceive('current')->once()->andReturn(new User(['email' => 'x@y.z']));

        $svc = new AuthService($authRepo, $userRepo);

        $this->assertSame(['token' => 'new-token'], $svc->refresh());
        $svc->logout();
        $this->assertSame('x@y.z', $svc->me()?->email);
    }
}
