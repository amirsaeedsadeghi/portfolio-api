<?php

namespace Tests\Feature;

use App\Enums\UserRoleEnum;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdminToken(): string
    {
        $user = User::factory()->create(['role' => UserRoleEnum::ADMIN->value]);
        $res = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password', // default from factory
        ])->assertOk();

        return $res->json('data.token');
    }

    public function test_public_index_and_show(): void
    {
        $p = Project::create([
            'title' => 'My Demo',
            'summary' => 'Summary',
            'description' => '<p>Desc</p>',
            'category' => 'Framework',
            'order' => 1,
            'slug' => 'my-demo',
            'role' => 'Developer',
            'start_date' => now()->toDateString(),
            'primary_image' => 'a.jpg'
        ]);

        $this->getJson('/api/v1/projects')
            ->assertOk()
            ->assertJsonPath('data.0.type', 'projects');

        $this->getJson('/api/v1/projects/my-demo')
            ->assertOk()
            ->assertJsonPath('data.slug', 'my-demo');
    }

    public function test_create_update_delete_requires_auth_and_policies(): void
    {
        Storage::fake('public');

        // unauthorized create blocked
        $this->postJson('/api/v1/projects', [])->assertUnauthorized();

        $token = $this->actingAsAdminToken();

        $create = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/projects', [
                'title' => 'New Project',
                'summary' => 'Nice Project',
                'description' => '<p>description of project</p>',
                'category' => 'Framework',
                'order' => 10,
                'role' => 'Lead',
                'startDate' => '2025-07-02',
                'primaryImage' => UploadedFile::fake()->image('cover.jpg'),
            ])->assertCreated()
            ->assertJsonPath('data.slug', 'new-project');

        $id = $create->json('data.id');

        // Update
        $this->withHeader('Authorization', "Bearer {$token}")
            ->patchJson("/api/v1/projects/{$id}", [
                'title' => 'Updated',
            ])->assertOk()
            ->assertJsonPath('data.title', 'Updated');

        // Delete
        $this->withHeader('Authorization', "Bearer {$token}")
            ->deleteJson("/api/v1/projects/{$id}")
            ->assertNoContent();
    }

    public function test_filtering_and_sorting_params(): void
    {
        Project::insert([
            [
                'title' => 'B',
                'summary' => 's',
                'description' => '<p></p>',
                'category' => 'Framework',
                'order' => 2,
                'slug' => 'b',
                'role' => 'Dev',
                'start_date' => now()->subDay()->toDateString(),
                'primary_image' => 'a.jpg'
            ],
            [
                'title' => 'A',
                'summary' => 's',
                'description' => '<p></p>',
                'category' => 'Framework',
                'order' => 1,
                'slug' => 'a',
                'role' => 'Dev',
                'start_date' => now()->toDateString(),
                'primary_image' => 'b.jpg'
            ]
        ]);

        $res = $this->getJson('/api/v1/projects?sort=title')
            ->assertOk();

        $first = $res->json('data.0.title');
        $this->assertSame('A', $first, 'Sorting by title should work');
    }
}
