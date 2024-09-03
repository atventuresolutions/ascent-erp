<?php

namespace Modules\Hr\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Hr\Models\Addition;
use Tests\TestCase;

class AdditionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test addition list
     * @return void
     */
    public function test_list_additions()
    {
        Addition::factory()->count(5)->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('additions.index'));

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
        $this->assertDatabaseCount('additions', 5);
    }

    /**
     * Test addition create
     * @return void
     */
    public function test_create_addition()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('additions.store'), [
            'name' => 'SSS',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('additions', [
            'name' => 'SSS',
        ]);
    }

    /**
     * Test addition show
     * @return void
     */
    public function test_show_addition()
    {
        $addition = Addition::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('additions.show', $addition->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * Test addition update
     * @return void
     */
    public function test_update_addition()
    {
        $addition = Addition::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson(route('additions.update', $addition->id), [
            'name' => 'PhilHealth',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('additions', [
            'name' => 'PhilHealth',
        ]);
    }

    /**
     * Test addition delete
     * @return void
     */
    public function test_delete_addition()
    {
        $addition = Addition::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson(route('additions.destroy', $addition->id));

        $response->assertStatus(204);

        $this->assertDatabaseCount('additions', 0);
    }
}
