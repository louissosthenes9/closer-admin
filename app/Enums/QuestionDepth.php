<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum QuestionDepth: string implements HasColor, HasLabel
{
    case Light = 'light';
    case Medium = 'medium';
    case Deep = 'deep';

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Light => 'success',
            self::Medium => 'warning',
            self::Deep => 'danger',
        };
    }
}
