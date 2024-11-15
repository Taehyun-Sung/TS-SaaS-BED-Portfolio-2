<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'city_id' => $this->faker->numberBetween(1, 100),
            'state_id' => $this->faker->numberBetween(1, 50),
            'country_id' => $this->faker->numberBetween(1, 20),
            'logo' => null,
            'user_id' => $this->faker->numberBetween(1,100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
