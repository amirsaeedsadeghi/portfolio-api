<?php

namespace App\Observers;

use App\Enums\AssetTypeEnum;
use App\Models\User;
use App\Services\AssetService;

class UserObserver
{
    /**
     * Create a new UserObserver instance.
     *
     * @param AssetService $assetService
     */
    public function __construct(private readonly AssetService $assetService) {}

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Delete the previous avatar when it has been replaced.
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user): void
    {
        if (! $user->wasChanged('image')) {
            return;
        }

        $oldImage = $user->getOriginal('image');

        if (blank($oldImage) || $oldImage === $user->image) {
            return;
        }

        $this->assetService->delete($oldImage, AssetTypeEnum::AVATAR);
    }

    /**
     * Delete the avatar when the user is deleted.
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user): void
    {
        $this->assetService->delete($user->image, AssetTypeEnum::AVATAR);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
