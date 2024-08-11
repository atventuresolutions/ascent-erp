<?php

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Inventory\Models\Model>
 */
class InventoryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku'             => Str::random(10),
            'name'            => $this->faker->name,
            'description'     => $this->faker->sentence,
            'unit_of_measure' => $this->faker->randomElement(
                ['KG', 'G', 'L', 'ML', 'PCS']
            ),
            'price'           => $this->faker->randomFloat(2, 0, 1000),
            'location'        => $this->faker->randomElement(
                ['A1', 'A2', 'B1', 'B2']
            ),
            'status'          => $this->faker->randomElement(
                ['ACTIVE', 'INACTIVE']
            ),
            'image'           => $this->faker->imageUrl(),
            'notes'           => $this->faker->sentence,
        ];
    }
}
