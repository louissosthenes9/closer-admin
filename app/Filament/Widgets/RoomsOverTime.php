<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\DailySeries;
use App\Models\Room;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RoomsOverTime extends ChartWidget
{
    use DailySeries;

    protected static ?int $sort = 2;

    protected ?string $heading = 'Rooms created';

    protected ?string $description = 'Daily rooms over time';

    protected int|string|array $columnSpan = ['default' => 'full', 'lg' => 2];

    protected ?string $maxHeight = '280px';

    public ?string $filter = '30';

    protected function getFilters(): ?array
    {
        return ['7' => 'Last 7 days', '30' => 'Last 30 days', '90' => 'Last 90 days'];
    }

    protected function getData(): array
    {
        $series = $this->dailyCounts(Room::query(), (int) $this->filter);

        return [
            'datasets' => [[
                'label' => 'Rooms',
                'data' => array_values($series),
                'fill' => 'start',
                'tension' => 0.35,
                'borderColor' => '#f59e0b',
                'backgroundColor' => 'rgba(245, 158, 11, 0.15)',
                'pointRadius' => 0,
                'pointHoverRadius' => 4,
            ]],
            'labels' => array_map(fn ($d) => Carbon::parse($d)->format('M j'), array_keys($series)),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => [
                'y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0], 'grid' => ['display' => false]],
                'x' => ['grid' => ['display' => false], 'ticks' => ['maxTicksLimit' => 8]],
            ],
        ];
    }
}
