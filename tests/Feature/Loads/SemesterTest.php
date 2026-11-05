<?php

use App\Features\Faculties\Models\Faculty;
use App\Features\Loads\Enums\Semester;
use Database\Seeders\DatabaseSeeder;

it('matches the semesters stored in the database', function () {
    expect(Semester::values())->toBe(['1st Semester', '2nd Semester', 'Summer']);
});

it('casts the load semester to the enum', function () {
    expect(makeLoad()->semester)->toBe(Semester::First);
});

it('seeds the demo data without errors', function () {
    $this->seed(DatabaseSeeder::class);

    expect(Faculty::count())->toBeGreaterThan(0);
});
