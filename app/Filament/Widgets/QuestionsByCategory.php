<?php

namespace App\Filament\Widgets;

use App\Models\QuestionCategory;
use Filament\Widgets\ChartWidget;

class QuestionsByCategory extends ChartWidget
{
    protected static ?int $sort = 4;

    protected ?string $heading = 'Question bank';

    protected ?string $description = 'Active questions per category';

    protected int|string|array $columnSpan = ['default' => 'full', 'lg' => 2];

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $cats = QuestionCategory::query()
            ->withCount(['questions' => fn ($q) => $q->where('active', true)])
            ->orderByDesc('questions_count')
            ->limit(10)
            ->get();

        return [
            'datasets' => [[
                'label' => 'Questions',
                'data' => $cats->pluck('questions_count')->all(),
                'backgroundColor' => '#f59e0b',
                'borderRadius' => 6,
                'maxBarThickness' => 28,
            ]],
            'labels' => $cats->pluck('name')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => ['legend' => ['display' => false]],
            'scales' => [
                'x' => ['beginAtZero' => true, 'ticks' => ['precision' => 0], 'grid' => ['display' => false]],
                'y' => ['grid' => ['display' => false]],
            ],
        ];
    }
}
