<?php

namespace Modules\Hr\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Hr\Models\Model;

/**
 * @extends Factory<Model>
 */
class TimekeepingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date'            => $this->faker->date(),
            'first_time_in'   => '08:00:00',
            'first_time_out'  => '12:00:00',
            'second_time_in'  => '13:00:00',
            'second_time_out' => '17:00:00',
            'status'          => 'PENDING',
            'notes'           => $this->faker->sentence,

            'break_start_time' => '12:00:00',
            'break_end_time'   => '13:00:00',
            'total_rendered'   => 8,
            'total_overtime'   => 0,
            'total_late'       => 0,
            'total_undertime'  => 0,
        ];
    }
}
