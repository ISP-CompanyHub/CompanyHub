<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RolePermissionSeeder::class);

        // Create test users with different roles
        $admin = User::firstOrCreate(
            ['email' => 'admin@companyhub.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['administrator']);

        $manager = User::firstOrCreate(
            ['email' => 'manager@companyhub.com'],
            [
                'name' => 'Manager User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $manager->syncRoles(['manager']);

        $employee = User::firstOrCreate(
            ['email' => 'employee@companyhub.com'],
            [
                'name' => 'Employee User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $employee->syncRoles(['employee']);

        // Original test user
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
