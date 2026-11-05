<?php

namespace App\Features\Courses\Models;

use App\Features\ActivityLog\Concerns\LogsActivity;
use App\Features\Courses\Policies\CoursePolicy;
use App\Features\Departments\Models\Department;
use App\Features\Faculties\Models\Faculty;
use App\Features\Sections\Models\Section;
use App\Features\Subjects\Models\Subject;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $department_id
 * @property string $code
 * @property string $name
 * @property string|null $description
 */
#[UsePolicy(CoursePolicy::class)]
class Course extends Model
{
    use LogsActivity;

    protected $fillable = [
        'department_id',
        'code',
        'name',
        'description',
    ];

    /** @return BelongsTo<Department, $this> */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /** @return HasMany<Section, $this> */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    /** @return HasMany<Subject, $this> */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    /** @return BelongsToMany<Faculty, $this> */
    public function faculties(): BelongsToMany
    {
        return $this->belongsToMany(Faculty::class)->withTimestamps();
    }
}
