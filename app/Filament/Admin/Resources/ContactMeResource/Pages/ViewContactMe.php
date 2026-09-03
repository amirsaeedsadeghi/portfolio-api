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

    /**
     * Mark the contact message as read when it is viewed.
     *
     * @return void
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record->read_at === null) {
            $this->record->update([
                'read_at' => now(),
            ]);
        }

        return $data;
    }

    /**
     * Initialize the message view and mark the message as read.
     *
     * @param int|string $record
     * @return void
     */
    public function mount(int|string $record): void
    {
        parent::mount($record);

        if ($this->record->read_at === null) {
            $this->record->update([
                'read_at' => now(),
            ]);
        }
    }
}
