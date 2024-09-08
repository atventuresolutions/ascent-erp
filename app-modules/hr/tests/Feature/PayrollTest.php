<?php

namespace Modules\Hr\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Hr\Models\Addition;
use Modules\Hr\Models\Deduction;
use Modules\Hr\Models\Employee;
use Modules\Hr\Models\EmployeeAddition;
use Modules\Hr\Models\EmployeeDeduction;
use Modules\Hr\Models\Payroll;
use Modules\Hr\Models\Timekeeping;
use Modules\Hr\Services\PayrollService;
use Tests\TestCase;

class PayrollTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Addition::factory()->create();
        Deduction::factory()->create();

        Employee::factory()
            ->has(
                EmployeeDeduction::factory()->state([
                    'start_date' => '2024-01-01',
                    'end_date'   => '2024-01-15',
                    'type'       => 'FIXED',
                    'amount'     => 100,
                ])
            )
            ->has(
                EmployeeAddition::factory()->state([
                    'start_date' => '2024-01-01',
                    'end_date'   => '2024-01-15',
                    'type'       => 'PERCENTAGE',
                    'amount'     => 10,
                ])
            )
            ->hasCompensation(1)
            ->count(2)
            ->create();

        $employees = Employee::all();
        $dateRange = ['2024-01-01', '2024-01-02', '2024-01-03'];

        foreach ($dateRange as $date) {
            foreach ($employees as $employee) {
                Timekeeping::factory()
                    ->state([
                        'date'        => $date,
                        'employee_id' => $employee->id,
                        'status'      => 'APPROVED'
                    ])->create();
            }
        }

        // Generate payroll
        PayrollService::generatePayroll(
            '2024-01-01',
            '2024-01-03',
            'Temporary Payroll',
            'Temporary Payroll'
        );
    }

    /**
     * Test payroll store
     * @return void
     */
    public function test_store_payroll()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('payrolls.store'), [
            'start_date' => '2024-01-01',
            'end_date'   => '2024-01-05',
            'name'       => 'January 2024 Payroll',
            'notes'      => 'This is the payroll for January 2024',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'start_date',
                'end_date',
                'notes',
                'basic_pay',
                'overtime_pay',
                'holiday_pay',
                'total_deductions',
                'total_additions',
                'net_pay',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('payrolls', [
            'name'             => 'January 2024 Payroll',
            'notes'            => 'This is the payroll for January 2024',
            'start_date'       => '2024-01-01',
            'end_date'         => '2024-01-05',
            'basic_pay'        => 600,
            'overtime_pay'     => 0,
            'holiday_pay'      => 0,
            'total_deductions' => 200,
            'total_additions'  => 60,
            'net_pay'          => 460,
        ]);
    }

    /**
     * Test payroll list
     * @return void
     */
    public function test_list_payroll()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('payrolls.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name',
                        'start_date',
                        'end_date',
                        'notes',
                        'basic_pay',
                        'overtime_pay',
                        'holiday_pay',
                        'total_deductions',
                        'total_additions',
                        'net_pay',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }

    /**
     * Test payroll show
     * @return void
     */
    public function test_show_payroll()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payroll  = Payroll::first();
        $response = $this->getJson(route('payrolls.show', $payroll->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'start_date',
                'end_date',
                'notes',
                'basic_pay',
                'overtime_pay',
                'holiday_pay',
                'total_deductions',
                'total_additions',
                'net_pay',
                'created_at',
                'updated_at'
            ]);
    }

    /**
     * Test payroll update
     * @return void
     */
    public function test_update_payroll()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payroll  = Payroll::first();
        $response = $this->putJson(route('payrolls.update', $payroll->id), [
            'name'   => 'Temporary Payroll Updated',
            'notes'  => 'Temporary Payroll Updated',
            'status' => 'APPROVED',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'start_date',
                'end_date',
                'notes',
                'basic_pay',
                'overtime_pay',
                'holiday_pay',
                'total_deductions',
                'total_additions',
                'net_pay',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('payrolls', [
            'name'   => 'Temporary Payroll Updated',
            'notes'  => 'Temporary Payroll Updated',
            'status' => 'APPROVED',
        ]);
    }

    /**
     * Test payroll delete
     * @return void
     */
    public function test_delete_payroll()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payroll  = Payroll::first();
        $response = $this->deleteJson(route('payrolls.destroy', $payroll->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('payrolls', ['id' => $payroll->id]);
    }
}
