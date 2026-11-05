<?php

declare(strict_types=1);

namespace App\Features\Reports\Actions;

use App\Features\Departments\Models\Department;
use App\Features\Faculties\Models\Faculty;

final class ResolveReportLabel
{
    public function department(?int $departmentId): string
    {
        if (! $departmentId) {
            return 'All Departments';
        }

        return Department::query()->whereKey($departmentId)->value('name') ?? 'All Departments';
    }

    public function faculty(?int $facultyId): string
    {
        if (! $facultyId) {
            return 'All Faculty';
        }

        return Faculty::query()->find($facultyId)->full_name ?? 'Unknown Faculty';
    }
}
