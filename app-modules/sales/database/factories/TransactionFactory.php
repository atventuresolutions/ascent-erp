<?php

namespace Modules\Sales\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Sales\Enums\TransactionStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Sales\Models\Model>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement([
                TransactionStatus::PENDING,
                TransactionStatus::PROCESSING,
                TransactionStatus::COMPLETED,
                TransactionStatus::CANCELLED
            ]),
            'total' => $this->faker->randomFloat(2, 100, 1000),
            'discount' => $this->faker->randomFloat(2, 0, 100),
            'tax' => $this->faker->randomFloat(2, 0, 100),
            'shipping' => $this->faker->randomFloat(2, 0, 100),
            'grand_total' => $this->faker->randomFloat(2, 100, 1000),
            'notes' => $this->faker->text,
        ];
    }
}
