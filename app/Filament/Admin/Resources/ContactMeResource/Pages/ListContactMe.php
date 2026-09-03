<?php

namespace App\Filament\Admin\Resources\ContactMeResource\Pages;

use App\Filament\Admin\Resources\ContactMeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContactMe extends ListRecords
{
    protected static string $resource = ContactMeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
