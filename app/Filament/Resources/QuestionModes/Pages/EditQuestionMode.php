<?php

namespace App\Filament\Resources\QuestionModes\Pages;

use App\Filament\Resources\QuestionModes\QuestionModeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditQuestionMode extends EditRecord
{
    protected static string $resource = QuestionModeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
