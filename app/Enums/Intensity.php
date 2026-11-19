<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Intensity: string implements HasLabel
{
    case Light = 'light';
    case Balanced = 'balanced';
    case Meaningful = 'meaningful';

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }
}
