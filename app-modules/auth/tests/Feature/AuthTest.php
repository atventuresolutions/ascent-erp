<?php

namespace Modules\Auth\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Tests\AuthServiceProviderTest as TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test auth login
     *
     * @return void
     */
    public function test_auth_login(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('auth.login'), [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)->assertJsonStructure(
            ['message', 'data' => ['token']]
        );
    }

    /**
     * Test auth logout
     *
     * @return void
     */
    public function test_auth_logout(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $this->postJson(route('auth.login'), [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response = $this->postJson(route('auth.logout'));

        $response->assertStatus(200)->assertJsonStructure(
            ['message']
        );
    }
}
