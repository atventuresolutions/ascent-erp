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
            'emergency_contact_name' => $this->faker->name,
            'emergency_contact_number' => $this->faker->phoneNumber,
            'emergency_contact_relationship' => $this->faker->word,
            'job_title' => $this->faker->word,
            'department' => $this->faker->word,
            'employment_status' => 'ACTIVE',
            'date_hired' => $this->faker->date,
            'date_regularized' => $this->faker->date,
            'date_resigned' => $this->faker->date,
            'date_terminated' => $this->faker->date,
        ];
    }
}
