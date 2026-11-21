<?php

namespace App\Filament\Widgets\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait DailySeries
{
    /**
     * Rows created per day for the last $days days (oldest first), zero-filled.
     *
     * @return array<string, int> keyed by Y-m-d
     */
    protected function dailyCounts(Builder $query, int $days, string $column = 'created_at'): array
    {
        $start = Carbon::today()->subDays($days - 1);

        $counts = $query
            ->where($column, '>=', $start)
            ->get([$column])
            ->countBy(fn ($row) => Carbon::parse($row->{$column})->toDateString());

        $series = [];
        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i)->toDateString();
            $series[$day] = (int) ($counts[$day] ?? 0);
        }

        return $series;
    }
}
