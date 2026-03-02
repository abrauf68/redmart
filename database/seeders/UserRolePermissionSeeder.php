<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        Permission::create(['name' => 'view role']);
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'update role']);
        Permission::create(['name' => 'delete role']);

        Permission::create(['name' => 'view permission']);
        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'update permission']);
        Permission::create(['name' => 'delete permission']);

        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);

        Permission::create(['name' => 'view agent']);
        Permission::create(['name' => 'create agent']);
        Permission::create(['name' => 'update agent']);
        Permission::create(['name' => 'delete agent']);

        Permission::create(['name' => 'view customer']);
        Permission::create(['name' => 'create customer']);
        Permission::create(['name' => 'update customer']);
        Permission::create(['name' => 'delete customer']);

        Permission::create(['name' => 'view archived user']);
        Permission::create(['name' => 'create archived user']);
        Permission::create(['name' => 'update archived user']);
        Permission::create(['name' => 'delete archived user']);

        Permission::create(['name' => 'view setting']);
        Permission::create(['name' => 'create setting']);
        Permission::create(['name' => 'update setting']);
        Permission::create(['name' => 'delete setting']);


        // Create Roles
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $agentRole = Role::create(['name' => 'agent']);
        $userRole = Role::create(['name' => 'user']);

        // give all permissions to super-admin role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $superAdminRole->givePermissionTo($allPermissionNames);

        $agentRole->givePermissionTo(['create customer', 'view customer', 'update customer']);


        // Create User and assign Role to it.

        $superAdminUser = User::firstOrCreate([
                    'email' => 'admin@gmail.com',
                ], [
                    'name' => 'Admin',
                    'username' => 'admin',
                    'email' => 'admin@gmail.com',
                    'is_approved' => '1',
                    'password' => Hash::make ('admin123'),
                    'email_verified_at' => now(),
                ]);

        $superAdminUser->assignRole($superAdminRole);

        $superAdminProfile = $superAdminUser->profile()->firstOrCreate([
            'user_id' => $superAdminUser->id,
        ], [
            'user_id' => $superAdminUser->id,
            'first_name' => $superAdminUser->name,
        ]);

        $agentUser = User::firstOrCreate([
                            'email' => 'agent@gmail.com'
                        ], [
                            'name' => 'agent',
                            'username' => 'agent',
                            'email' => 'agent@gmail.com',
                            'is_approved' => '1',
                            'password' => Hash::make ('12345678'),
                            'email_verified_at' => now(),
                        ]);

        $agentUser->assignRole($agentRole);

        $adminUserProfile = $agentUser->profile()->firstOrCreate([
            'user_id' => $agentUser->id,
        ], [
            'user_id' => $agentUser->id,
            'first_name' => $agentUser->name,
        ]);
    }
}
