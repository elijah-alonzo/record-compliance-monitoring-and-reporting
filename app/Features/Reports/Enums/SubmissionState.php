<?php

declare(strict_types=1);

namespace App\Features\Reports\Enums;

enum SubmissionState: string
{
    case Submitted = 'submitted';
    case NotSubmitted = 'not_submitted';

    public function label(): string
    {
        return match ($this) {
            self::Submitted => 'Submitted',
            self::NotSubmitted => 'Not Submitted',
        };
    }
}
