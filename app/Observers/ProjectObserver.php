<?php

namespace App\Observers;

use App\Enums\AssetTypeEnum;
use App\Models\Project;
use App\Services\AssetService;

class ProjectObserver
{

    /**
     * Create a new ProjectObserver instance.
     *
     * @param AssetService $assetService
     */
    public function __construct(private readonly AssetService $assetService) {}

    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        //
    }

    /**
     * Delete the previous primary image when it has been replaced.
     *
     * @param Project $project
     * @return void
     */
    public function updated(Project $project): void
    {
        if (! $project->wasChanged('primary_image')) {
            return;
        }

        $oldImage = $project->getOriginal('primary_image');

        if (blank($oldImage) || $oldImage === $project->primary_image) {
            return;
        }

        $this->assetService->delete($oldImage, AssetTypeEnum::PROJECT);
    }

    /**
     * Handle the ProjectImage "deleted" event.
     */
    public function deleted(Project $project): void
    {
        //
    }

    /**
     * Delete all project assets before the project is removed.
     *
     * @param Project $project
     * @return void
     */
    public function deleting(Project $project): void
    {
        $this->assetService->delete($project->primary_image, AssetTypeEnum::PROJECT);

        foreach ($project->images as $image) {
            $this->assetService->delete($image->image, AssetTypeEnum::PROJECT);
        }
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
