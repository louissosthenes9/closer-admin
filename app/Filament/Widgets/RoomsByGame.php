<?php

namespace App\Filament\Widgets;

use App\Models\Room;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Str;

class RoomsByGame extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Games played';

    protected ?string $description = 'Rooms by game type';

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $rows = Room::query()->selectRaw('game, count(*) as total')->groupBy('game')->orderByDesc('total')->pluck('total', 'game');

        return [
            'datasets' => [[
                'data' => $rows->values()->all(),
                'backgroundColor' => ['#f59e0b', '#38bdf8', '#a78bfa', '#34d399', '#fb7185'],
                'borderWidth' => 0,
            ]],
            'labels' => $rows->keys()->map(fn ($g) => Str::headline((string) $g))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'cutout' => '68%',
            'plugins' => ['legend' => ['position' => 'bottom']],
            'scales' => ['x' => ['display' => false], 'y' => ['display' => false]],
        ];
    }
}
