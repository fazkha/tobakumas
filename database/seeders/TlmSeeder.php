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
            'supplier-list',
            'supplier-show',
            'supplier-create',
            'supplier-edit',
            'supplier-delete',
            'po-list',
            'po-show',
            'po-create',
            'po-edit',
            'po-delete',
            'po-approval',
            'customer-list',
            'customer-show',
            'customer-create',
            'customer-edit',
            'customer-delete',
            'so-list',
            'so-show',
            'so-create',
            'so-edit',
            'so-delete',
            'so-approval',
            'satuan-list',
            'satuan-show',
            'satuan-create',
            'satuan-edit',
            'satuan-delete',
            'barang-list',
            'barang-show',
            'barang-create',
            'barang-edit',
            'barang-delete',
            'coa-list',
            'coa-show',
            'coa-create',
            'coa-edit',
            'coa-delete',
            'stopname-list',
            'stopname-show',
            'stopname-create',
            'stopname-edit',
            'stopname-delete',
            'gudang-list',
            'gudang-show',
            'gudang-create',
            'gudang-edit',
            'gudang-delete',
            'pegawai-list',
            'pegawai-show',
            'pegawai-create',
            'pegawai-edit',
            'pegawai-delete',
            'konversi-list',
            'konversi-show',
            'konversi-create',
            'konversi-edit',
            'konversi-delete',
        ];

        foreach ($permissions as $key => $permission) {
            Permission::create(['name' => $permission]);
        }

        // $role = Role::create(['name' => 'Admin']);
        // $role->givePermissionTo(Permission::all());

        // $user = User::find(1);
        // $user->assignRole('Admin');
    }
}
