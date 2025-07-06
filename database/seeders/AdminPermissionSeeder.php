<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionsSuperAdmin = Permission::pluck('id')->toArray() ?? [];
        $superAdmin = Role::where('name', 'Super Admin')->first();
        $superAdmin->syncPermissions([]);

        $superAdmin->syncPermissions($permissionsSuperAdmin);

        $admin = Role::where('name', 'Admin')->first();
        $admin->syncPermissions([]);

        $admin->syncPermissions($permissionsSuperAdmin);
    }
}
