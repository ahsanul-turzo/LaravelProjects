<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            'ban users',

            // Post management
            'view posts',
            'create posts',
            'edit posts',
            'delete posts',
            'moderate posts',

            // Comment management
            'view comments',
            'create comments',
            'edit comments',
            'delete comments',
            'moderate comments',

            // Subscription management
            'view subscriptions',
            'manage subscriptions',

            // Admin panel
            'access admin panel',
            'view analytics',
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdmin = \Spatie\Permission\Models\Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo($permissions);

        $admin = \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'view users',
            'edit users',
            'ban users',
            'view posts',
            'moderate posts',
            'view comments',
            'moderate comments',
            'view subscriptions',
            'access admin panel',
            'view analytics',
        ]);

        $moderator = \Spatie\Permission\Models\Role::create(['name' => 'moderator']);
        $moderator->givePermissionTo([
            'view posts',
            'moderate posts',
            'view comments',
            'moderate comments',
            'access admin panel',
        ]);

        $user = \Spatie\Permission\Models\Role::create(['name' => 'user']);
        $user->givePermissionTo([
            'create posts',
            'edit posts',
            'create comments',
            'edit comments',
        ]);

        // Create a super admin user
        $superAdminUser = \App\Models\User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'admin@socialmedia.test',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $superAdminUser->assignRole('super_admin');
    }
}
