<?php

namespace App\Services;

use App\Enums\AssetTypeEnum;
use App\Support\AssetStorageResolver;

/**
 * Class AssetService
 *
 * Handles filesystem operations for application assets while delegating
 * storage location resolution to AssetStorageResolver.
 */
final class AssetService
{
    /**
     * Determine whether an asset exists in its configured storage.
     *
     * @param string|null $path The stored relative asset path.
     * @param AssetTypeEnum $type The asset category used to resolve the filesystem.
     * @return bool True when the asset exists, otherwise false.
     */
    public function exists(?string $path, AssetTypeEnum $type): bool
    {
        if (blank($path)) {
            return false;
        }

        return AssetStorageResolver::disk($type)->exists(ltrim($path, '/'));
    }

    /**
     * Delete an asset from its configured storage.
     *
     * The operation is safely ignored when the path is empty or
     * the referenced asset does not exist.
     *
     * @param string|null $path The stored relative asset path.
     * @param AssetTypeEnum $type The asset category used to resolve the filesystem.
     * @return bool True when the asset is deleted, otherwise false.
     */
    public function delete(?string $path, AssetTypeEnum $type): bool
    {
        if (blank($path)) {
            return false;
        }

        $path = ltrim($path, '/');

        $disk = AssetStorageResolver::disk($type);

        if (! $disk->exists($path)) {
            return false;
        }

        return $disk->delete($path);
    }
}
