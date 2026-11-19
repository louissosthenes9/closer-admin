<?php

namespace App\Filament\Resources\QuestionModes;

use App\Filament\Resources\QuestionModes\Pages\CreateQuestionMode;
use App\Filament\Resources\QuestionModes\Pages\EditQuestionMode;
use App\Filament\Resources\QuestionModes\Pages\ListQuestionModes;
use App\Filament\Resources\QuestionModes\Schemas\QuestionModeForm;
use App\Filament\Resources\QuestionModes\Tables\QuestionModesTable;
use App\Models\QuestionMode;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class QuestionModeResource extends Resource
{
    protected static ?string $model = QuestionMode::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Modes';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return QuestionModeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuestionModesTable::configure($table);
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
            'index' => ListQuestionModes::route('/'),
            'create' => CreateQuestionMode::route('/create'),
            'edit' => EditQuestionMode::route('/{record}/edit'),
        ];
    }
}
