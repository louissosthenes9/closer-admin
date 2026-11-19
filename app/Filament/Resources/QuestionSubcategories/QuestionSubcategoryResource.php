<?php

namespace App\Filament\Resources\QuestionSubcategories;

use App\Filament\Resources\QuestionSubcategories\Pages\CreateQuestionSubcategory;
use App\Filament\Resources\QuestionSubcategories\Pages\EditQuestionSubcategory;
use App\Filament\Resources\QuestionSubcategories\Pages\ListQuestionSubcategories;
use App\Filament\Resources\QuestionSubcategories\Schemas\QuestionSubcategoryForm;
use App\Filament\Resources\QuestionSubcategories\Tables\QuestionSubcategoriesTable;
use App\Models\QuestionSubcategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class QuestionSubcategoryResource extends Resource
{
    protected static ?string $model = QuestionSubcategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Subcategories';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return QuestionSubcategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuestionSubcategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuestionSubcategories::route('/'),
            'create' => CreateQuestionSubcategory::route('/create'),
            'edit' => EditQuestionSubcategory::route('/{record}/edit'),
        ];
    }
}
