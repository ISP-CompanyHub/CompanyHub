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
            'view job postings',
            'create job postings',
            'edit job postings',
            'delete job postings',

            // Candidates
            'view candidates',
            'create candidates',
            'edit candidates',
            'delete candidates',
            'update candidate status',

            // Interviews
            'view interviews',
            'schedule interviews',
            'edit interviews',
            'delete interviews',

            // Job Offers
            'view job offers',
            'create job offers',
            'send job offers',
            'edit job offers',
            'delete job offers',

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

            // vacations & holidays for employees
            'view vacation',
            'create vacation',
            'view holidays',
            'view leave balances',
        ]);

        // System Administrator role - full CRUD on job postings, candidates, interviews, vacations, holidays
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

            'view job offers',

            'view documents',
            'create documents',
            'edit documents',
            'delete documents',

            // vacations & holidays
            'view vacation requests',
            'create vacation requests',
            'edit vacation requests',
            'delete vacation requests',
            'approve vacation requests',


            // vacations & holidays
            'view vacation',
            'create vacation',
            'edit vacation',
            'delete vacation',
            'approve vacation',
            'view leave balance',

            'view holidays',
            'create holidays',
            'edit holidays',
            'delete holidays',
        ]);

        // Manager role - can do everything (sync all existing permissions)
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->syncPermissions(Permission::all());
    }
}
