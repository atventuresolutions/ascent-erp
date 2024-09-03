<?php

namespace Modules\Hr\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Hr\Models\Deduction;
use Modules\Hr\Models\Model;

/**
 * @extends Factory<Model>
 */
class EmployeeDeductionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $deductionIds = Deduction::pluck('id')->toArray();

        return [
            'deduction_id' => $this->faker->randomElement($deductionIds),
            'type'        => $this->faker->randomElement(['FIXED', 'PERCENTAGE']),
            'amount'      => $this->faker->randomFloat(2, 0, 10000),
            'start_date'  => $this->faker->date(),
            'end_date'    => $this->faker->optional()->date(),
            'notes'       => $this->faker->text(),
        ];
    }
}
