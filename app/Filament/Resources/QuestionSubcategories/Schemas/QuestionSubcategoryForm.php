<?php

namespace App\Filament\Resources\QuestionSubcategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;

class QuestionSubcategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')->relationship('category', 'name')->required()->preload()->searchable()->live(),
                TextInput::make('name')->required()->maxLength(80),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(48)
                    ->alphaDash()
                    ->unique(ignoreRecord: true, modifyRuleUsing: fn (Unique $rule, Get $get) => $rule->where('category_id', $get('category_id'))),
                TextInput::make('sort_order')->numeric()->default(0)->required(),
                Toggle::make('active')->default(true),
            ]);
    }
}
