<?php

namespace Modules\Sales\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Sales\Enums\TransactionStatus;
use Modules\Sales\Models\Transaction;
use Modules\Sales\Tests\SalesServiceProviderTest as TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test list transactions.
     *
     * @return void
     */
    public function test_list_transactions()
    {
        Transaction::factory()
            ->hasTransactionItems(3)
            ->count(5)
            ->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('sales.transactions.index'));

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
                        'transaction_items' => [
                            '*' => [
                                'id',
                                'transaction_id',
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
     * Test create transaction.
     *
     * @return void
     */
    public function test_create_transaction()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('sales.transactions.store'), [
            'status' => TransactionStatus::PENDING,
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
                'transaction_items' => [
                    '*' => [
                        'id',
                        'transaction_id',
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

        $this->assertDatabaseCount('transactions', 1);
        $this->assertDatabaseCount('transaction_items', 2);
    }

    /**
     * Test show transaction.
     *
     * @return void
     */
    public function test_show_transaction()
    {
        $transaction = Transaction::factory()
            ->hasTransactionItems(3)
            ->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('sales.transactions.show', $transaction->id));

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
                'transaction_items' => [
                    '*' => [
                        'id',
                        'transaction_id',
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

        $this->assertDatabaseCount('transactions', 1);
    }

    /**
     * Test update transaction.
     *
     * @return void
     */
    public function test_update_transaction()
    {
        $transaction = Transaction::factory()
            ->hasTransactionItems(3)
            ->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson(route('sales.transactions.show', $transaction->id), [
            'status' => TransactionStatus::CANCELLED,
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
                'transaction_items' => [
                    '*' => [
                        'id',
                        'transaction_id',
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

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => TransactionStatus::CANCELLED,
            'notes' => 'This notes is for testing purpose.',
        ]);
    }

    /**
     * Test delete transaction.
     *
     * @return void
     */
    public function test_delete_transaction()
    {
        $transaction = Transaction::factory()
            ->hasTransactionItems(3)
            ->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson(route('sales.transactions.destroy', $transaction->id));

        $response->assertStatus(204);

        $this->assertDatabaseCount('transactions', 0);
    }
}
