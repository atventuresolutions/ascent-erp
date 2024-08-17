<?php

namespace Modules\Hr\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Hr\Models\Model>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'mobile_number' => $this->faker->phoneNumber,
            'telephone_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'birthday' => $this->faker->date,
        ];
    }
}
