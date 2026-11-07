<?php

namespace Database\Seeders;

use App\Features\Users\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * What a fresh deployment needs: every panel permission, the two roles the
 * registrar panel lets in (admin with everything, staff with the academic
 * records), and the first admin from config/app.php (ADMIN_EMAIL,
 * ADMIN_PASSWORD). Everything else (departments, faculty, loads, deadlines,
 * other accounts) is set up in /registrar.
 * Safe to run again: it only adds what is missing.
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /** Records staff may fully manage. */
    private const STAFF_ENTITIES = ['Department', 'Faculty', 'Load', 'Section', 'Subject'];

    public function run(): void
    {
        Artisan::call('shield:generate', [
            '--all' => true,
            '--option' => 'permissions',
            '--panel' => 'registrar',
            '--no-interaction' => true,
            '--quiet' => true,
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = Permission::query()->pluck('name');

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions($permissions->all());

        $staffRole = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        if ($staffRole->permissions()->doesntExist()) {
            $staffRole->syncPermissions($permissions
                ->filter(fn (string $name): bool => in_array(Str::after($name, ':'), self::STAFF_ENTITIES, true))
                ->all());
        }

        User::firstOrCreate(
            ['email' => config('app.admin.email')],
            [
                'first_name' => 'System',
                'last_name' => 'Admin',
                'password' => Hash::make(config('app.admin.password')),
            ]
        )->syncRoles([$adminRole]);
    }
}
