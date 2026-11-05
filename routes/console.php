<?php

use App\Features\Loads\Actions\SendLoadNotifications;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('loads:send-overdue-notifications', function (SendLoadNotifications $sendLoadNotifications) {
    $sent = $sendLoadNotifications();

    $this->info("Week-before reminders sent: {$sent['weekBefore']}");
    $this->info("Overdue notifications sent: {$sent['overdue']}");
})->purpose('Send overdue notification emails for unsubmitted teaching loads.');

Schedule::command('loads:send-overdue-notifications')->dailyAt('08:00');
