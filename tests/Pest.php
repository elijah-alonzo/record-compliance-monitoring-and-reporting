<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

use App\Features\Courses\Models\Course;
use App\Features\Departments\Models\Department;
use App\Features\Faculties\Models\Faculty;
use App\Features\Loads\Models\Load;
use App\Features\Sections\Models\Section;
use App\Features\Subjects\Models\Subject;

/**
 * Create a teaching load assigned to a faculty member, with every parent record it needs.
 *
 * @param  array<string, mixed>  $attributes
 */
function makeLoad(array $attributes = [], ?Faculty $faculty = null): Load
{
    static $n = 0;
    $n++;

    $department = Department::query()->firstOrCreate(['code' => 'DEP'], ['name' => 'Department', 'color' => '#024427']);
    $course = Course::query()->firstOrCreate(['code' => 'CRS'], ['name' => 'Course', 'department_id' => $department->id]);

    $faculty ??= Faculty::query()->create([
        'first_name' => 'Faculty',
        'last_name' => "Member {$n}",
        'employment_type' => 'Full-Time Faculty',
        'department_id' => $department->id,
        'email' => "faculty{$n}@example.com",
    ]);

    $load = Load::query()->create(array_merge([
        'subject_id' => Subject::query()->create([
            'subject_code' => "SUB{$n}", 'name' => "Subject {$n}", 'units' => 3, 'type' => 'Minor',
        ])->id,
        'section_id' => Section::query()->create([
            'code' => "SEC{$n}", 'year_level' => '1st Year', 'department_id' => $department->id, 'course_id' => $course->id,
        ])->id,
        'year' => '2025-2026',
        'semester' => '1st Semester',
        'due_date' => '2026-01-10',
        'status' => false,
    ], $attributes));

    $load->faculties()->attach($faculty);
    $load->unsetRelation('faculties');

    return $load;
}
