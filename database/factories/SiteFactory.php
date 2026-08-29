<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Site>
 */
class SiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'name' => fake()->city().' — '.fake()->streetSuffix(),
            'address' => fake()->streetAddress(),
            'timezone' => 'Africa/Lubumbashi',
            'active' => true,
            'latitude' => fake()->latitude(-11.85, -11.55),
            'longitude' => fake()->longitude(27.25, 27.65),
        ];
    }
}
