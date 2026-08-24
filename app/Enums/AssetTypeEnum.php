<?php

namespace App\Enums;

/**
 * Enum AssetTypeEnum
 *
 * Defines the supported asset categories used to resolve storage disks,
 * directories, and public URLs across the application.
 */
enum AssetTypeEnum: string
{
    case PROFILE = 'profile';
    case PROJECT = 'project';
    case STACK = 'stack';
    case BRAND = 'brand';
    case DOCUMENT = 'document';
}
