<?php

namespace App\Filament\Resources\Rooms\Tables;

use App\Models\Room;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class RoomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('code')->searchable()->badge(),
                TextColumn::make('game')->badge(),
                TextColumn::make('mode')->badge(),
                TextColumn::make('phase')->badge(),
                TextColumn::make('participants_count')->counts('participants')->label('Players')->sortable(),
                TextColumn::make('rounds'),
                IconColumn::make('open')->boolean()->state(fn (Room $record): bool => $record->isOpen()),
                TextColumn::make('created_at')->dateTime()->sortable(),
                TextColumn::make('expires_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('phase')->options(['lobby' => 'Lobby', 'playing' => 'Playing', 'finished' => 'Finished']),
                SelectFilter::make('game')->options(['conversation' => 'Conversation', 'blind_reveal' => 'Blind reveal', 'guess_me' => 'Guess me']),
                TernaryFilter::make('open')
                    ->queries(
                        true: fn ($query) => $query->where('expires_at', '>', now()),
                        false: fn ($query) => $query->where('expires_at', '<=', now()),
                    ),
            ])
            ->recordActions([
                Action::make('close')
                    ->label('Close room')
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Room $record): bool => $record->isOpen())
                    ->action(fn (Room $record) => $record->forceFill(['expires_at' => now(), 'active_code' => null])->save()),
            ]);
    }
}
