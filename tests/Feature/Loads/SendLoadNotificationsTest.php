<?php

use App\Features\Loads\Actions\SendLoadNotifications;
use App\Features\Loads\Mail\TeachingLoadDeadlineReminderMail;
use App\Features\Loads\Mail\TeachingLoadOverdueMail;
use Illuminate\Support\Facades\Mail;

it('emails faculty a week before the deadline, once', function () {
    Mail::fake();
    $load = makeLoad(['due_date' => now()->addDays(7)->toDateString()]);

    $sent = (new SendLoadNotifications)();
    (new SendLoadNotifications)();

    expect($sent)->toBe(['weekBefore' => 1, 'overdue' => 0]);
    Mail::assertSent(TeachingLoadDeadlineReminderMail::class, 1);
    expect($load->fresh()->week_before_notified_at)->not->toBeNull();
});

it('emails faculty about overdue loads and skips submitted ones', function () {
    Mail::fake();
    makeLoad(['due_date' => now()->subDay()->toDateString()]);
    makeLoad(['due_date' => now()->subDay()->toDateString(), 'status' => true, 'submitted_at' => now()]);

    $sent = (new SendLoadNotifications)();

    expect($sent['overdue'])->toBe(1);
    Mail::assertSent(TeachingLoadOverdueMail::class, 1);
});
