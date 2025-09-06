<?php

namespace Tests\Unit;

use App\Http\Filters\ProjectFilter;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QueryFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_sorting_by_title_and_like_search(): void
    {
        Project::insert([
            ['title' => 'Zed', 'summary' => 's', 'description' => '<p/>', 'category' => 'F', 'order' => 2, 'slug' => 'zed', 'role' => 'Dev', 'start_date' => now()->toDateString(), 'primary_image' => 'zed.jpg'],
            ['title' => 'Alpha', 'summary' => 's', 'description' => '<p/>', 'category' => 'F', 'order' => 1, 'slug' => 'alpha', 'role' => 'Dev', 'start_date' => now()->toDateString(), 'primary_image' => 'alpha.jpg'],
        ]);

        $filter = new ProjectFilter(['sort' => 'title', 'filter' => ['title' => '*Al*']]);
        $q = Project::query();
        $filter->apply($q);
        $rows = $q->pluck('title')->all();
        $this->assertSame(['Alpha'], $rows);
    }
}
