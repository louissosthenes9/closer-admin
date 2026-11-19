<?php

namespace App\Filament\Resources\QuestionSubcategories\Pages;

use App\Filament\Resources\QuestionSubcategories\QuestionSubcategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditQuestionSubcategory extends EditRecord
{
    protected static string $resource = QuestionSubcategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
