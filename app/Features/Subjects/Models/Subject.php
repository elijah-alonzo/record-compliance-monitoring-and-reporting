<?php

namespace App\Features\Subjects\Models;

use App\Features\ActivityLog\Concerns\LogsActivity;
use App\Features\Courses\Models\Course;
use App\Features\Loads\Models\Load;
use App\Features\Subjects\Enums\SubjectType;
use App\Features\Subjects\Policies\SubjectPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $subject_code
 * @property string $name
 * @property int $units
 * @property SubjectType $type
 * @property int|null $course_id
 * @property string|null $description
 */
#[UsePolicy(SubjectPolicy::class)]
class Subject extends Model
{
    use LogsActivity;

    protected $fillable = [
        'subject_code',
        'name',
        'units',
        'type',
        'course_id',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'type' => SubjectType::class,
        ];
    }

    /** @return BelongsTo<Course, $this> */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /** @return HasMany<Load, $this> */
    public function loads(): HasMany
    {
        return $this->hasMany(Load::class);
    }
}
