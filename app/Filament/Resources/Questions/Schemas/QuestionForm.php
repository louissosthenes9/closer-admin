<?php

namespace App\Filament\Resources\Questions\Schemas;

use App\Enums\QuestionDepth;
use App\Models\QuestionSubcategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('text')->label('Question')->required()->maxLength(500)->rows(3)->columnSpanFull(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('subcategory_id', null))
                    ->createOptionForm([
                        TextInput::make('name')->required()->maxLength(80),
                        TextInput::make('slug')->required()->maxLength(48)->alphaDash()->unique('question_categories', 'slug'),
                    ]),
                Select::make('subcategory_id')
                    ->label('Subcategory')
                    ->options(fn (Get $get): array => QuestionSubcategory::query()
                        ->where('category_id', $get('category_id'))
                        ->orderBy('sort_order')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->disabled(fn (Get $get): bool => blank($get('category_id'))),
                Select::make('mode_id')->label('Mode')->relationship('mode', 'name')->searchable()->preload(),
                Select::make('depth')->options(QuestionDepth::class)->required()->default(QuestionDepth::Light),
                TextInput::make('min_age')->numeric()->minValue(0)->maxValue(99)->default(16)->required(),
                TextInput::make('language')->default('en')->required()->maxLength(8),
                TagsInput::make('tags')->default([])->columnSpanFull(),
                TextInput::make('key')
                    ->label('Catalog ID')
                    ->placeholder('Auto-generated')
                    ->maxLength(32)
                    ->unique(ignoreRecord: true)
                    ->dehydrateStateUsing(fn (?string $state) => filled($state) ? $state : null),
                Toggle::make('sensitive'),
                Toggle::make('active')->default(true),
            ]);
    }
}
