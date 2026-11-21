<?php

namespace App\Filament\Widgets;

use App\Enums\QuestionDepth;
use App\Models\Question;
use Filament\Widgets\ChartWidget;

class QuestionsByDepth extends ChartWidget
{
    protected static ?int $sort = 5;

    protected ?string $heading = 'Question depth';

    protected ?string $description = 'Light to deep mix';

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $counts = Question::query()->where('active', true)->selectRaw('depth, count(*) as total')->groupBy('depth')->pluck('total', 'depth');
        $colors = ['light' => '#34d399', 'medium' => '#f59e0b', 'deep' => '#fb7185'];

        $labels = [];
        $data = [];
        $bg = [];
        foreach (QuestionDepth::cases() as $depth) {
            $labels[] = $depth->getLabel();
            $data[] = (int) ($counts[$depth->value] ?? 0);
            $bg[] = $colors[$depth->value] ?? '#94a3b8';
        }

        return ['datasets' => [['data' => $data, 'backgroundColor' => $bg, 'borderWidth' => 0]], 'labels' => $labels];
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
