<?php

namespace App\Features\Loads\Models;

use App\Features\ActivityLog\Concerns\LogsActivity;
use App\Features\Faculties\Models\Faculty;
use App\Features\Loads\Enums\Semester;
use App\Features\Loads\Policies\LoadPolicy;
use App\Features\Sections\Models\Section;
use App\Features\Subjects\Models\Subject;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $subject_id
 * @property int $section_id
 * @property string $year
 * @property Semester $semester
 * @property Carbon|null $due_date
 * @property string|null $schedule
 * @property bool $status
 * @property Carbon|null $submitted_at
 * @property Carbon|null $week_before_notified_at
 * @property Carbon|null $overdue_notified_at
 * @property string|null $submission_status Set by the report actions
 */
#[UsePolicy(LoadPolicy::class)]
class Load extends Model
{
    use LogsActivity;

    protected $fillable = [
        'subject_id',
        'section_id',
        'year',
        'semester',
        'due_date',
        'schedule',
        'status',
        'submitted_at',
        'week_before_notified_at',
        'overdue_notified_at',
    ];

    protected function casts(): array
    {
        return [
            'semester' => Semester::class,
            'due_date' => 'date',
            'status' => 'boolean',
            'submitted_at' => 'datetime',
            'week_before_notified_at' => 'datetime',
            'overdue_notified_at' => 'datetime',
        ];
    }

    /** @return BelongsToMany<Faculty, $this> */
    public function faculties(): BelongsToMany
    {
        return $this->belongsToMany(Faculty::class)->withTimestamps();
    }

    /** @return BelongsTo<Subject, $this> */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /** @return BelongsTo<Section, $this> */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /** @param Builder<Load> $query */
    public function scopeForPeriod(Builder $query, ?string $year, ?string $semester): void
    {
        $query
            ->when($year, fn (Builder $builder) => $builder->where('year', $year))
            ->when($semester, fn (Builder $builder) => $builder->where('semester', $semester));
    }

    /** @param Builder<Load> $query */
    public function scopeForDepartment(Builder $query, ?int $departmentId): void
    {
        $query->when($departmentId, fn (Builder $builder) => $builder->whereHas(
            'faculties',
            fn (Builder $faculties) => $faculties->where('department_id', $departmentId),
        ));
    }

    /** @param Builder<Load> $query */
    public function scopeForFaculty(Builder $query, ?int $facultyId): void
    {
        $query->when($facultyId, fn (Builder $builder) => $builder->whereHas(
            'faculties',
            fn (Builder $faculties) => $faculties->whereKey($facultyId),
        ));
    }

    /** @param Builder<Load> $query */
    public function scopeSubmitted(Builder $query): void
    {
        $query->where('status', true)->whereNotNull('submitted_at');
    }

    /** @param Builder<Load> $query */
    public function scopeNotSubmitted(Builder $query): void
    {
        $query->where(function (Builder $builder): void {
            $builder->where('status', false)->orWhereNull('submitted_at');
        });
    }

    /** @param Builder<Load> $query */
    public function scopeSubmittedOnTime(Builder $query): void
    {
        $query->submitted()->whereNotNull('due_date')->whereRaw('DATE(submitted_at) <= due_date');
    }

    /** @param Builder<Load> $query */
    public function scopeSubmittedLate(Builder $query): void
    {
        $query->submitted()->whereNotNull('due_date')->whereRaw('DATE(submitted_at) > due_date');
    }

    /** @param Builder<Load> $query */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('semester')->orderBy('year')->orderBy('subject_id')->orderBy('section_id');
    }

    public function submissionStatus(): string
    {
        if (! $this->status || ! $this->submitted_at) {
            return 'Pending';
        }

        if (! $this->due_date) {
            return 'Submitted';
        }

        return $this->submitted_at->lte($this->due_date->copy()->endOfDay()) ? 'On Time' : 'Late';
    }
}
