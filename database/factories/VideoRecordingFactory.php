<?php

namespace Database\Factories;

use App\Models\Camera;
use App\Models\VideoRecording;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VideoRecording>
 */
class VideoRecordingFactory extends Factory
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
            'storage_key' => 'recordings/'.fake()->unique()->uuid().'.mp4',
            'started_at' => now()->subMinutes(8),
            'ended_at' => now()->subMinutes(7),
            'duration_seconds' => 60,
            'size_bytes' => fake()->numberBetween(5_000_000, 40_000_000),
            'checksum' => fake()->sha256(),
            'retention_until' => now()->addDays(30),
            'status' => 'available',
        ];
    }
}
