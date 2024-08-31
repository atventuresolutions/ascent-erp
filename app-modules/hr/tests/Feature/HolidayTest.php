<?php

namespace Modules\Hr\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Hr\Models\Holiday;
use Tests\TestCase;

class HolidayTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test holiday list
     * @return void
     */
    public function test_list_holidays()
    {
        Holiday::factory()->count(5)->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('holidays.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'date',
                        'type',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);

        $this->assertCount(5, $response['data']);
        $this->assertDatabaseCount('holidays', 5);
    }

    /**
     * Test holiday create
     * @return void
     */
    public function test_create_holiday()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('holidays.store'), [
            'name' => 'New Year',
            'date' => '2022-01-01',
            'type' => 'REGULAR',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'date',
                'type',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('holidays', [
            'name' => 'New Year',
            'date' => '2022-01-01',
            'type' => 'regular',
        ]);
    }

    /**
     * Test holiday show
     * @return void
     */
    public function test_show_holiday()
    {
        $holiday = Holiday::factory()->create();
        $user    = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('holidays.show', $holiday->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'date',
                'type',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * Test holiday update
     * @return void
     */
    public function test_update_holiday()
    {
        $holiday = Holiday::factory()->create();
        $user    = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson(route('holidays.update', $holiday->id), [
            'name' => 'New Year',
            'date' => '2022-01-01',
            'type' => 'REGULAR',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'date',
                'type',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('holidays', [
            'name' => 'New Year',
            'date' => '2022-01-01',
            'type' => 'regular',
        ]);
    }

    /**
     * Test holiday delete
     * @return void
     */
    public function test_delete_holiday()
    {
        $holiday = Holiday::factory()->create();
        $user    = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson(route('holidays.destroy', $holiday->id));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('holidays', [
            'id' => $holiday->id,
        ]);
    }
}
