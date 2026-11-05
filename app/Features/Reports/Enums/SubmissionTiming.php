<?php

declare(strict_types=1);

namespace App\Features\Reports\Enums;

enum SubmissionTiming: string
{
    case OnTime = 'on_time';
    case Late = 'late';

    public function label(): string
    {
        return match ($this) {
            self::OnTime => 'On Time',
            self::Late => 'Late',
        };
    }
}
