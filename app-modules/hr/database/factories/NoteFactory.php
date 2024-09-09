<?php

namespace Modules\Hr\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Hr\Models\Note;

/**
 * @extends Factory<Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type'    => $this->faker->randomElement(['EMPLOYMENT', 'COMPENSATION', 'REVIEWS', 'DOCUMENTS', 'OTHERS']),
            'title'   => $this->faker->sentence,
            'content' => $this->faker->paragraph,
        ];
    }
}
