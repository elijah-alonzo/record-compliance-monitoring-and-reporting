<?php

namespace App\Features\Faculties\Models;

use App\Features\ActivityLog\Concerns\LogsActivity;
use App\Features\Courses\Models\Course;
use App\Features\Departments\Models\Department;
use App\Features\Faculties\Enums\EmploymentType;
use App\Features\Faculties\Policies\FacultyPolicy;
use App\Features\Loads\Models\Load;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property EmploymentType $employment_type
 * @property int $department_id
 * @property string|null $email
 * @property string|null $phone
 * @property-read string $full_name
 */
#[UsePolicy(FacultyPolicy::class)]
class Faculty extends Model
{
    use LogsActivity;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'employment_type',
        'department_id',
        'email',
        'phone',
    ];

    protected $appends = [
        'full_name',
    ];

    protected function casts(): array
    {
        return [
            'employment_type' => EmploymentType::class,
        ];
    }

    /** @return BelongsTo<Department, $this> */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /** @return BelongsToMany<Load, $this> */
    public function loads(): BelongsToMany
    {
        return $this->belongsToMany(Load::class)->withTimestamps();
    }

    /** @return BelongsToMany<Course, $this> */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class)->withTimestamps();
    }

    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ], static fn ($part) => $part !== null && $part !== '');

        return trim(implode(' ', $parts));
    }
}
