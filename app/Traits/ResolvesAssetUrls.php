<?php

namespace App\Traits;

use App\Enums\AssetTypeEnum;
use App\Support\AssetUrl;

/**
 * Trait ResolvesAssetUrls
 *
 * Provides HTTP resources with a reusable presentation helper for resolving
 * stored asset paths into publicly accessible URLs.
 *
 * This trait is intentionally limited to URL presentation and does not
 * perform asset storage or lifecycle operations.
 */
trait ResolvesAssetUrls
{
    /**
     * Resolve a stored asset path to its public URL.
     *
     * @param string|null $path The stored relative asset path or absolute URL.
     * @param AssetTypeEnum $type The asset category used to resolve the public URL.
     * @return string|null The resolved public URL, or null when no path is provided.
     */
    protected function assetUrl(?string $path, AssetTypeEnum $type): ?string
    {
        return AssetUrl::url($path, $type);
    }
}
