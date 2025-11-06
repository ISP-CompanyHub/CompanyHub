<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::firstOrCreate(
            ['name' => 'Human Resources'],
            ['description' => 'Handles recruitment, employee relations, and benefits.'],
        );

        Department::firstOrCreate(
            ['name' => 'Finance'],
            ['description' => 'Manages company finances, budgeting, and accounting.'],
        );

        Department::firstOrCreate(
            ['name' => 'IT'],
            ['description' => 'Responsible for technology infrastructure and support.'],
        );

        Department::firstOrCreate(
            ['name' => 'Marketing'],
            ['description' => 'Focuses on market research, advertising, and promotions.'],
        );

        Department::firstOrCreate(
            ['name' => 'Sales'],
            ['description' => 'Oversees customer acquisition and revenue generation.'],
        );

        Department::firstOrCreate(
            ['name' => 'Customer Support'],
            ['description' => 'Provides assistance and support to customers.'],
        );

        Department::firstOrCreate(
            ['name' => 'Research and Development'],
            ['description' => 'Innovates and develops new products and services.'],
        );

        Department::firstOrCreate(
            ['name' => 'Operations'],
            ['description' => 'Manages day-to-day business operations and logistics.'],
        );

        Department::firstOrCreate(
            ['name' => 'Legal'],
            ['description' => 'Handles legal matters and compliance issues.'],
        );

        Department::firstOrCreate(
            ['name' => 'Administration'],
            ['description' => 'Supports overall organizational functions and management.'],
        );
    }
}
