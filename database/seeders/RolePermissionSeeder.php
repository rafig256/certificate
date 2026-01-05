<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        /**
         * ------------------------------------
         * Define Permissions
         * ------------------------------------
         */
        $permissions = [

            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Organs
            'organs.view',
            'organs.create',
            'organs.edit',
            'organs.delete',

            // Signatory
            'signator.view',
            'signator.create',
            'signator.edit',
            'signator.delete',

            // Certificate Holder (domain actions)
            'certificate_holder.link_user',
            'certificate_holder.unlink_user',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /**
         * ------------------------------------
         * Define Roles
         * ------------------------------------
         */
        $roles = [
            'administrator',
            'admin',
            'organizer',
            'signer',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }

        /**
         * ------------------------------------
         * Assign Permissions to Roles
         * ------------------------------------
         */

        // Administrator → everything
        $administrator = Role::findByName('administrator');
        $administrator->syncPermissions(Permission::all());

        // Admin (limited, no bypass)
        $admin = Role::findByName('admin');
        $admin->syncPermissions([
            'users.view',
            'organs.view',
            'signator.view',
            'certificate_holder.link_user',
        ]);

        // Organizer
        $organizer = Role::findByName('organizer');
        $organizer->syncPermissions([
            'organs.view',
            'organs.edit',
            'users.view',
            'certificate_holder.link_user',
        ]);

        // Signer
        $signer = Role::findByName('signer');
        $signer->syncPermissions([
            'signator.view',
            'signator.edit',
        ]);

        $adminUser = User::firstOrCreate(
            [
                'email' => 'rafig_256@yahoo.com',
            ],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );

        if (! $adminUser->hasRole('administrator')) {
            $adminUser->assignRole('administrator');
        }
    }
}
