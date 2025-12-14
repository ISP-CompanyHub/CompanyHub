<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
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
            // Job Postings
            'view job postings', // Not part of use case diagram, but added for completeness
            'create job postings',
            'edit job postings', // Not part of use case diagram, but added for completeness
            'delete job postings', // Not part of use case diagram, but added for completeness

            // Candidates
            'view candidates',
            'create candidates', // Not part of use case diagram, but added for completeness
            'edit candidates',
            'delete candidates', // Not part of use case diagram, but added for completeness
            'update candidate status', // Not part of use case diagram, but added for completeness

            // Interviews
            'view interviews', // Not part of use case diagram, but added for completeness
            'schedule interviews',
            'edit interviews', // Not part of use case diagram, but added for completeness
            'delete interviews', // Not part of use case diagram, but added for completeness

            // Job Offers
            'view job offers', // Not part of use case diagram, but added for completeness
            'create job offers',
            'edit job offers', // Not part of use case diagram, but added for completeness
            'delete job offers', // Not part of use case diagram, but added for completeness

            // Documents
            'view documents',
            'create documents',
            'edit documents',
            'delete documents',

            // Vacations (requests)
            'view vacation requests',
            'create vacation requests',
            'edit vacation requests',
            'delete vacation requests',
            'approve vacation requests',
            'view leave balances',

            // Holidays
            'view holidays',
            'create holidays',
            'edit holidays',
            'delete holidays',
            // Employee Profiles and Departments
            'view employee profile',
            'view departments', // Not part of use case diagram, but added to complement 'edit departments'
            'edit departments',
            'view employees',
            'edit employee profile',
            'create employee profile',
            'generate company structure',

            // Salaries
            'view salaries',
            'create salaries',
            'edit salaries',
            'delete salaries',
            'generate monthly salary',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Employee role - basic viewing and own-request permissions
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $employeeRole->syncPermissions([
            'view job postings',
            'view candidates',
            'view documents',
            'create documents',
            'edit documents',
            'delete documents',
            'view vacation requests',
            'create vacation requests',
            'view holidays',
            'view leave balances',
            'view employee profile',
            'generate company structure',
        ]);

        // System Administrator role - full CRUD on job postings, candidates, interviews, vacations, holidays, profiles
        $adminRole = Role::firstOrCreate(['name' => 'administrator']);
        $adminRole->syncPermissions([
            'view job postings',
            'create job postings',
            'edit job postings',
            'delete job postings',

            'view candidates',
            'create candidates',
            'edit candidates',
            'delete candidates',
            'update candidate status',

            'view interviews',
            'schedule interviews',
            'edit interviews',
            'delete interviews',

            'view documents',
            'create documents',
            'edit documents',
            'delete documents',

            'view vacation requests',
            'create vacation requests',
            'edit vacation requests',
            'approve vacation requests',
            'view leave balances',
            'view holidays',
            'create holidays',
            'edit holidays',
            'view employee profile',
            'view departments',
            'edit departments',
            'view employees',
            'edit employee profile',
            'create employee profile',

            'generate company structure',
        ]);

        // Manager role - can do everything (sync all existing permissions)
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->syncPermissions(Permission::all());
    }
}
