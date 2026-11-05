<?php

declare(strict_types=1);

namespace App\Features\Loads\Enums;

use Filament\Support\Contracts\HasLabel;

enum Semester: string implements HasLabel
{
    case First = '1st Semester';
    case Second = '2nd Semester';
    case Summer = 'Summer';

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
