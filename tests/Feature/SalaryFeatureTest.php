<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SalaryLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SalaryFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\SalaryComponentSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('administrator');
    }

    public function test_administrators_can_view_salaries_index()
    {
        $response = $this->actingAs($this->admin)->get(route('salaries.index'));
        $response->assertStatus(200);
    }

    public function test_administrators_can_create_salary_log()
    {
        $user = User::factory()->create();
        $base = \App\Models\SalaryComponent::where('name', 'Base Salary')->first();
        $tax = \App\Models\SalaryComponent::where('name', 'Income Tax')->first();

        $response = $this->actingAs($this->admin)->post(route('salaries.store'), [
            'user_id' => $user->id,
            'calculation_date' => now()->toDateString(),
            'components' => [
                ['id' => $base->id, 'amount' => 5000],
                ['id' => $tax->id, 'amount' => 1000],
            ],
        ]);

        $response->assertRedirect(route('salaries.index'));
        $this->assertDatabaseHas('salary_logs', [
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('salary_log_component', [
            'salary_component_id' => $base->id,
            'amount' => 5000,
        ]);
    }

    public function test_administrators_can_view_salary_details()
    {
        $salaryLog = SalaryLog::factory()->create();
        $response = $this->actingAs($this->admin)->get(route('salaries.show', $salaryLog));
        $response->assertStatus(200);
    }

    public function test_administrators_can_update_salary_log()
    {
        $salaryLog = SalaryLog::factory()->create();
        $bonus = \App\Models\SalaryComponent::where('name', 'Bonus')->first();

        $response = $this->actingAs($this->admin)->put(route('salaries.update', $salaryLog), [
            'user_id' => $salaryLog->user_id,
            'calculation_date' => $salaryLog->calculation_date->format('Y-m-d'),
            'components' => [
                ['id' => $bonus->id, 'amount' => 500],
            ],
        ]);

        $response->assertRedirect(route('salaries.show', $salaryLog));
        $this->assertDatabaseHas('salary_log_component', [
            'salary_log_id' => $salaryLog->id,
            'salary_component_id' => $bonus->id,
            'amount' => 500,
        ]);
    }

    public function test_administrators_can_delete_salary_log()
    {
        $salaryLog = SalaryLog::factory()->create();
        $response = $this->actingAs($this->admin)->delete(route('salaries.destroy', $salaryLog));

        $response->assertRedirect(route('salaries.index'));
        $this->assertDatabaseMissing('salary_logs', [
            'id' => $salaryLog->id,
        ]);
    }
}
