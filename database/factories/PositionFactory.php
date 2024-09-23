<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     *
     * ## Default Attributes
     * - **advertising_start_date**: Random date.
     * - **advertising_end_date**: Random date.
     * - **title**: Random job title.
     * - **description**: Random job description.
     * - **keywords**: Random keywords for the position.
     * - **min_salary**: Random minimum salary.
     * - **max_salary**: Random maximum salary.
     * - **currency**: Fixed value 'AUD'.
     * - **benefits**: Random benefits description.
     * - **requirements**: Random requirements description.
     * - **position_type**: Random position type (permanent, contract, etc.).
     * - **company_id**: Foreign key to the Company model.
     * - **user_id**: Foreign key to the User model.
     * - **created_at**: Current timestamp.
     * - **updated_at**: Current timestamp.
     */
    public function definition()
    {
        return [
            'advertising_start_date' => $this->faker->date,
            'advertising_end_date' => $this->faker->date,
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->paragraph,
            'keywords' => $this->faker->words(3, true),
            'min_salary' => $this->faker->randomFloat(2, 50000, 100000),
            'max_salary' => $this->faker->randomFloat(2, 100000, 150000),
            'currency' => 'AUD',
            'benefits' => $this->faker->paragraph,
            'requirements' => $this->faker->paragraph,
            'position_type' => $this->faker->randomElement(['permanent', 'contract', 'part-time', 'casual', 'internship']),
            'company_id' => Company::factory(),
            'user_id' => User::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
