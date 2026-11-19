<?php

namespace App\Filament\Resources\QuestionCategories\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class QuestionCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(80)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create'
                        ? $set('slug', Str::snake((string) $state))
                        : null),
                TextInput::make('slug')->required()->maxLength(48)->alphaDash()->unique(ignoreRecord: true),
                Textarea::make('description')->columnSpanFull(),
                TextInput::make('sort_order')->numeric()->default(0)->required(),
                Toggle::make('active')->default(true),
                Repeater::make('subcategories')
                    ->relationship()
                    ->columnSpanFull()
                    ->columns(4)
                    ->orderColumn('sort_order')
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                    ->addActionLabel('Add subcategory')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(80)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::snake((string) $state))),
                        TextInput::make('slug')->required()->maxLength(48)->alphaDash(),
                        Toggle::make('active')->default(true)->inline(false),
                    ]),
            ]);
    }
}
