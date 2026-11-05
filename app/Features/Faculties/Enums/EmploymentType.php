<?php

declare(strict_types=1);

namespace App\Features\Faculties\Enums;

use Filament\Support\Contracts\HasLabel;

enum EmploymentType: string implements HasLabel
{
    case Shared = 'Shared Faculty';
    case FullTime = 'Full-Time Faculty';
    case PartTime = 'Part-Time Faculty';

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
