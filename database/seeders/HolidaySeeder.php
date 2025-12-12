<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Holiday;
use Hoa\Compiler\Test\Integration\Llk\Documentation;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{

    /**
    'holiday_date',
    'title',
    'type',
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        Holiday::firstOrCreate(
            [
                'holiday_date' => '2025-01-01',
                'title' => "New Year's Day",
                'type' => 'national'
            ]
        );
       Holiday::firstOrCreate(
           [
               'holiday_date' => '2025-03-08',
               'title' => "International Women's Day",
               'type' => 'observance'
           ]
       );
        Holiday::firstOrCreate(
            [
                'holiday_date' => '2025-05-01',
                'title' => 'Labor Day',
                'type' => 'national'
            ]
        );
        Holiday::firstOrCreate(
            [
                'holiday_date' => '2025-07-04',
                'title' => 'Independence Day',
                'type' => 'national'
            ]
        );
        Holiday::firstOrCreate(
            [
                'holiday_date' => '2025-08-15',
                'title' => 'Company Day Off',
                'type' => 'company'
            ]
        );
        Holiday::firstOrCreate(
            [
                'holiday_date' => '2025-11-28',
                'title' => 'Founders Day',
                'type' => 'company'
            ]
        );
        Holiday::firstOrCreate(
            [
                'holiday_date' => '2025-12-25',
                'title' => 'Christmas Day',
                'type' => 'national'
            ]
        );
    }
}
