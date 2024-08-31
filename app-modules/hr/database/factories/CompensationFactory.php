<?php

namespace Modules\Hr\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Hr\Models\Compensation;

/**
 * @extends Factory<Compensation>
 */
class CompensationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'daily_rate'          => $this->faker->randomFloat(2, 100, 1000),
            'daily_working_hours' => $this->faker->numberBetween(1, 24),

            'overtime_multiplier'         => 10,
            'holiday_multiplier'          => 20,
            'special_holiday_multiplier'  => 10,

            'shift_start_time'    => '08:00',
            'shift_end_time'      => '17:00',
            'break_start_time'    => '12:00',
            'break_end_time'      => '13:00',
        ];
    }
}
