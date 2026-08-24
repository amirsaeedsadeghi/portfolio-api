<?php

namespace App\Support;

use App\Enums\AssetTypeEnum;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

/**
 * Class AssetStorageResolver
 *
 * Resolves filesystem configuration for each supported asset type.
 * Provides centralized access to the configured disk, directory,
 * and filesystem adapter without exposing storage configuration
 * throughout the application.
 */
final class AssetStorageResolver
{
    /**
     * Get the configured filesystem disk name for an asset type.
     *
     * @param AssetTypeEnum $type The asset category to resolve.
     * @return string The configured filesystem disk name.
     *
     * @throws InvalidArgumentException If the disk configuration is missing or invalid.
     */
    public static function diskName(AssetTypeEnum $type): string
    {
        return self::config($type, 'disk');
    }

    /**
     * Get the configured storage directory for an asset type.
     *
     * @param AssetTypeEnum $type The asset category to resolve.
     * @return string The configured directory path.
     *
     * @throws InvalidArgumentException If the directory configuration is missing or invalid.
     */
    public static function directory(AssetTypeEnum $type): string
    {
        return self::config($type, 'directory');
    }

    /**
     * Resolve the filesystem adapter for an asset type.
     *
     * @param AssetTypeEnum $type The asset category to resolve.
     * @return FilesystemAdapter The configured filesystem adapter.
     *
     * @throws InvalidArgumentException If the disk configuration is missing or invalid.
     */
    public static function disk(AssetTypeEnum $type): FilesystemAdapter
    {
        return Storage::disk(self::diskName($type));
    }

    /**
     * Retrieve and validate a configuration value for an asset type.
     *
     * @param AssetTypeEnum $type The asset category to resolve.
     * @param string $key The configuration key to retrieve.
     * @return string The validated configuration value.
     *
     * @throws InvalidArgumentException If the configuration value is missing or invalid.
     */
    private static function config(AssetTypeEnum $type, string $key): string
    {
        $value = config("assets.types.{$type->value}.{$key}");

        if (! is_string($value) || blank($value)) {
            throw new InvalidArgumentException("Asset configuration [{$key}] for type [{$type->value}] is invalid.");
        }

        return $value;
    }
}
