<?php

namespace App\Filament\Imports;

use App\Features\Courses\Models\Course;
use App\Features\Departments\Models\Department;
use App\Features\Sections\Enums\YearLevel;
use App\Features\Sections\Models\Section;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class SectionImporter extends Importer
{
    protected static ?string $model = Section::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->label('Section Code')
                ->requiredMapping()
                ->rules(['required', 'max:10'])
                ->example('BEED1'),

            ImportColumn::make('year_level')
                ->label('Year Level')
                ->requiredMapping()
                ->rules([
                    'required',
                    Rule::in(YearLevel::values()),
                ])
                ->example('1st Year'),

            ImportColumn::make('department')
                ->label('Department Code or Name')
                ->requiredMapping()
                ->relationship('department', resolveUsing: ['code', 'name'])
                ->rules(['required'])
                ->example('CTE'),

            ImportColumn::make('course')
                ->label('Course Code or Name')
                ->requiredMapping()
                ->relationship('course', resolveUsing: function (string $state, array $data): ?Course {
                    $departmentValue = $data['department'] ?? null;
                    $departmentId = null;

                    if (filled($departmentValue)) {
                        $departmentId = Department::query()
                            ->where('code', $departmentValue)
                            ->orWhere('name', $departmentValue)
                            ->value('id');
                    }

                    $query = Course::query()->where(fn ($q) => $q->where('code', $state)->orWhere('name', $state));

                    if ($departmentId) {
                        $query->where('department_id', $departmentId);
                    }

                    return $query->first();
                })
                ->rules(['required'])
                ->example('Bachelor of Elementary Education'),
        ];
    }

    public function resolveRecord(): ?Model
    {
        $code = $this->data['code'] ?? null;
        $yearLevel = $this->data['year_level'] ?? null;
        $departmentId = $this->resolveDepartmentId($this->data['department'] ?? null);
        $courseId = $this->resolveCourseId($this->data['course'] ?? null, $departmentId);

        if ($code && $yearLevel && $courseId) {
            return Section::firstOrNew([
                'code' => $code,
                'year_level' => $yearLevel,
                'course_id' => $courseId,
            ]);
        }

        return new Section;
    }

    protected function resolveDepartmentId(?string $value): ?int
    {
        if (blank($value)) {
            return null;
        }

        return Department::query()
            ->where('code', $value)
            ->orWhere('name', $value)
            ->value('id');
    }

    protected function resolveCourseId(?string $value, ?int $departmentId): ?int
    {
        if (blank($value)) {
            return null;
        }

        $query = Course::query()->where(fn ($q) => $q->where('code', $value)->orWhere('name', $value));

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        return $query->value('id');
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        return sprintf('Imported %d section record(s).', $import->successful_rows);
    }
}
