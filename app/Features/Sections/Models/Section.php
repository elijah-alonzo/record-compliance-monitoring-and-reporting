<?php

namespace App\Features\Sections\Models;

use App\Features\ActivityLog\Concerns\LogsActivity;
use App\Features\Courses\Models\Course;
use App\Features\Departments\Models\Department;
use App\Features\Loads\Models\Load;
use App\Features\Sections\Enums\YearLevel;
use App\Features\Sections\Policies\SectionPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $code
 * @property YearLevel $year_level
 * @property int $department_id
 * @property int|null $course_id
 */
#[UsePolicy(SectionPolicy::class)]
class Section extends Model
{
    use LogsActivity;

    protected $fillable = [
        'code',
        'year_level',
        'department_id',
        'course_id',
    ];

    protected function casts(): array
    {
        return [
            'year_level' => YearLevel::class,
        ];
    }

    /** @return BelongsTo<Department, $this> */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
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
