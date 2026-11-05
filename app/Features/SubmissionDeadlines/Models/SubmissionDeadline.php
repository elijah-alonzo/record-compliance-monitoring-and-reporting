<?php

namespace App\Features\SubmissionDeadlines\Models;

use App\Features\ActivityLog\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Carbon|null $first_semester_deadline
 * @property Carbon|null $second_semester_deadline
 * @property Carbon|null $summer_semester_deadline
 */
class SubmissionDeadline extends Model
{
    use LogsActivity;

    protected $fillable = [
        'first_semester_deadline',
        'second_semester_deadline',
        'summer_semester_deadline',
    ];

    protected function casts(): array
    {
        return [
            'first_semester_deadline' => 'date',
            'second_semester_deadline' => 'date',
            'summer_semester_deadline' => 'date',
        ];
    }
}
