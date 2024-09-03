<?php

namespace Modules\Hr\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Hr\Models\Addition;
use Modules\Hr\Models\Model;

/**
 * @extends Factory<Model>
 */
class EmployeeAdditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $additionIds = Addition::pluck('id')->toArray();

        return [
            'addition_id' => $this->faker->randomElement($additionIds),
            'type'        => $this->faker->randomElement(['FIXED', 'PERCENTAGE']),
            'amount'      => $this->faker->randomFloat(2, 0, 10000),
            'start_date'  => $this->faker->date(),
            'end_date'    => $this->faker->optional()->date(),
            'notes'       => $this->faker->text(),
        ];
    }
}
