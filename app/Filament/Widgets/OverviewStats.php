<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\DailySeries;
use App\Models\Question;
use App\Models\Room;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewStats extends StatsOverviewWidget
{
    use DailySeries;

    protected static ?int $sort = 1;

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $rooms = array_values($this->dailyCounts(Room::query(), 14));
        $users = array_values($this->dailyCounts(User::query(), 14));

        $roomsWeek = array_sum(array_slice($rooms, -7));
        $roomsPrev = array_sum(array_slice($rooms, 0, 7));
        $delta = $roomsPrev > 0 ? round((($roomsWeek - $roomsPrev) / $roomsPrev) * 100) : null;

        return [
            Stat::make('Open rooms', Room::where('expires_at', '>', now())->count())
                ->description('Playing right now')
                ->descriptionIcon('heroicon-m-signal')
                ->color('success'),
            Stat::make('Rooms this week', $roomsWeek)
                ->description($delta === null ? 'No prior week' : (abs($delta).'% '.($delta >= 0 ? 'up' : 'down').' vs last week'))
                ->descriptionIcon($delta !== null && $delta < 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-arrow-trending-up')
                ->chart($rooms)
                ->color($delta !== null && $delta < 0 ? 'danger' : 'primary'),
            Stat::make('Players', User::count())
                ->description(array_sum(array_slice($users, -7)).' new this week')
                ->descriptionIcon('heroicon-m-user-plus')
                ->chart($users)
                ->color('info'),
            Stat::make('Active questions', Question::where('active', true)->count())
                ->description(Question::where('active', false)->count().' inactive')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('gray'),
        ];
    }
}
