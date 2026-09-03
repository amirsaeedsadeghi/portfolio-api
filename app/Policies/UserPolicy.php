<?php

namespace App\Policies;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $authUser): bool
    {
        //        
        return $authUser->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $authUser, User $targetUser): bool
    {
        //
        return $authUser->isAdmin() || $targetUser->id === $authUser->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $authUser): bool
    {
        //
        return $authUser->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $authUser, User $targetUser): bool
    {
        //
        return $authUser->isAdmin() || $authUser->id === $targetUser->id;
    }

    /**
     * Determine whether the authenticated user can delete the target user.
     *
     * @param User $authUser The authenticated user.
     * @param User $targetUser The user being deleted.
     * @return bool True when deletion is allowed.
     */
    public function delete(User $authUser, User $targetUser): bool
    {
        if (! $authUser->isAdmin()) {
            return false;
        }

        if ($authUser->is($targetUser)) {
            return false;
        }

        if (! $targetUser->isAdmin()) {
            return true;
        }

        return User::query()
            ->where('role', UserRoleEnum::ADMIN->value)
            ->count() > 1;
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, User $model): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, User $model): bool
    // {
    //     //
    // }
}
