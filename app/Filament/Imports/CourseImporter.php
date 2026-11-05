<?php

namespace App\Filament\Imports;

use App\Features\Courses\Models\Course;
use App\Features\Departments\Models\Department;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Model;

class CourseImporter extends Importer
{
    protected static ?string $model = Course::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('department')
                ->label('Department Code or Name')
                ->requiredMappingForNewRecordsOnly(fn (Importer $importer): bool => blank($importer->getOptions()['department_id'] ?? null))
                ->relationship('department', resolveUsing: ['code', 'name'])
                ->rules(['required'])
                ->example('CS'),

            ImportColumn::make('code')
                ->label('Course Code')
                ->requiredMapping()
                ->rules(['required', 'max:50'])
                ->example('BSCS'),

            ImportColumn::make('name')
                ->label('Course Name')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('BS Computer Science'),

            ImportColumn::make('description')
                ->label('Description')
                ->rules(['nullable', 'max:65535'])
                ->ignoreBlankState()
                ->example('Four-year degree program.'),
        ];
    }

    public function resolveRecord(): ?Model
    {
        $code = $this->data['code'] ?? null;

        if (filled($code)) {
            return Course::firstOrNew(['code' => $code]);
        }

        $name = $this->data['name'] ?? null;
        $departmentId = $this->getOptions()['department_id'] ?? $this->resolveDepartmentId($this->data['department'] ?? null);

        if ($name && $departmentId) {
            return Course::firstOrNew([
                'department_id' => $departmentId,
                'name' => $name,
            ]);
        }

        return new Course;
    }

    protected function beforeFill(): void
    {
        $departmentId = $this->getOptions()['department_id'] ?? null;

        if ($departmentId) {
            $this->record->department_id = $departmentId;
        }
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

    public static function getCompletedNotificationBody(Import $import): string
    {
        return sprintf('Imported %d course record(s).', $import->successful_rows);
    }
}
