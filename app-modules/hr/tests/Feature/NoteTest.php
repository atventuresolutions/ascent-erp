<?php

namespace Modules\Hr\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Hr\Models\Employee;
use Modules\Hr\Models\Note;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    use RefreshDatabase;

    /**
     * Test employee notes list
     * @return void
     */
    public function test_list_employee_notes()
    {
        $user     = User::factory()->create();
        $employee = Employee::factory()
            ->has(
                Note::factory()
                    ->count(5)
                    ->state(['user_id' => $user->id])
            )->create();

        Sanctum::actingAs($user);
        $response = $this->getJson(route('employees.notes.index', [$employee->id]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'employee_id',
                        'type',
                        'title',
                        'content',
                        'file',
                        'user',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);

        $this->assertDatabaseCount('notes', 5);
    }

    /**
     * Test employee note create
     * @return void
     */
    public function test_create_employee_note()
    {
        $user     = User::factory()->create();
        $employee = Employee::factory()->create();

        Sanctum::actingAs($user);
        $response = $this->postJson(route('employees.notes.store', [$employee->id]), [
            'type'    => 'EMPLOYMENT',
            'title'   => 'Test title',
            'content' => 'Test content',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'user_id',
                'employee_id',
                'type',
                'title',
                'content',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('notes', [
            'employee_id' => $employee->id,
            'user_id'     => $user->id,
            'type'        => 'EMPLOYMENT',
            'title'       => 'Test title',
            'content'     => 'Test content',
        ]);
    }

    /**
     * Test employee note show
     * @return void
     */
    public function test_show_employee_note()
    {
        $user     = User::factory()->create();
        $employee = Employee::factory()->create();
        $note     = $employee->notes()->create([
            'user_id' => $user->id,
            'type'    => 'REVIEW',
            'title'   => 'Test title',
            'content' => 'Test content',
        ]);

        Sanctum::actingAs($user);
        $response = $this->getJson(route('notes.show', [$note->id]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'user_id',
                'employee_id',
                'type',
                'title',
                'content',
                'file',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('notes', [
            'employee_id' => $employee->id,
            'user_id'     => $user->id,
            'type'        => 'REVIEW',
            'title'       => 'Test title',
            'content'     => 'Test content',
        ]);
    }

    /**
     * Test employee note update
     * @return void
     */
    public function test_update_employee_note()
    {
        $user     = User::factory()->create();
        $employee = Employee::factory()->create();
        $note     = $employee->notes()->create([
            'user_id' => $user->id,
            'type'    => 'REVIEW',
            'title'   => 'Test title',
            'content' => 'Test content',
        ]);

        Sanctum::actingAs($user);
        $response = $this->putJson(route('notes.update', [$note->id]), [
            'type'    => 'REVIEW',
            'title'   => 'Test title updated',
            'content' => 'Test content updated',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'user_id',
                'employee_id',
                'type',
                'title',
                'content',
                'file',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('notes', [
            'employee_id' => $employee->id,
            'user_id'     => $user->id,
            'type'        => 'REVIEW',
            'title'       => 'Test title updated',
            'content'     => 'Test content updated',
        ]);
    }

    /**
     * Test employee note delete
     * @return void
     */
    public function test_delete_employee_note()
    {
        $user     = User::factory()->create();
        $employee = Employee::factory()->create();
        $note     = $employee->notes()->create([
            'user_id' => $user->id,
            'type'    => 'REVIEW',
            'title'   => 'Test title',
            'content' => 'Test content',
        ]);

        Sanctum::actingAs($user);
        $response = $this->deleteJson(route('notes.destroy', [$note->id]));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('notes', [
            'id' => $note->id,
        ]);
    }
}
