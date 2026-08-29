<?php

namespace Database\Factories;

use App\Models\Camera;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Camera>
 */
class CameraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'site_id' => Site::factory(),
            'name' => 'Caméra '.fake()->unique()->numerify('###'),
            'code' => fake()->unique()->bothify('CAM-####'),
            'stream_url_encrypted' => 'encrypted-placeholder',
            'protocol' => 'rtsp',
            'latitude' => fake()->latitude(-11.85, -11.55),
            'longitude' => fake()->longitude(27.25, 27.65),
            'direction_degrees' => fake()->randomFloat(2, 0, 359),
            'status' => fake()->randomElement(['online', 'online', 'online', 'offline']),
            'capabilities' => ['person', 'vehicle', 'plate'],
            'last_seen_at' => now()->subMinutes(fake()->numberBetween(0, 30)),
        ];
    }
}
