<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vacation;
use Illuminate\Database\Seeder;

class VacationSeeder extends Seeder
{

    /**
     *
     * 'user_id',
     * 'submission_date',
     * 'vacation_start',
     * 'vacation_end',
     * 'type',
     * 'status',
     * 'comments',
     */
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Admin')->first()->id,
                'submission_date' => '2023-01-10',
                'vacation_start' => '2023-02-01',
                'vacation_end' => '2023-02-02',
                'type' => 'paid',
                'status' => 'approved',
                'comments' => 'Family trip to the mountains.'
            ]
        );

        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Admin')->first()->id,
                'submission_date' => '2023-03-15',
                'vacation_start' => '2023-04-10',
                'vacation_end' => '2023-04-15',
                'type' => 'unpaid',
                'status' => 'pending',
                'comments' => 'Taking a few days for personal matters.'
            ]
        );

        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Admin')->first()->id,
                'submission_date' => '2023-05-20',
                'vacation_start' => '2023-06-15',
                'vacation_end' => '2023-06-17',
                'type' => 'sick',
                'status' => 'approved',
                'comments' => 'Scheduled surgery recovery.'
            ]
        );

        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Admin')->first()->id,
                'submission_date' => '2023-07-01',
                'vacation_start' => '2023-08-01',
                'vacation_end' => '2023-08-02',
                'type' => 'paid',
                'status' => 'rejected',
                'comments' => 'Summer beach vacation.'
            ]
        );

        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Admin')->first()->id,
                'submission_date' => '2023-09-10',
                'vacation_start' => '2023-10-05',
                'vacation_end' => '2023-10-10',
                'type' => 'remote',
                'status' => 'approved',
                'comments' => 'Working from home base for a week.'
            ]
        );

        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Test User4')->first()->id,
                'submission_date' => '2023-11-05',
                'vacation_start' => '2023-12-20',
                'vacation_end' => '2023-12-21',
                'type' => 'paid',
                'status' => 'approved',
                'comments' => 'Christmas holidays.'
            ]
        );

        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Employee')->first()->id,
                'submission_date' => '2024-01-05',
                'vacation_start' => '2024-01-15',
                'vacation_end' => '2024-01-15',
                'type' => 'emergency',
                'status' => 'approved',
                'comments' => 'Urgent family requirement.'
            ]
        );
        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Employee')->first()->id,
                'submission_date' => '2025-01-05',
                'vacation_start' => '2025-01-20',
                'vacation_end' => '2025-01-22',
                'type' => 'vacation',
                'status' => 'approved',
                'comments' => 'Winter break.'
            ]
        );

// Entry 2: April 2025 (1 day)
        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Employee')->first()->id,
                'submission_date' => '2025-03-25',
                'vacation_start' => '2025-04-15',
                'vacation_end' => '2025-04-15',
                'type' => 'vacation',
                'status' => 'approved',
                'comments' => 'Personal day off.'
            ]
        );

// Entry 3: August 2025 (5 days)
        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Employee')->first()->id,
                'submission_date' => '2025-07-20',
                'vacation_start' => '2025-08-10',
                'vacation_end' => '2025-08-14',
                'type' => 'vacation',
                'status' => 'approved',
                'comments' => 'Summer vacation.'
            ]
        );

// Entry 4: December 2025 (2 days)
        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Employee')->first()->id,
                'submission_date' => '2025-11-30',
                'vacation_start' => '2025-12-28',
                'vacation_end' => '2025-12-30',
                'type' => 'vacation',
                'status' => 'approved',
                'comments' => 'Holidays.'
            ]
        );
    }
}
