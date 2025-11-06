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

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Employee role - basic viewing permissions
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $employeeRole->syncPermissions([
            'view job postings',
            'view candidates',
            'view documents',
            'create documents',
            'edit documents',
            'delete documents',
        ]);

        // System Administrator role - full CRUD on job postings, candidates, and interviews
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
        ]);

        // Manager role - can do everything including sending job offers
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->syncPermissions(Permission::all());
    }
}
