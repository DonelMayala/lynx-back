<?php

namespace Database\Factories;

use App\Models\Alert;
use App\Models\Detection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Alert>
 */
class AlertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'alert_rule_id' => null,
            'detection_id' => Detection::factory(),
            'severity' => fake()->randomElement(['medium', 'high', 'critical']),
            'status' => 'new',
            'latitude' => fake()->latitude(-11.85, -11.55),
            'longitude' => fake()->longitude(27.25, 27.65),
            'triggered_at' => now()->subMinutes(fake()->numberBetween(1, 90)),
        ];
    }
}
