<?php

declare(strict_types=1);

namespace App\Features\Loads\Actions;

use App\Features\Faculties\Models\Faculty;
use App\Features\Loads\Mail\TeachingLoadDeadlineReminderMail;
use App\Features\Loads\Mail\TeachingLoadOverdueMail;
use App\Features\Loads\Models\Load;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

final class SendLoadNotifications
{
    /**
     * Email assigned faculty about unsubmitted loads due in a week or already overdue.
     *
     * @return array{weekBefore: int, overdue: int}
     */
    public function __invoke(): array
    {
        $weekBefore = $this->notify(
            Load::query()
                ->where('status', false)
                ->whereDate('due_date', '=', now()->addDays(7)->toDateString())
                ->whereNull('week_before_notified_at'),
            'week_before_notified_at',
            fn (Load $load, Faculty $faculty) => new TeachingLoadDeadlineReminderMail($load, $faculty->full_name),
        );

        $overdue = $this->notify(
            Load::query()
                ->where('status', false)
                ->whereDate('due_date', '<', now()->toDateString())
                ->whereNull('overdue_notified_at'),
            'overdue_notified_at',
            fn (Load $load, Faculty $faculty) => new TeachingLoadOverdueMail($load, $faculty->full_name),
        );

        return ['weekBefore' => $weekBefore, 'overdue' => $overdue];
    }

    /**
     * @param  Builder<Load>  $loads
     * @param  callable(Load, Faculty): object  $mailable
     */
    private function notify(Builder $loads, string $notifiedColumn, callable $mailable): int
    {
        $sent = 0;

        $loads
            ->with(['faculties', 'subject', 'section'])
            ->whereHas('faculties', fn (Builder $faculties) => $faculties->whereNotNull('email'))
            ->chunkById(100, function (Collection $chunk) use ($mailable, $notifiedColumn, &$sent): void {
                foreach ($chunk as $load) {
                    foreach ($load->faculties->whereNotNull('email') as $faculty) {
                        Mail::to($faculty->email)->send($mailable($load, $faculty));
                        $sent++;
                    }

                    $load->forceFill([$notifiedColumn => now()])->saveQuietly();
                }
            });

        return $sent;
    }
}
