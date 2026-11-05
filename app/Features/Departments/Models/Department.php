<?php

namespace App\Features\Departments\Models;

use App\Features\ActivityLog\Concerns\LogsActivity;
use App\Features\Courses\Models\Course;
use App\Features\Departments\Policies\DepartmentPolicy;
use App\Features\Faculties\Models\Faculty;
use App\Features\Sections\Models\Section;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $color
 */
#[UsePolicy(DepartmentPolicy::class)]
class Department extends Model
{
    use LogsActivity;

    protected $fillable = [
        'code',
        'name',
        'color',
    ];

    /** @return HasMany<Faculty, $this> */
    public function faculties(): HasMany
    {
        return $this->hasMany(Faculty::class);
    }

    /** @return HasMany<Course, $this> */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /** @return HasMany<Section, $this> */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }
}
