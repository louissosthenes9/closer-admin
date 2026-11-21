<?php

namespace App\Filament\Widgets;

use App\Models\Room;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestRooms extends TableWidget
{
    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Latest rooms';

    public function table(Table $table): Table
    {
        return $table
            ->query(Room::query()->withCount('participants')->latest('id'))
            ->paginated(false)
            ->defaultPaginationPageOption(6)
            ->columns([
                TextColumn::make('code')->badge(),
                TextColumn::make('game')->badge()->formatStateUsing(fn ($state) => str((string) $state)->headline()),
                TextColumn::make('phase')->badge()->color(fn ($state) => match ($state) {
                    'playing' => 'success',
                    'lobby' => 'warning',
                    default => 'gray',
                }),
                TextColumn::make('participants_count')->label('Players'),
                TextColumn::make('created_at')->since()->label('Created'),
            ])
            ->modifyQueryUsing(fn ($query) => $query->limit(6));
    }
}
