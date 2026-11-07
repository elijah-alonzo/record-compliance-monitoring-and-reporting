<?php

use App\Features\Users\Models\User;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
    $this->actingAs(User::where('email', config('app.admin.email'))->firstOrFail());
});

it('sends guests to the login page', function () {
    auth()->logout();

    $this->get('/reports/loads')->assertRedirect('/registrar/login');
});

it('lists only late submissions', function () {
    $late = makeLoad(['status' => true, 'submitted_at' => '2026-01-12 09:00:00']);
    $onTime = makeLoad(['status' => true, 'submitted_at' => '2026-01-09 09:00:00']);

    $this->get('/reports/late-submissions?year=2025-2026&semester=1st Semester')
        ->assertOk()
        ->assertViewHas('loads', fn ($loads) => $loads->pluck('id')->all() === [$late->id]);

    expect($onTime->submissionStatus())->toBe('On Time');
});

it('lists loads that have not been submitted', function () {
    $pending = makeLoad();
    makeLoad(['status' => true, 'submitted_at' => '2026-01-09 09:00:00']);

    $this->get('/reports/not-submitted?year=2025-2026&semester=1st Semester')
        ->assertOk()
        ->assertViewHas('loads', fn ($loads) => $loads->pluck('id')->all() === [$pending->id]);
});

it('limits the faculty report to one faculty member', function () {
    $mine = makeLoad();
    $other = makeLoad();

    $this->get('/reports/faculty?faculty='.$mine->faculties->first()->id)
        ->assertOk()
        ->assertViewHas('loads', fn ($loads) => $loads->pluck('id')->all() === [$mine->id]);

    expect($other->faculties)->toHaveCount(1);
});

it('treats "all" as no filter and applies status and timing', function () {
    makeLoad();
    $late = makeLoad(['status' => true, 'submitted_at' => '2026-01-12 09:00:00']);

    $this->get('/reports/loads?year=all&semester=all&status=submitted&timing=late')
        ->assertOk()
        ->assertViewHas('loads', fn ($loads) => $loads->pluck('id')->all() === [$late->id])
        ->assertViewHas('semester', 'All Semesters');
});

it('rejects an unknown semester', function () {
    $this->get('/reports/late-submissions?year=2025-2026&semester=Fall')->assertSessionHasErrors('semester');
});
