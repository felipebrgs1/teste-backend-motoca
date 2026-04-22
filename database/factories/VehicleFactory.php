<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['car', 'motorcycle']),
            'brand' => 'Honda',
            'model' => $this->faker->randomElement(['Civic', 'City', 'HR-V', 'CR-V', 'Fit', 'Accord', 'CG 160', 'CB 500', 'XRE 300', 'CBR 1000RR']),
            'year' => $this->faker->numberBetween(2000, 2026),
            'price' => $this->faker->randomFloat(2, 15000, 300000),
            'color' => $this->faker->colorName(),
            'mileage' => $this->faker->numberBetween(0, 200000),
        ];
    }
}
