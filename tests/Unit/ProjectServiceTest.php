<?php

namespace Tests\Unit;

use Mockery;
use App\Models\Project;
use PHPUnit\Framework\TestCase;
use App\Services\ProjectService;
use Illuminate\Support\Facades\Gate;
use App\Http\Filters\QueryFilterInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\ProjectRepositoryInterface;

class ProjectServiceTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function test_all_delegates_to_active_ordered_repository_query(): void
    {
        $repo = Mockery::mock(ProjectRepositoryInterface::class);
        $filter = Mockery::mock(QueryFilterInterface::class);

        $eloquent = new Collection();
        $repo->shouldReceive('allActiveWithOrder')->once()->with($filter)->andReturn($eloquent);

        $svc = new ProjectService($repo);
        $res = $svc->all($filter);

        $this->assertSame($eloquent, $res);
    }

    public function test_store_and_update_delete(): void
    {

        $repo = Mockery::mock(ProjectRepositoryInterface::class);
        $svc = new ProjectService($repo);
        $project = Mockery::mock(Project::class);
        $updated = Mockery::mock(Project::class);

        // create
        Gate::shouldReceive('authorize')
            ->once()
            ->with('create', Project::class)
            ->andReturnTrue();
        $repo->shouldReceive('create')->once()->with([])->andReturn($project);
        $this->assertSame($project, $svc->create([]));

        //updateById
        Gate::shouldReceive('authorize')
            ->once()
            ->with('update', Project::class)
            ->andReturnTrue();
        $repo->shouldReceive('findById')->once()->with(1)->andReturn($project);
        $repo->shouldReceive('updateModel')->once()->with($project, ['title' => 'Y'])->andReturn($updated);
        $this->assertSame($updated, $svc->updateById(1, ['title' => 'Y']));

        // deleteModel
        Gate::shouldReceive('authorize')
            ->once()
            ->with('delete', Project::class)
            ->andReturnTrue();
        $repo->shouldReceive('deleteModel')->once()->with($project);
        $svc->deleteModel($project);
        $this->assertTrue(true);
    }
}
