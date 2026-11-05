<?php

namespace Database\Seeders;

use App\Features\Users\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedPermissions();

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $staffRole = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);

        $adminRole->syncPermissions(Permission::query()->get());
        $staffRole->syncPermissions(Permission::query()
            ->whereIn('name', $this->staffPermissionNames())
            ->get());

        $adminUser = User::create([
            'first_name' => 'Registrar',
            'middle_name' => null,
            'last_name' => 'Admin',
            'email' => 'admin@registrar.com',
            'password' => Hash::make('password'),
        ]);
        $adminUser->assignRole($adminRole);

        $staffUser = User::create([
            'first_name' => 'Registrar',
            'middle_name' => null,
            'last_name' => 'Staff',
            'email' => 'staff@registrar.com',
            'password' => Hash::make('password'),
        ]);
        $staffUser->assignRole($staffRole);
    }

    private function seedPermissions(): void
    {
        foreach ($this->permissionMap() as $entity => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action}:{$entity}",
                    'guard_name' => 'web',
                ]);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * @return array<string, array<int, string>>
     */
    private function permissionMap(): array
    {
        return [
            'Department' => [
                'ViewAny',
                'View',
                'Create',
                'Update',
                'Delete',
                'DeleteAny',
                'Restore',
                'ForceDelete',
                'ForceDeleteAny',
                'RestoreAny',
                'Replicate',
                'Reorder',
            ],
            'Faculty' => [
                'ViewAny',
                'View',
                'Create',
                'Update',
                'Delete',
                'DeleteAny',
                'Restore',
                'ForceDelete',
                'ForceDeleteAny',
                'RestoreAny',
                'Replicate',
                'Reorder',
            ],
            'Load' => [
                'ViewAny',
                'View',
                'Create',
                'Update',
                'Delete',
                'DeleteAny',
                'Restore',
                'ForceDelete',
                'ForceDeleteAny',
                'RestoreAny',
                'Replicate',
                'Reorder',
            ],
            'Section' => [
                'ViewAny',
                'View',
                'Create',
                'Update',
                'Delete',
                'DeleteAny',
                'Restore',
                'ForceDelete',
                'ForceDeleteAny',
                'RestoreAny',
                'Replicate',
                'Reorder',
            ],
            'Subject' => [
                'ViewAny',
                'View',
                'Create',
                'Update',
                'Delete',
                'DeleteAny',
                'Restore',
                'ForceDelete',
                'ForceDeleteAny',
                'RestoreAny',
                'Replicate',
                'Reorder',
            ],
            'User' => [
                'ViewAny',
                'View',
                'Create',
                'Update',
                'Delete',
                'DeleteAny',
                'Restore',
                'ForceDelete',
                'ForceDeleteAny',
                'RestoreAny',
                'Replicate',
                'Reorder',
            ],
            'Role' => [
                'ViewAny',
                'View',
                'Create',
                'Update',
                'Delete',
                'DeleteAny',
                'Restore',
                'ForceDelete',
                'ForceDeleteAny',
                'RestoreAny',
                'Replicate',
                'Reorder',
            ],
        ];
    }

    /**
     * @return array<int, string>
     */
    private function staffPermissionNames(): array
    {
        $names = [];

        foreach (['Department', 'Faculty', 'Load', 'Section', 'Subject'] as $entity) {
            foreach ($this->permissionMap()[$entity] as $action) {
                $names[] = "{$action}:{$entity}";
            }
        }

        return $names;
    }
}
