<?php

namespace App\Filament\Resources\QuestionModes\Pages;

use App\Filament\Resources\QuestionModes\QuestionModeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQuestionModes extends ListRecords
{
    protected static string $resource = QuestionModeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
