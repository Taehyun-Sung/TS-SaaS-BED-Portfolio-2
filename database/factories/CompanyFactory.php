<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     *
     * ## Default Attributes
     * - **name**: Random company name.
     * - **city**: Random city name.
     * - **state**: Random state name.
     * - **country**: Random country name.
     * - **logo**: URL to a random image (optional).
     * - **created_at**: Current timestamp.
     * - **updated_at**: Current timestamp.
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'logo' => $this->faker->imageUrl(), // Optional field
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
