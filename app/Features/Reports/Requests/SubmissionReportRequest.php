<?php

declare(strict_types=1);

namespace App\Features\Reports\Requests;

use App\Features\Loads\Enums\Semester;
use Illuminate\Validation\Rule;

class SubmissionReportRequest extends ReportRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'year' => ['required', 'string', 'max:20'],
            'semester' => ['required', Rule::enum(Semester::class)],
            'department' => ['nullable', 'integer', 'exists:departments,id'],
            'faculty' => ['nullable', 'integer', 'exists:faculties,id'],
        ];
    }
}
