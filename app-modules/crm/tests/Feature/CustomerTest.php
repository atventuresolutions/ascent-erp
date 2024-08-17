<?php

namespace Modules\Crm\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Modules\Crm\Models\Customer;
use Modules\Crm\Tests\CrmServiceProviderTest as TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test customer list
     * @return void
     */
    public function test_list_customers() {
        Customer::factory()->count(5)->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('crm.customers.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'reference',
                        'firstname',
                        'lastname',
                        'mobile_number',
                        'telephone_number',
                        'email',
                        'address',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);

        $this->assertCount(5, $response['data']);
        $this->assertDatabaseCount('customers', 5);
    }

    /*
     * Test customer create
     * @return void
     */
    public function test_create_customer()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('crm.customers.store', [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'mobile_number' => $this->faker->phoneNumber,
            'telephone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->address,
        ]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                    'id',
                    'reference',
                    'firstname',
                    'lastname',
                    'mobile_number',
                    'telephone_number',
                    'email',
                    'address',
                    'created_at',
                    'updated_at',
            ]);

        $this->assertDatabaseCount('customers', 1);
    }

    /**
     * Test customer show
     * @return void
     */
    public function test_show_customer()
    {
        $customer = Customer::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('crm.customers.show', $customer->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'reference',
                'firstname',
                'lastname',
                'mobile_number',
                'telephone_number',
                'email',
                'address',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * Test customer update
     * @return void
     */
    public function test_update_customer()
    {
        $customer = Customer::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson(route('crm.customers.update', $customer->id), [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'mobile_number' => $this->faker->phoneNumber,
            'telephone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->address,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'reference',
                'firstname',
                'lastname',
                'mobile_number',
                'telephone_number',
                'email',
                'address',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'firstname' => $response['firstname'],
            'lastname' => $response['lastname'],
            'mobile_number' => $response['mobile_number'],
            'telephone_number' => $response['telephone_number'],
            'email' => $response['email'],
            'address' => $response['address'],
        ]);
    }

    /**
     * Test customer delete
     * @return void
     */
    public function test_delete_customer()
    {
        $customer = Customer::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson(route('crm.customers.destroy', $customer->id));

        $response->assertStatus(204);

        $this->assertDatabaseCount('customers', 0);
    }
}
