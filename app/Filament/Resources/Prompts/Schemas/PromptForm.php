<?php

namespace App\Filament\Resources\Prompts\Schemas;

use App\Enums\Intensity;
use App\Enums\PromptKind;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PromptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('prompt')->required()->rows(3)->columnSpanFull(),
                Select::make('kind')->options(PromptKind::class)->required()->live(),
                Select::make('intensity')->options(Intensity::class)->required()->default(Intensity::Light),
                Repeater::make('options')
                    ->simple(TextInput::make('option')->required()->maxLength(120))
                    ->minItems(2)
                    ->maxItems(fn ($get): int => $get('kind') === PromptKind::WouldYouRather->value ? 2 : 4)
                    ->default(['', ''])
                    ->helperText('2–4 options; exactly 2 for "Would you rather".')
                    ->columnSpanFull(),
                Textarea::make('follow_up')->rows(2)->columnSpanFull(),
                CheckboxList::make('modes')->options(['date' => 'Date', 'friends' => 'Friends'])->default(['date', 'friends'])->required()->columns(2),
                TagsInput::make('tags')->default([])->helperText('"spicy" restricts the prompt to Date mode with comfort opt-in.'),
                TextInput::make('min_players')->numeric()->minValue(2)->default(2)->required(),
                TextInput::make('max_players')->numeric()->minValue(2)->default(6)->required(),
                Toggle::make('anonymous'),
                Toggle::make('published')->default(true),
                TextInput::make('key')
                    ->placeholder('Auto-generated')
                    ->maxLength(32)
                    ->unique(ignoreRecord: true)
                    ->dehydrateStateUsing(fn (?string $state) => filled($state) ? $state : null),
            ]);
    }
}
