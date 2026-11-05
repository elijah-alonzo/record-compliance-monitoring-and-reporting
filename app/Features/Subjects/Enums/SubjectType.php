<?php

declare(strict_types=1);

namespace App\Features\Subjects\Enums;

use Filament\Support\Contracts\HasLabel;

enum SubjectType: string implements HasLabel
{
    case Major = 'Major';
    case Minor = 'Minor';

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
