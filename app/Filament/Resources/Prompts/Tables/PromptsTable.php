<?php

namespace App\Filament\Resources\Prompts\Tables;

use App\Enums\Intensity;
use App\Enums\PromptKind;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PromptsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('key')->searchable()->toggleable(),
                TextColumn::make('prompt')->searchable()->limit(80)->wrap(),
                TextColumn::make('kind')->badge(),
                TextColumn::make('intensity')->badge(),
                ToggleColumn::make('published'),
            ])
            ->filters([
                SelectFilter::make('kind')->options(PromptKind::class),
                SelectFilter::make('intensity')->options(Intensity::class),
                TernaryFilter::make('published'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
