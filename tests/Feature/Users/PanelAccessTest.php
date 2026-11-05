<?php

use App\Features\Users\Models\User;
use Database\Seeders\AdminUserSeeder;

it('sends guests to the login page', function () {
    $this->get('/registrar')->assertRedirect('/registrar/login');
});

it('lets the seeded admin open the dashboard', function () {
    $this->seed(AdminUserSeeder::class);

    $this->actingAs(User::where('email', 'admin@registrar.com')->firstOrFail())
        ->get('/registrar')
        ->assertOk();
});

it('keeps users without a role out of the panel', function () {
    $user = User::create([
        'first_name' => 'No',
        'last_name' => 'Role',
        'email' => 'norole@example.com',
        'password' => 'password',
    ]);

    $this->actingAs($user)->get('/registrar')->assertForbidden();
});
