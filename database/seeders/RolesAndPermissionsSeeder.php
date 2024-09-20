<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define permissions
        $permissions = [
            // Company Permissions
            'browse companies', 'read company', 'add company', 'edit company', 'delete company', 'search companies',

            // Position Permissions
            'browse positions', 'read position', 'add position', 'edit position', 'delete position', 'search positions',
            'soft delete position', 'undo position deletion', 'destroy position',

            // User Permissions
            'browse users', 'read user', 'add user', 'edit user', 'delete user', 'search users',
            'soft delete user', 'undo user deletion', 'destroy user'
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        // Unregistered has no permissions
        Role::firstOrCreate(['name' => 'unregistered']);

        // Applicant role permissions
        Role::firstOrCreate(['name' => 'applicant'])
            ->givePermissionTo([
                'browse positions', 'read position', 'search positions',
                'read user', 'edit user', 'delete user', 'soft delete user'
            ]);

        // Client role permissions
        Role::firstOrCreate(['name' => 'client'])
            ->givePermissionTo([
                'browse companies', 'read company', 'add company', 'edit company', 'delete company', 'search companies',
                'browse positions', 'read position', 'search positions', 'add position', 'edit position', 'delete position',
                'soft delete position', 'undo position deletion'
            ]);

        // Staff role permissions
        Role::firstOrCreate(['name' => 'staff'])
            ->givePermissionTo([
                'browse companies', 'read company', 'add company', 'edit company', 'delete company', 'search companies',
                'browse positions', 'read position', 'add position', 'edit position', 'delete position', 'search positions',
                'soft delete position', 'undo position deletion', 'destroy position',
                'browse users', 'read user', 'add user', 'edit user', 'delete user', 'search users',
                'soft delete user', 'undo user deletion', 'destroy user'
            ]);

        // Administrator and Super-User get all permissions
        $allPermissions = Permission::all();

        Role::firstOrCreate(['name' => 'administrator'])
            ->givePermissionTo($allPermissions);

        Role::firstOrCreate(['name' => 'super-user'])
            ->givePermissionTo($allPermissions);
    }
}
