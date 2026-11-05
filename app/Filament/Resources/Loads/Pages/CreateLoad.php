<?php

namespace App\Filament\Resources\Loads\Pages;

use App\Features\Faculties\Models\Faculty;
use App\Features\Loads\Enums\Semester;
use App\Features\Loads\Mail\TeachingLoadAssignedMail;
use App\Features\SubmissionDeadlines\Models\SubmissionDeadline;
use App\Filament\Resources\Loads\LoadResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Mail;

class CreateLoad extends CreateRecord
{
    protected static string $resource = LoadResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $isSubmitted = (bool) ($data['status'] ?? false);
        $semester = $data['semester'] ?? null;

        $data['submitted_at'] = $isSubmitted ? now() : null;
        $record = SubmissionDeadline::query()->first();
        $deadline = match ($semester) {
            Semester::First->value => $record?->first_semester_deadline,
            Semester::Second->value => $record?->second_semester_deadline,
            Semester::Summer->value => $record?->summer_semester_deadline,
            default => null,
        };

        if (! $deadline) {
            Notification::make()
                ->title('Submission deadline required')
                ->body('Set the submission deadline for the selected semester before creating teaching loads.')
                ->danger()
                ->send();

            throw new Halt;
        }

        $data['due_date'] = $deadline;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $record->loadMissing(['faculties', 'subject', 'section']);

        $recipients = $record->faculties
            ->filter(fn (Faculty $faculty) => (string) ($faculty->email ?? '') !== '');

        if ($recipients->isEmpty()) {
            Notification::make()
                ->title('Faculty emails are missing')
                ->body('Add email addresses for the assigned faculty members before sending notifications.')
                ->danger()
                ->send();

            return;
        }

        foreach ($recipients as $faculty) {
            Mail::to($faculty->email)->send(new TeachingLoadAssignedMail($record, $faculty->full_name));
        }
    }
}
