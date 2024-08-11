<?php

namespace Modules\Inventory\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Inventory\Models\InventoryItem;
use Modules\Inventory\Tests\InventoryServiceProviderTest as TestCase;

class InventoryItemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test inventory item list
     *
     * @return void
     */
    public function test_list_inventory_item(): void
    {
        InventoryItem::factory()->count(5)->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('inventory.items.index'));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'sku',
                    'name',
                    'description',
                    'unit_of_measure',
                    'price',
                    'location',
                    'status',
                    'image',
                    'notes',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    /**
     * Test create inventory item
     *
     * @return void
     */
    public function test_create_inventory_item()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('inventory.items.store'), [
            'sku'             => 'SKU-123',
            'name'            => 'Test Item',
            'description'     => 'Test Description',
            'unit_of_measure' => 'PCS',
            'price'           => 100,
            'location'        => 'A1',
            'status'          => 'active',
            'notes'           => 'Test Notes',
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'id',
            'sku',
            'name',
            'description',
            'unit_of_measure',
            'price',
            'location',
            'status',
            'notes',
            'created_at',
            'updated_at',
        ]);
    }

    /**
     * Test show inventory item
     *
     * @return void
     */
    public function test_show_inventory_item()
    {
        $inventoryItem = InventoryItem::factory()->create();
        $user          = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(
            route('inventory.items.show', $inventoryItem->id)
        );

        $response->assertStatus(200)->assertJsonStructure([
            'id',
            'sku',
            'name',
            'description',
            'unit_of_measure',
            'price',
            'location',
            'status',
            'image',
            'notes',
            'created_at',
            'updated_at',
        ]);
    }

    public function test_update_inventory_item()
    {
        $inventoryItem = InventoryItem::factory()->create();
        $user          = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson(
            route('inventory.items.update', $inventoryItem->id),
            [
                'sku'             => 'SKU-123-UPDATED',
                'name'            => 'Test Item Updated',
                'description'     => 'Test Description Updated',
                'unit_of_measure' => 'PCS_UPDATED',
                'price'           => 999999,
                'location'        => 'A1-UPDATED',
                'status'          => 'ACTIVE-UPDATED',
                'notes'           => 'Test Notes Updated',
            ]
        );

        $response->assertStatus(200)->assertJsonStructure([
            'id',
            'sku',
            'name',
            'description',
            'unit_of_measure',
            'price',
            'location',
            'status',
            'notes',
            'created_at',
            'updated_at',
        ]);

        $this->assertDatabaseHas('inventory_items', [
            'id'              => $inventoryItem->id,
            'sku'             => 'SKU-123-UPDATED',
            'name'            => 'Test Item Updated',
            'description'     => 'Test Description Updated',
            'unit_of_measure' => 'PCS_UPDATED',
            'price'           => 999999,
            'location'        => 'A1-UPDATED',
            'status'          => 'ACTIVE-UPDATED',
            'notes'           => 'Test Notes Updated',
        ]);
    }

    /**
     * Test delete inventory item
     *
     * @return void
     */
    public function test_delete_inventory_item()
    {
        $inventoryItem = InventoryItem::factory()->create();
        $user          = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson(
            route('inventory.items.destroy', $inventoryItem->id)
        );

        $response->assertStatus(204);

        $this->assertDatabaseMissing('inventory_items', [
            'id' => $inventoryItem->id,
        ]);
    }
}
