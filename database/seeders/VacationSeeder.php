<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vacation;
use Illuminate\Database\Seeder;

class VacationSeeder extends Seeder
{

    public function run(): void
    {
        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Employee')->first()->id,
                'submission_date' => '2025-01-10',
                'vacation_start' => '2025-02-01',
                'vacation_end' => '2025-02-02',
                'type' => 'paid',
                'status' => 'approved',
                'comments' => 'Family trip to the mountains.'
            ]
        );

        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Admin')->first()->id,
                'submission_date' => '2025-03-15',
                'vacation_start' => '2025-04-10',
                'vacation_end' => '2025-04-15',
                'type' => 'unpaid',
                'status' => 'pending',
                'comments' => 'Taking a few days for personal matters.'
            ]
        );

        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Admin')->first()->id,
                'submission_date' => '2025-05-20',
                'vacation_start' => '2025-06-15',
                'vacation_end' => '2025-06-17',
                'type' => 'sick',
                'status' => 'approved',
                'comments' => 'Scheduled surgery recovery.'
            ]
        );

        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Admin')->first()->id,
                'submission_date' => '2025-07-01',
                'vacation_start' => '2025-08-01',
                'vacation_end' => '2025-08-02',
                'type' => 'paid',
                'status' => 'rejected',
                'comments' => 'Summer beach vacation.'
            ]
        );

        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Admin')->first()->id,
                'submission_date' => '2025-09-10',
                'vacation_start' => '2025-10-05',
                'vacation_end' => '2025-10-10',
                'type' => 'remote',
                'status' => 'approved',
                'comments' => 'Working from home base for a week.'
            ]
        );

        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Test User4')->first()->id,
                'submission_date' => '2025-11-05',
                'vacation_start' => '2025-12-20',
                'vacation_end' => '2025-12-21',
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
        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Employee')->first()->id,
                'submission_date' => '2025-03-05',
                'vacation_start' => '2025-03-06',
                'vacation_end' => '2025-03-09',
                'type' => 'remote',
                'status' => 'pending',
                'comments' => 'remote'
            ]
        );
        Vacation::firstOrCreate(
            [
                'user_id' => User::where('name', 'Employee')->first()->id,
                'submission_date' => '2025-07-12',
                'vacation_start' => '2025-07-13',
                'vacation_end' => '2025-07-13',
                'type' => 'emergency',
                'status' => 'approved',
                'comments' => 'doctors appointment'
            ]
        );
    }
}
