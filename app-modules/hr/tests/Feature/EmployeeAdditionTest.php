<?php

namespace Modules\Hr\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Hr\Models\Addition;
use Modules\Hr\Models\Employee;
use Tests\TestCase;

class EmployeeAdditionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test employee addition list
     * @return void
     */
    public function test_list_employee_additions()
    {
        Addition::factory()->count(5)->create();
        $employee = Employee::factory()->hasEmployeeAdditions(5)->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('employees.employeeAdditions.index', [$employee->id]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'addition',
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

        $this->assertDatabaseCount('employee_additions', 5);
    }

    /**
     * Test employee addition create
     * @return void
     */
    public function test_create_employee_addition()
    {
        $addition = Addition::factory()->create();
        $employee = Employee::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('employees.employeeAdditions.store', [$employee->id]), [
            'addition_id' => $addition->id,
            'type'        => 'FIXED',
            'amount'      => 1000,
            'start_date'  => now()->subMonth()->format('Y-m-d'),
            'end_date'    => now()->addMonth()->format('Y-m-d'),
            'notes'       => 'Test notes',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'addition',
                'type',
                'amount',
                'start_date',
                'end_date',
                'notes',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('employee_additions', [
            'addition_id' => $addition->id,
            'type'        => 'FIXED',
            'amount'      => 1000,
            'start_date'  => now()->subMonth()->format('Y-m-d'),
            'end_date'    => now()->addMonth()->format('Y-m-d'),
            'notes'       => 'Test notes',
        ]);
    }

    /**
     * Test employee addition show
     * @return void
     */
    public function test_show_employee_addition()
    {
        $addition = Addition::factory()->create();
        $employee = Employee::factory()->create();
        $employeeAddition = $employee->employeeAdditions()->create([
            'addition_id' => $addition->id,
            'type'        => 'FIXED',
            'amount'      => 1000,
            'start_date'  => now()->subMonth()->format('Y-m-d'),
            'end_date'    => now()->addMonth()->format('Y-m-d'),
            'notes'       => 'Test notes',
        ]);
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('employeeAdditions.show', [$employeeAddition->id]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'addition',
                'type',
                'amount',
                'start_date',
                'end_date',
                'notes',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('employee_additions', [
            'addition_id' => $addition->id,
            'type'        => 'FIXED',
            'amount'      => 1000,
            'start_date'  => now()->subMonth()->format('Y-m-d'),
            'end_date'    => now()->addMonth()->format('Y-m-d'),
            'notes'       => 'Test notes',
        ]);
    }

    /**
     * Test employee addition update
     * @return void
     */
    public function test_update_employee_addition()
    {
        $addition = Addition::factory()->create();
        $employee = Employee::factory()->create();
        $employeeAddition = $employee->employeeAdditions()->create([
            'addition_id' => $addition->id,
            'type'        => 'FIXED',
            'amount'      => 1000,
            'start_date'  => now()->subMonth()->format('Y-m-d'),
            'end_date'    => now()->addMonth()->format('Y-m-d'),
            'notes'       => 'Test notes',
        ]);
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson(route('employeeAdditions.update', [$employeeAddition->id]), [
            'addition_id' => $addition->id,
            'type'        => 'PERCENTAGE',
            'amount'      => 10,
            'start_date'  => now()->subMonth()->format('Y-m-d'),
            'end_date'    => now()->addMonth()->format('Y-m-d'),
            'notes'       => 'Test notes updated',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'addition',
                'type',
                'amount',
                'start_date',
                'end_date',
                'notes',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('employee_additions', [
            'addition_id' => $addition->id,
            'type'        => 'PERCENTAGE',
            'amount'      => 10,
            'start_date'  => now()->subMonth()->format('Y-m-d'),
            'end_date'    => now()->addMonth()->format('Y-m-d'),
            'notes'       => 'Test notes updated',
        ]);
    }

    /**
     * Test employee addition delete
     * @return void
     */
    public function test_delete_employee_addition()
    {
        $addition = Addition::factory()->create();
        $employee = Employee::factory()->create();
        $employeeAddition = $employee->employeeAdditions()->create([
            'addition_id' => $addition->id,
            'type'        => 'FIXED',
            'amount'      => 1000,
            'start_date'  => now()->subMonth()->format('Y-m-d'),
            'end_date'    => now()->addMonth()->format('Y-m-d'),
            'notes'       => 'Test notes',
        ]);
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson(route('employeeAdditions.destroy', [$employeeAddition->id]));

        $response->assertStatus(204);

        $this->assertDatabaseCount('employee_additions', 0);
    }
}
