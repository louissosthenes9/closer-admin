<?php

namespace App\Filament\Resources\QuestionSubcategories\Pages;

use App\Filament\Resources\QuestionSubcategories\QuestionSubcategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQuestionSubcategories extends ListRecords
{
    protected static string $resource = QuestionSubcategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
