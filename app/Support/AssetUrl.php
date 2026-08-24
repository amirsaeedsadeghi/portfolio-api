<?php

namespace App\Support;

use App\Enums\AssetTypeEnum;
use App\Support\AssetStorageResolver;

/**
 * Class AssetUrl
 *
 * Generates public URLs for application assets based on their configured
 * asset type while preserving already absolute URLs unchanged.
 */
final class AssetUrl
{
    /**
     * Generate the public URL for an asset path.
     *
     * Absolute HTTP and HTTPS URLs are returned unchanged. Relative paths
     * are resolved through the filesystem configured for the given asset type.
     *
     * @param string|null $path The stored relative asset path or absolute URL.
     * @param AssetTypeEnum $type The asset category used to resolve storage configuration.
     * @return string|null The resolved public URL, or null when no path is provided.
     */
    public static function url(?string $path, AssetTypeEnum $type): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (self::isAbsoluteUrl($path)) {
            return $path;
        }

        return AssetStorageResolver::disk($type)->url(ltrim($path, '/'));
    }

    /**
     * Determine whether the given path is an absolute HTTP or HTTPS URL.
     *
     * @param string $path The path or URL to inspect.
     * @return bool True when the value is an absolute HTTP or HTTPS URL.
     */
    protected static function isAbsoluteUrl(string $path): bool
    {
        return str_starts_with($path, 'http://') || str_starts_with($path, 'https://');
    }
}
