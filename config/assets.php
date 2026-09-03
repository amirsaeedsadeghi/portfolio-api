<?php

use App\Enums\AssetTypeEnum;

return [

    'types' => [

        AssetTypeEnum::PROFILE->value => [
            'disk' => 'public',
            'directory' => 'users',
        ],

        AssetTypeEnum::PROJECT->value => [
            'disk' => 'public',
            'directory' => 'projects',
        ],

        AssetTypeEnum::STACK->value => [
            'disk' => 'frontend',
            'directory' => 'stacks',
        ],

        AssetTypeEnum::DOCUMENT->value => [
            'disk' => 'public',
            'directory' => 'documents',
        ],

        AssetTypeEnum::BRAND->value => [
            'disk' => 'frontend',
            'directory' => 'assets/brand',
        ],

        AssetTypeEnum::AVATAR->value => [
            'disk' => 'public',
            'directory' => 'users/avatars',
        ],

    ],

];
