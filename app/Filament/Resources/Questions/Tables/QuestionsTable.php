<?php

namespace App\Filament\Resources\Questions\Tables;

use App\Enums\QuestionDepth;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('key')->label('ID')->searchable()->sortable()->toggleable(),
                TextColumn::make('text')->label('Question')->searchable()->limit(80)->wrap(),
                TextColumn::make('category.name')->sortable(),
                TextColumn::make('subcategory.name')->toggleable(),
                TextColumn::make('mode.name')->toggleable(),
                TextColumn::make('depth')->badge(),
                TextColumn::make('min_age')->label('Age')->toggleable(isToggledHiddenByDefault: true),
                ToggleColumn::make('sensitive')->toggleable(isToggledHiddenByDefault: true),
                ToggleColumn::make('active'),
            ])
            ->filters([
                SelectFilter::make('category')->relationship('category', 'name'),
                SelectFilter::make('mode')->relationship('mode', 'name'),
                SelectFilter::make('depth')->options(QuestionDepth::class),
                TernaryFilter::make('active'),
                TernaryFilter::make('sensitive'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('activate')
                        ->action(fn (Collection $records) => $records->each->update(['active' => true]))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('deactivate')
                        ->action(fn (Collection $records) => $records->each->update(['active' => false]))
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
