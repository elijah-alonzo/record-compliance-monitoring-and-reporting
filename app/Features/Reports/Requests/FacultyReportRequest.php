<?php

declare(strict_types=1);

namespace App\Features\Reports\Requests;

use App\Features\Loads\Enums\Semester;
use Illuminate\Validation\Rule;

class FacultyReportRequest extends ReportRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'year' => ['nullable', 'string', 'max:20'],
            'semester' => ['nullable', Rule::enum(Semester::class)],
            'faculty' => ['required', 'integer', 'exists:faculties,id'],
        ];
    }
}
