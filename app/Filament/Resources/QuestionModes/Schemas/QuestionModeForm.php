<?php

namespace App\Filament\Resources\QuestionModes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QuestionModeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required()->maxLength(80),
                TextInput::make('slug')->required()->maxLength(48)->alphaDash()->unique(ignoreRecord: true),
                Textarea::make('description')->columnSpanFull(),
                TextInput::make('suggested_session_size')->numeric()->minValue(1)->default(10)->required(),
                Select::make('progression')
                    ->options(['shuffle' => 'Shuffle', 'light_to_deep' => 'Light to deep'])
                    ->default('shuffle')
                    ->required(),
                Toggle::make('allow_skip')->default(true),
                Toggle::make('active')->default(true),
            ]);
    }
}
