<?php

namespace App\Filament\Imports;

use App\Features\Departments\Models\Department;
use App\Features\Faculties\Enums\EmploymentType;
use App\Features\Faculties\Models\Faculty;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class FacultyImporter extends Importer
{
    protected static ?string $model = Faculty::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('first_name')
                ->label('First Name')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Maria'),

            ImportColumn::make('middle_name')
                ->label('Middle Name')
                ->rules(['nullable', 'max:255'])
                ->ignoreBlankState()
                ->example('C.'),

            ImportColumn::make('last_name')
                ->label('Last Name')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Dela Cruz'),

            ImportColumn::make('employment_type')
                ->label('Employment Type')
                ->requiredMapping()
                ->rules([
                    'required',
                    Rule::in(EmploymentType::values()),
                ])
                ->example('Full-Time Faculty'),

            ImportColumn::make('department')
                ->label('Department Code or Name')
                ->requiredMapping()
                ->relationship('department', resolveUsing: ['code', 'name'])
                ->rules(['required'])
                ->example('CS'),

            ImportColumn::make('courses')
                ->label('Courses (comma-separated codes or names)')
                ->multiple(',')
                ->relationship(resolveUsing: ['code', 'name'])
                ->ignoreBlankState()
                // Sync instead of Filament's default attach, so re-importing a faculty doesn't hit the pivot unique key.
                ->saveRelationshipsUsing(fn (array $state, ImportColumn $column, Faculty $record) => $record->courses()->sync(
                    $column->resolveRelatedRecords($state)?->modelKeys() ?? [],
                ))
                ->example('BSIT,BSCS'),

            ImportColumn::make('email')
                ->label('Email')
                ->rules(['nullable', 'email', 'max:255'])
                ->example('maria@school.edu'),

            ImportColumn::make('phone')
                ->label('Phone')
                ->rules(['nullable', 'max:255'])
                ->example('09171234567'),
        ];
    }

    public function resolveRecord(): ?Model
    {
        $email = $this->data['email'] ?? null;

        if (filled($email)) {
            return Faculty::firstOrNew(['email' => $email]);
        }

        $departmentId = $this->resolveDepartmentId($this->data['department'] ?? null);
        $firstName = $this->data['first_name'] ?? null;
        $lastName = $this->data['last_name'] ?? null;

        if ($departmentId && $firstName && $lastName) {
            return Faculty::firstOrNew([
                'department_id' => $departmentId,
                'first_name' => $firstName,
                'last_name' => $lastName,
            ]);
        }

        return new Faculty;
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
        return sprintf('Imported %d faculty record(s).', $import->successful_rows);
    }
}
