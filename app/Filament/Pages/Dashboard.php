<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected ?string $subheading = 'How Closer is being played and what is in the question bank.';

    public function getColumns(): int|array
    {
        return ['default' => 1, 'lg' => 3];
    }
}
