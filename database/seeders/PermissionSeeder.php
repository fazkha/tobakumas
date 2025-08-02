<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'role-list',
            'role-show',
            'role-create',
            'role-edit',
            'role-delete',
            'user-list',
            'user-show',
            'user-create',
            'user-edit',
            'user-delete',
        ];

        foreach ($permissions as $key => $permission) {
            Permission::create(['name' => $permission]);
        }

        $role = Role::create(['name' => 'Admin']);
        $role->givePermissionTo(Permission::all());

        $user = User::find(1);
        $user->assignRole('Admin');
    }
}
