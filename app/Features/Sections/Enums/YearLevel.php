<?php

declare(strict_types=1);

namespace App\Features\Sections\Enums;

use Filament\Support\Contracts\HasLabel;

enum YearLevel: string implements HasLabel
{
    case First = '1st Year';
    case Second = '2nd Year';
    case Third = '3rd Year';
    case Fourth = '4th Year';
    case Fifth = '5th Year';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): string
    {
        return $this->value;
    }
}
