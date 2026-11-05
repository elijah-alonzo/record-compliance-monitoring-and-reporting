<?php

namespace App\Features\ActivityLog\Concerns;

use App\Features\ActivityLog\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        static::created(function ($model): void {
            $model->writeActivityLog('created');
        });

        static::updated(function ($model): void {
            $changes = $model->getChanges();
            unset($changes['updated_at']);

            if ($changes === []) {
                return;
            }

            $model->writeActivityLog('updated');
        });

        static::deleted(function ($model): void {
            $model->writeActivityLog('deleted');
        });
    }

    protected function writeActivityLog(string $action): void
    {
        $label = $this->activityLogLabel();
        $actionLabel = match ($action) {
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            default => ucfirst($action),
        };
        $modelLabel = $this->activityLogModelLabel();
        $description = $label !== null && $label !== ''
            ? sprintf('%s %s: %s', $actionLabel, $modelLabel, $label)
            : sprintf('%s %s', $actionLabel, $modelLabel);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => $description,
        ]);
    }

    protected function activityLogLabel(): ?string
    {
        $model = class_basename($this);

        if ($model === 'Faculty' || $model === 'User') {
            $fullName = $this->full_name ?? null;

            if ($fullName) {
                return $fullName;
            }
        }

        if ($model === 'Section') {
            $code = $this->code ?? null;
            $yearLevel = $this->year_level?->value;

            if ($code && $yearLevel) {
                return sprintf('%s %s', $code, $yearLevel);
            }

            return $code;
        }

        if ($model === 'Subject') {
            $code = $this->subject_code ?? null;
            $name = $this->name ?? null;

            if ($code && $name) {
                return sprintf('%s - %s', $code, $name);
            }

            return $code ?? $name;
        }

        if ($model === 'Course') {
            return $this->name ?? null;
        }

        if ($model === 'Department') {
            $code = $this->code ?? null;
            $name = $this->name ?? null;

            if ($code && $name) {
                return sprintf('%s - %s', $code, $name);
            }

            return $code ?? $name;
        }

        if ($model === 'SubmissionDeadline') {
            $first = $this->first_semester_deadline;
            $second = $this->second_semester_deadline;
            $summer = $this->summer_semester_deadline;

            $parts = [];

            if ($first) {
                $parts[] = 'First Semester: '.$first->format('M d, Y');
            }

            if ($second) {
                $parts[] = 'Second Semester: '.$second->format('M d, Y');
            }

            if ($summer) {
                $parts[] = 'Summer: '.$summer->format('M d, Y');
            }

            return $parts !== [] ? implode(' | ', $parts) : null;
        }

        if ($model === 'Load') {
            $this->loadMissing(['faculties', 'subject', 'section']);

            $subject = $this->subject?->subject_code ?? $this->subject?->name;
            $section = $this->section?->code;
            $facultyNames = $this->faculties
                ->pluck('full_name')
                ->filter()
                ->implode(', ');

            $parts = array_filter([$subject, $section, $facultyNames]);

            if ($parts !== []) {
                return implode(' | ', $parts);
            }

            return $this->getKey() ? sprintf('Load #%s', $this->getKey()) : null;
        }

        foreach (['name', 'title', 'code', 'subject_code', 'email'] as $key) {
            $value = $this->{$key} ?? null;

            if ($value !== null && $value !== '') {
                return $value;
            }
        }

        return $this->getKey() ? sprintf('%s #%s', $model, $this->getKey()) : null;
    }

    protected function activityLogModelLabel(): string
    {
        $model = class_basename($this);
        $overrides = [
            'Load' => 'Teaching Load',
            'SubmissionDeadline' => 'Submission Deadline',
            'User' => 'Registrar',
        ];

        if (isset($overrides[$model])) {
            return $overrides[$model];
        }

        return trim((string) preg_replace('/(?<!^)([A-Z])/', ' $1', $model));
    }

    protected function activityLogSanitizedAttributes(array $attributes): array
    {
        if (method_exists($this, 'getHidden')) {
            foreach ($this->getHidden() as $hiddenKey) {
                unset($attributes[$hiddenKey]);
            }
        }

        unset($attributes['created_at'], $attributes['updated_at']);

        return $attributes;
    }
}
