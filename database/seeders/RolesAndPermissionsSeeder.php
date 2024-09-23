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
     *
     * This seeder creates predefined roles and permissions in the database
     * for user management and access control.
     *
     * ## Permissions Defined
     * - Company Permissions: Manage companies (browse, read, add, edit, delete).
     * - Position Permissions: Manage job positions (browse, read, add, edit, delete).
     * - User Permissions: Manage users (browse, read, add, edit, delete).
     *
     * ## Roles Created
     * - **unregistered**: Default role with no permissions.
     * - **applicant**: Limited access to positions and their own user data.
     * - **client**: Access to manage their own company and positions.
     * - **staff**: Full access to manage all companies, positions, and users.
     * - **administrator**: Full access to manage all aspects of the system.
     * - **super-user**: Access to all permissions.
     */
    public function run()
    {
        // Define permissions
        $permissions = [
            // Company Permissions
            'browse companies', 'read company', 'add company', 'edit company', 'delete company', 'search companies',
            'soft delete company', 'undo company deletion', 'destroy company',

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

        // Unregistered has no permissions
        Role::firstOrCreate(['name' => 'unregistered']);

        // Applicant role permissions
        Role::firstOrCreate(['name' => 'applicant'])
            ->givePermissionTo([
                'browse positions', 'read position', 'search positions', // Position access
                'read user', 'edit user', 'delete user', 'soft delete user' // Manage own user data
            ]);

        // Client role permissions
        Role::firstOrCreate(['name' => 'client'])
            ->givePermissionTo([
                'browse companies', 'read company', 'add company', 'edit company', 'delete company', 'search companies', // Manage their own company
                'browse positions', 'read position', 'search positions', 'add position', 'edit position', 'delete position', // Manage their own positions
                'soft delete position', 'undo position deletion' // Soft delete for their positions
            ]);

        // Staff role permissions
        Role::firstOrCreate(['name' => 'staff'])
            ->givePermissionTo([
                'browse companies', 'read company', 'add company', 'edit company', 'delete company', 'search companies', // Manage all companies
                'soft delete company', 'undo company deletion', 'destroy company', // Full control over companies
                'browse positions', 'read position', 'add position', 'edit position', 'delete position', 'search positions', // Manage all positions
                'soft delete position', 'undo position deletion', 'destroy position', // Full control over positions
                'browse users', 'read user', 'add user', 'edit user', 'delete user', 'search users', // Manage all users
                'soft delete user', 'undo user deletion', 'destroy user' // Full control over users
            ]);

        // Administrator role permissions
        Role::firstOrCreate(['name' => 'administrator'])
            ->givePermissionTo([
                'browse companies', 'read company', 'add company', 'edit company', 'delete company', 'search companies',
                'soft delete company', 'undo company deletion', 'destroy company',
                'browse positions', 'read position', 'add position', 'edit position', 'delete position', 'search positions',
                'soft delete position', 'undo position deletion', 'destroy position',
                'browse users', 'read user', 'add user', 'edit user', 'delete user', 'search users',
                'soft delete user', 'undo user deletion', 'destroy user'
            ]);

        // Super-User role permissions (all permissions)
        Role::firstOrCreate(['name' => 'super-user'])
            ->givePermissionTo(Permission::all());
    }
}
