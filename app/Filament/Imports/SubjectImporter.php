<?php

namespace App\Filament\Imports;

use App\Features\Subjects\Enums\SubjectType;
use App\Features\Subjects\Models\Subject;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class SubjectImporter extends Importer
{
    protected static ?string $model = Subject::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('subject_code')
                ->label('Subject Code')
                ->requiredMapping()
                ->rules(['required', 'max:50'])
                ->example('MATH101'),

            ImportColumn::make('name')
                ->label('Subject Name')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Calculus'),

            ImportColumn::make('units')
                ->label('Units')
                ->requiredMapping()
                ->integer()
                ->rules(['required', 'integer', 'min:1', 'max:6'])
                ->example('3'),

            ImportColumn::make('type')
                ->label('Type')
                ->requiredMapping()
                ->rules([
                    'required',
                    Rule::in(SubjectType::values()),
                ])
                ->example('Major'),

            ImportColumn::make('course')
                ->label('Course (Major only)')
                ->relationship('course', resolveUsing: ['code', 'name'])
                ->rules(['nullable', 'required_if:type,Major'])
                ->example('BSIT'),

            ImportColumn::make('description')
                ->label('Description')
                ->rules(['nullable', 'max:65535'])
                ->ignoreBlankState()
                ->example('Differential and integral calculus.'),
        ];
    }

    public function resolveRecord(): ?Model
    {
        $code = $this->data['subject_code'] ?? null;

        if (filled($code)) {
            return Subject::firstOrNew(['subject_code' => $code]);
        }

        return new Subject;
    }

    protected function beforeSave(): void
    {
        if ($this->record->type !== SubjectType::Major) {
            $this->record->course_id = null;
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        return sprintf('Imported %d subject record(s).', $import->successful_rows);
    }
}
