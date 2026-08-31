<?php

namespace App\Filament\Admin\Resources\SkillResource\Pages;

use App\Filament\Admin\Resources\SkillResource;
use App\Models\Skill;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSkill extends CreateRecord
{
    protected static string $resource = SkillResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['order'] = (int) Skill::query()->max('order') + 1;

        return $data;
    }
}
