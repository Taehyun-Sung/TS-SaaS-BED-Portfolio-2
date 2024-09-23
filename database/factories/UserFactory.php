<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     *
     * ## Default Attributes
     * - **nickname**: Random username.
     * - **given_name**: Random first name.
     * - **family_name**: Random last name.
     * - **email**: Unique random email.
     * - **password**: Hashed default password.
     * - **company_id**: Foreign key to the Company model.
     * - **user_type**: Random user type (client, staff, applicant).
     * - **status**: Random status (active, unconfirmed, etc.).
     * - **created_at**: Current timestamp.
     * - **updated_at**: Current timestamp.
     */
    public function definition()
    {
        return [
            'nickname' => $this->faker->userName,
            'given_name' => $this->faker->firstName,
            'family_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'), // or bcrypt('password')
            'company_id' => Company::factory(),
            'user_type' => $this->faker->randomElement(['client', 'staff', 'applicant']),
            'status' => $this->faker->randomElement(['active', 'unconfirmed', 'suspended', 'banned', 'unknown']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
