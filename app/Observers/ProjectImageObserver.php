<?php

namespace App\Observers;

use App\Enums\AssetTypeEnum;
use App\Models\ProjectImage;
use App\Services\AssetService;

class ProjectImageObserver
{
    /**
     * Create a new ProjectImageObserver instance.
     *
     * @param AssetService $assetService
     */
    public function __construct(private readonly AssetService $assetService) {}

    /**
     * Handle the ProjectImage "created" event.
     */
    public function created(ProjectImage $projectImage): void
    {
        //
    }

    /**
     * Delete the previous image when it has been replaced.
     *
     * @param ProjectImage $projectImage
     * @return void
     */
    public function updated(ProjectImage $projectImage): void
    {
        if (! $projectImage->wasChanged('image')) {
            return;
        }

        $oldImage = $projectImage->getOriginal('image');

        if (blank($oldImage) || $oldImage === $projectImage->image) {
            return;
        }

        $this->assetService->delete($oldImage, AssetTypeEnum::PROJECT);
    }

    /**
     * Delete the image file when a gallery image is deleted directly.
     *
     * @param ProjectImage $projectImage
     * @return void
     */
    public function deleted(ProjectImage $projectImage): void
    {
        $this->assetService->delete($projectImage->image, AssetTypeEnum::PROJECT);
    }

    /**
     * Handle the ProjectImage "restored" event.
     */
    public function restored(ProjectImage $projectImage): void
    {
        //
    }

    /**
     * Handle the ProjectImage "force deleted" event.
     */
    public function forceDeleted(ProjectImage $projectImage): void
    {
        //
    }
}
