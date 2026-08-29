<?php

namespace Database\Factories;

use App\Models\Camera;
use App\Models\Detection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Detection>
 */
class DetectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'camera_id' => Camera::factory(),
            'recording_id' => null,
            'detection_type' => fake()->randomElement(['vehicle', 'person', 'crowd', 'restricted_area']),
            'confidence' => fake()->randomFloat(4, 0.72, 0.99),
            'detected_at' => now()->subMinutes(fake()->numberBetween(1, 120)),
            'bounding_box' => ['x' => 0.2, 'y' => 0.15, 'width' => 0.4, 'height' => 0.6],
            'model_metadata' => ['model' => 'lynx-detector', 'version' => '1.0'],
            'validation_status' => 'pending',
        ];
    }
}
