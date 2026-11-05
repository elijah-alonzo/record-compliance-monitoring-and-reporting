<?php

declare(strict_types=1);

namespace App\Features\Reports\Requests;

use App\Features\Loads\Enums\Semester;
use App\Features\Reports\Enums\SubmissionState;
use App\Features\Reports\Enums\SubmissionTiming;
use Illuminate\Validation\Rule;

class LoadsReportRequest extends ReportRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'year' => ['nullable', 'string', 'max:20'],
            'semester' => ['nullable', Rule::enum(Semester::class)],
            'department' => ['nullable', 'integer', 'exists:departments,id'],
            'faculty' => ['nullable', 'integer', 'exists:faculties,id'],
            'status' => ['nullable', Rule::enum(SubmissionState::class)],
            'timing' => ['nullable', Rule::enum(SubmissionTiming::class)],
        ];
    }
}
