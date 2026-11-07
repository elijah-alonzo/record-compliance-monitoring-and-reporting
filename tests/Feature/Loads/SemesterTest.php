<?php

use App\Features\Faculties\Models\Faculty;
use App\Features\Loads\Enums\Semester;
use App\Features\Loads\Models\Load;
use App\Features\Users\Models\User;
use Database\Seeders\DatabaseSeeder;
use Spatie\Permission\Models\Role;

it('matches the semesters stored in the database', function () {
    expect(Semester::values())->toBe(['1st Semester', '2nd Semester', 'Summer']);
});

it('casts the load semester to the enum', function () {
    expect(makeLoad()->semester)->toBe(Semester::First);
});

it('seeds only the first admin and the panel roles', function () {
    $this->seed(DatabaseSeeder::class);
    $this->seed(DatabaseSeeder::class);

    $admin = User::sole();

    expect($admin->email)->toBe(config('app.admin.email'))
        ->and($admin->hasRole('admin'))->toBeTrue()
        ->and(Role::pluck('name')->sort()->values()->all())->toBe(['admin', 'staff'])
        ->and(Role::findByName('staff')->hasPermissionTo('Update:Load'))->toBeTrue()
        ->and(Role::findByName('staff')->hasPermissionTo('ViewAny:User'))->toBeFalse()
        ->and(Faculty::count())->toBe(0)
        ->and(Load::count())->toBe(0);
});
