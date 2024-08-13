<?php

namespace Modules\Inventory\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Inventory\Models\InventoryItem;
use Tests\TestCase;

class InventoryStockTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test stock in inventory item.
     *
     * @return void
     */
    public function test_stock_in_inventory_item(): void
    {
        $inventoryItem = InventoryItem::factory()->create();
        $user          = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(
            route('inventory.stocks.update', [
                'itemId'   => $inventoryItem->id,
                'quantity' => 10,
                'type'     => 'STOCK_IN',
            ])
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('inventory_stocks', [
            'inventory_item_id' => $inventoryItem->id,
            'current'           => 10,
        ]);
        $this->assertDatabaseHas('inventory_stock_histories', [
            'inventory_stock_id' => $inventoryItem->inventoryStock->id,
            'quantity'           => 10,
            'type'               => 'STOCK_IN',
            'system'             => 'INVENTORY',
        ]);
    }

    /**
     * Test stock out inventory item.
     *
     * @return void
     */
    public function test_stock_out_inventory_item(): void
    {
        $inventoryItem = InventoryItem::factory()->create();
        $user          = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(
            route('inventory.stocks.update', [
                'itemId'   => $inventoryItem->id,
                'quantity' => 10,
                'type'     => 'STOCK_OUT',
            ])
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('inventory_stocks', [
            'inventory_item_id' => $inventoryItem->id,
            'current'           => -10,
        ]);
        $this->assertDatabaseHas('inventory_stock_histories', [
            'inventory_stock_id' => $inventoryItem->inventoryStock->id,
            'quantity'           => 10,
            'type'               => 'STOCK_OUT',
            'system'             => 'INVENTORY',
        ]);
    }
}
