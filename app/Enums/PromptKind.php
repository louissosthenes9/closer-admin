<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PromptKind: string implements HasLabel
{
    case SingleChoice = 'single_choice';
    case WouldYouRather = 'would_you_rather';
    case Ranking = 'ranking';
    case Challenge = 'challenge';

    public function getLabel(): string
    {
        return match ($this) {
            self::SingleChoice => 'Single choice',
            self::WouldYouRather => 'Would you rather',
            self::Ranking => 'Ranking',
            self::Challenge => 'Challenge',
        };
    }
}
