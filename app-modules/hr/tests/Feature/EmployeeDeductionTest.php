<?php

namespace Modules\Hr\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Hr\Models\Deduction;
use Modules\Hr\Models\Employee;
use Tests\TestCase;

class EmployeeDeductionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test employee deduction list
     * @return void
     */
    public function test_list_employee_deductions()
    {
        Deduction::factory()->count(5)->create();
        $employee = Employee::factory()->hasEmployeeDeductions(5)->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('employees.employeeDeductions.index', [$employee->id]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'deduction',
                        'type',
                        'amount',
                        'start_date',
                        'end_date',
                        'notes',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);

        $this->assertDatabaseCount('employee_deductions', 5);
    }

    /**
     * Test employee deduction create
     * @return void
     */
    public function test_create_employee_deduction()
    {
        $deduction = Deduction::factory()->create();
        $employee = Employee::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('employees.employeeDeductions.store', [$employee->id]), [
            'deduction_id' => $deduction->id,
            'type'        => 'FIXED',
            'amount'      => 1000,
            'start_date'  => now()->subMonth()->format('Y-m-d'),
            'end_date'    => now()->addMonth()->format('Y-m-d'),
            'notes'       => 'Test notes',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'deduction',
                'type',
                'amount',
                'start_date',
                'end_date',
                'notes',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('employee_deductions', [
            'deduction_id' => $deduction->id,
            'type'        => 'FIXED',
            'amount'      => 1000,
            'start_date'  => now()->subMonth()->format('Y-m-d'),
            'end_date'    => now()->addMonth()->format('Y-m-d'),
            'notes'       => 'Test notes',
        ]);
    }

    /**
     * Test employee deduction show
     * @return void
     */
    public function test_show_employee_deduction()
    {
        $deduction = Deduction::factory()->create();
        $employee = Employee::factory()->create();
        $employeeDeduction = $employee->employeeDeductions()->create([
            'deduction_id' => $deduction->id,
            'type'        => 'FIXED',
            'amount'      => 1000,
            'start_date'  => now()->subMonth()->format('Y-m-d'),
            'end_date'    => now()->addMonth()->format('Y-m-d'),
            'notes'       => 'Test notes',
        ]);
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('employeeDeductions.show', [$employeeDeduction->id]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'deduction',
                'type',
                'amount',
                'start_date',
                'end_date',
                'notes',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('employee_deductions', [
            'deduction_id' => $deduction->id,
            'type'        => 'FIXED',
            'amount'      => 1000,
            'start_date'  => now()->subMonth()->format('Y-m-d'),
            'end_date'    => now()->addMonth()->format('Y-m-d'),
            'notes'       => 'Test notes',
        ]);
    }

    /**
     * Test employee deduction update
     * @return void
     */
    public function test_update_employee_deduction()
    {
        $deduction = Deduction::factory()->create();
        $employee = Employee::factory()->create();
        $employeeDeduction = $employee->employeeDeductions()->create([
            'deduction_id' => $deduction->id,
            'type'        => 'FIXED',
            'amount'      => 1000,
            'start_date'  => now()->subMonth()->format('Y-m-d'),
            'end_date'    => now()->addMonth()->format('Y-m-d'),
            'notes'       => 'Test notes',
        ]);
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson(route('employeeDeductions.update', [$employeeDeduction->id]), [
            'deduction_id' => $deduction->id,
            'type'        => 'PERCENTAGE',
            'amount'      => 10,
            'start_date'  => now()->subMonth()->format('Y-m-d'),
            'end_date'    => now()->addMonth()->format('Y-m-d'),
            'notes'       => 'Test notes updated',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'deduction',
                'type',
                'amount',
                'start_date',
                'end_date',
                'notes',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('employee_deductions', [
            'deduction_id' => $deduction->id,
            'type'        => 'PERCENTAGE',
            'amount'      => 10,
            'start_date'  => now()->subMonth()->format('Y-m-d'),
            'end_date'    => now()->addMonth()->format('Y-m-d'),
            'notes'       => 'Test notes updated',
        ]);
    }

    /**
     * Test employee deduction delete
     * @return void
     */
    public function test_delete_employee_deduction()
    {
        $deduction = Deduction::factory()->create();
        $employee = Employee::factory()->create();
        $employeeDeduction = $employee->employeeDeductions()->create([
            'deduction_id' => $deduction->id,
            'type'        => 'FIXED',
            'amount'      => 1000,
            'start_date'  => now()->subMonth()->format('Y-m-d'),
            'end_date'    => now()->addMonth()->format('Y-m-d'),
            'notes'       => 'Test notes',
        ]);
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson(route('employeeDeductions.destroy', [$employeeDeduction->id]));

        $response->assertStatus(204);

        $this->assertDatabaseCount('employee_deductions', 0);
    }
}
