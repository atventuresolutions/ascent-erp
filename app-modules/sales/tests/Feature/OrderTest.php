<?php

namespace Modules\Sales\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Sales\Enums\OrderStatus;
use Modules\Sales\Models\Order;
use Modules\Sales\Tests\SalesServiceProviderTest as TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test list orders.
     *
     * @return void
     */
    public function test_list_orders()
    {
        Order::factory()
            ->hasOrderItems(3)
            ->count(5)
            ->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('sales.orders.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'customer_id',
                        'status',
                        'total',
                        'discount',
                        'tax',
                        'shipping',
                        'grand_total',
                        'notes',
                        'created_at',
                        'updated_at',
                        'order_items' => [
                            '*' => [
                                'id',
                                'order_id',
                                'inventory_item_id',
                                'sku',
                                'name',
                                'price',
                                'quantity',
                                'created_at',
                                'updated_at',
                            ],
                        ],
                    ],
                ],
            ]);

        $this->assertCount(5, $response['data']);
    }

    /**
     * Test create order.
     *
     * @return void
     */
    public function test_create_order()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('sales.orders.store'), [
            'status' => OrderStatus::PENDING,
            'discount' => 0,
            'tax' => 0,
            'shipping' => 0,
            'notes' => 'This notes is for testing purpose.',
            'items' => [
                [
                    'sku' => 'SKU0001',
                    'name' => 'Item 1',
                    'price' => 100,
                    'quantity' => 2,
                ],
                [
                    'sku' => 'SKU0002',
                    'name' => 'Item 2',
                    'price' => 200,
                    'quantity' => 3,
                ],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'customer_id',
                'status',
                'total',
                'discount',
                'tax',
                'shipping',
                'grand_total',
                'notes',
                'created_at',
                'updated_at',
                'order_items' => [
                    '*' => [
                        'id',
                        'order_id',
                        'inventory_item_id',
                        'sku',
                        'name',
                        'price',
                        'quantity',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 2);
    }

    /**
     * Test show order.
     *
     * @return void
     */
    public function test_show_order()
    {
        $order = Order::factory()
            ->hasOrderItems(3)
            ->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('sales.orders.show', $order->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'customer_id',
                'status',
                'total',
                'discount',
                'tax',
                'shipping',
                'grand_total',
                'notes',
                'created_at',
                'updated_at',
                'order_items' => [
                    '*' => [
                        'id',
                        'order_id',
                        'inventory_item_id',
                        'sku',
                        'name',
                        'price',
                        'quantity',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);

        $this->assertDatabaseCount('orders', 1);
    }

    /**
     * Test update order.
     *
     * @return void
     */
    public function test_update_order()
    {
        $order = Order::factory()
            ->hasOrderItems(3)
            ->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson(route('sales.orders.show', $order->id), [
            'status' => OrderStatus::CANCELLED,
            'notes' => 'This notes is for testing purpose.',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'customer_id',
                'status',
                'total',
                'discount',
                'tax',
                'shipping',
                'grand_total',
                'notes',
                'created_at',
                'updated_at',
                'order_items' => [
                    '*' => [
                        'id',
                        'order_id',
                        'inventory_item_id',
                        'sku',
                        'name',
                        'price',
                        'quantity',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::CANCELLED,
            'notes' => 'This notes is for testing purpose.',
        ]);
    }

    /**
     * Test delete order.
     *
     * @return void
     */
    public function test_delete_order()
    {
        $order = Order::factory()
            ->hasOrderItems(3)
            ->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson(route('sales.orders.destroy', $order->id));

        $response->assertStatus(204);

        $this->assertDatabaseCount('orders', 0);
    }
}
