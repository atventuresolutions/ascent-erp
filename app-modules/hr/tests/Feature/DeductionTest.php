<?php

namespace Modules\Hr\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Hr\Models\Deduction;
use Tests\TestCase;

class DeductionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test deduction list
     * @return void
     */
    public function test_list_deductions()
    {
        Deduction::factory()->count(5)->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('deductions.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);

        $this->assertCount(5, $response['data']);
        $this->assertDatabaseCount('deductions', 5);
    }

    /**
     * Test deduction create
     * @return void
     */
    public function test_create_deduction()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('deductions.store'), [
            'name' => 'SSS',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('deductions', [
            'name' => 'SSS',
        ]);
    }

    /**
     * Test deduction show
     * @return void
     */
    public function test_show_deduction()
    {
        $deduction = Deduction::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('deductions.show', $deduction->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * Test deduction update
     * @return void
     */
    public function test_update_deduction()
    {
        $deduction = Deduction::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson(route('deductions.update', $deduction->id), [
            'name' => 'PhilHealth',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('deductions', [
            'name' => 'PhilHealth',
        ]);
    }

    /**
     * Test deduction delete
     * @return void
     */
    public function test_delete_deduction()
    {
        $deduction = Deduction::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson(route('deductions.destroy', $deduction->id));

        $response->assertStatus(204);

        $this->assertDatabaseCount('deductions', 0);
    }
}
