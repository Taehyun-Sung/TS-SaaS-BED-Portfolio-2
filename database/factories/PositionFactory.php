<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    protected  $model = Position::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'advertising_start_date' => $this->faker->date(),
            'advertising_end_date' => $this->faker->date(),
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(),
            'keywords' => implode(', ', $this->faker->words(5)),
            'min_salary' => $this->faker->numberBetween(50000, 100000),
            'max_salary' => $this->faker->numberBetween(100000, 200000),
            'salary_currency' => 'AUD',
            'company_id' => Company::factory(),
            'user_id' => User::factory(),
            'benefits' => $this->faker->sentence(),
            'requirements' => $this->faker->sentence(),
            'position_type' => $this->faker->randomElement(['permanent', 'contract', 'part-time', 'casual']),
        ];
    }
}
