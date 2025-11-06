<?php

namespace Database\Seeders;

use App\Models\Department;
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
        $this->call(DepartmentSeeder::class);

        // Create test users with different roles
        $admin = User::firstOrCreate(
            ['email' => 'admin@companyhub.com'],
            [
                'name' => 'Admin',
                'surname' => 'User',
                'personal_id' => 'A123456789',
                'date_of_birth' => '2000-01-01',
                'phone_number' => '+1234567890',
                'address' => '123 Admin St, City, Country',
                'job_title' => 'System Administrator',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'department_id' => Department::query()->where('name', 'Administration')->first()->id,
            ]
        );
        $admin->syncRoles(['administrator']);

        $manager = User::firstOrCreate(
            ['email' => 'manager@companyhub.com'],
            [
                'name' => 'Manager',
                'surname' => 'User',
                'personal_id' => 'M987654321',
                'date_of_birth' => '1990-05-15',
                'phone_number' => '+0987654321',
                'address' => '456 Manager Ave, City, Country',
                'job_title' => 'Manager',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'department_id' => Department::query()->where('name', 'Sales')->first()->id,
            ]
        );
        $manager->syncRoles(['manager']);

        $employee = User::firstOrCreate(
            ['email' => 'employee@companyhub.com'],
            [
                'name' => 'Employee',
                'surname' => 'User',
                'personal_id' => 'E112233445',
                'date_of_birth' => '1995-09-20',
                'phone_number' => '+1122334455',
                'address' => '789 Employee Rd, City, Country',
                'job_title' => 'Staff Member',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'department_id' => Department::query()->where('name', 'Customer Support')->first()->id,
            ]
        );
        $employee->syncRoles(['employee']);

        // Original test user
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'surname' => 'Example',
                'personal_id' => 'T000000001',
                'date_of_birth' => '1990-01-01',
                'phone_number' => '+1000000000',
                'address' => '100 Test Blvd, City, Country',
                'job_title' => 'Tester',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'department_id' => Department::query()->where('name', 'IT')->first()->id,
            ]
        );
    }
}
