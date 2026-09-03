<?php

namespace App\Filament\Admin\Resources\ContactMeResource\Pages;

use App\Filament\Admin\Resources\ContactMeResource;
use Filament\Resources\Pages\ViewRecord;

/**
 * Displays a received contact message.
 */
class ViewContactMe extends ViewRecord
{
    protected static string $resource = ContactMeResource::class;
}
