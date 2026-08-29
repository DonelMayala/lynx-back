<?php

namespace Database\Factories;

use App\Models\Incident;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Incident>
 */
class IncidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'alert_id' => null,
            'reference_number' => fake()->unique()->bothify('INC-####-##'),
            'title' => fake()->sentence(5),
            'description' => fake()->paragraph(),
            'priority' => fake()->randomElement(['medium', 'high', 'critical']),
            'status' => 'open',
            'assigned_to' => null,
            'opened_at' => now()->subHours(fake()->numberBetween(1, 48)),
        ];
    }
}
