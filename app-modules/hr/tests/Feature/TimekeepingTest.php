<?php

namespace Modules\Hr\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Hr\Models\Employee;
use Tests\TestCase;

class TimekeepingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test timekeeping list
     * @return void
     */
    public function test_list_timekeepings()
    {
        Employee::factory()
            ->hasTimekeepings(5)
            ->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('timekeepings.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'employee_id',
                        'date',
                        'first_time_in',
                        'first_time_out',
                        'second_time_in',
                        'second_time_out',
                        'total_rendered',
                        'total_overtime',
                        'total_late',
                        'total_undertime',
                        'status',
                        'notes',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);

        $this->assertDatabaseCount('timekeepings', 5);
    }

    /**
     * Test timekeeping store
     * @return void
     */
    public function test_store_timekeeping()
    {
        $employee = Employee::factory()->hasCompensation(1)->create();
        $user     = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('timekeepings.store'), [
            'employee_id'     => $employee->id,
            'date'            => now()->format('Y-m-d'),
            'first_time_in'   => '08:00:00',
            'first_time_out'  => '12:00:00',
            'second_time_in'  => '13:00:00',
            'second_time_out' => '17:00:00',
            'notes'           => 'Sample notes'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'employee_id',
                'date',
                'first_time_in',
                'first_time_out',
                'second_time_in',
                'second_time_out',
                'total_rendered',
                'total_overtime',
                'total_late',
                'total_undertime',
                'status',
                'notes',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('timekeepings', [
            'employee_id'     => $employee->id,
            'date'            => now()->format('Y-m-d'),
            'first_time_in'   => '08:00:00',
            'first_time_out'  => '12:00:00',
            'second_time_in'  => '13:00:00',
            'second_time_out' => '17:00:00',
            'notes'           => 'Sample notes'
        ]);

        $this->assertEquals(480, $response->json('total_rendered')); // 480 minutes = 8 hours
        $this->assertEquals(0, $response->json('total_overtime'));
        $this->assertEquals(0, $response->json('total_late'));
        $this->assertEquals(0, $response->json('total_undertime'));
    }

    /**
     * Test timekeeping show
     * @return void
     */
    public function test_show_timekeeping()
    {
        $timekeeping = Employee::factory()
            ->hasTimekeepings(1)
            ->create()
            ->timekeepings()
            ->first();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('timekeepings.show', $timekeeping->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'employee_id',
                'date',
                'first_time_in',
                'first_time_out',
                'second_time_in',
                'second_time_out',
                'total_rendered',
                'total_overtime',
                'total_late',
                'total_undertime',
                'status',
                'notes',
                'created_at',
                'updated_at'
            ]);
    }

    /**
     * Test timekeeping update
     * @return void
     */
    public function test_update_timekeeping()
    {
        $timekeeping = Employee::factory()
            ->hasCompensation(1)
            ->hasTimekeepings(1)
            ->create()
            ->timekeepings()
            ->first();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson(route('timekeepings.update', $timekeeping->id), [
            'employee_id'     => $timekeeping->employee_id,
            'date'            => now()->format('Y-m-d'),
            'first_time_in'   => '09:00:00',
            'first_time_out'  => '12:00:00',
            'second_time_in'  => '13:00:00',
            'second_time_out' => '15:00:00',
            'notes'           => 'Sample notes'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'employee_id',
                'date',
                'first_time_in',
                'first_time_out',
                'second_time_in',
                'second_time_out',
                'total_rendered',
                'total_overtime',
                'total_late',
                'total_undertime',
                'status',
                'notes',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('timekeepings', [
            'id'              => $timekeeping->id,
            'employee_id'     => $timekeeping->employee_id,
            'date'            => now()->format('Y-m-d'),
            'first_time_in'   => '09:00:00',
            'first_time_out'  => '12:00:00',
            'second_time_in'  => '13:00:00',
            'second_time_out' => '15:00:00',
            'notes'           => 'Sample notes'
        ]);

        $this->assertEquals(300, $response->json('total_rendered')); // 360 minutes = 6 hours
        $this->assertEquals(0, $response->json('total_overtime'));
        $this->assertEquals(60, $response->json('total_late')); // 60 minutes = 1 hour
        $this->assertEquals(120, $response->json('total_undertime')); // 120 minutes = 2 hours
    }

    /**
     * Test timekeeping delete
     * @return void
     */
    public function test_delete_timekeeping()
    {
        $timekeeping = Employee::factory()
            ->hasTimekeepings(1)
            ->create()
            ->timekeepings()
            ->first();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson(route('timekeepings.destroy', $timekeeping->id));

        $response->assertStatus(204);

        $this->assertDatabaseCount('timekeepings', 0);
    }
}
