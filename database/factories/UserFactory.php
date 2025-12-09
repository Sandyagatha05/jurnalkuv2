<?php

namespace Database\Factories;

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
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password123'),
            'institution' => fake()->randomElement([
                'University of Science',
                'Tech Institute',
                'Research University',
                'Science Academy',
                'Engineering College',
                'Medical University',
                'Business School',
                'Arts Institute',
            ]),
            'department' => fake()->randomElement([
                'Computer Science',
                'Information Technology',
                'Engineering',
                'Physics',
                'Mathematics',
                'Biology',
                'Chemistry',
                'Medicine',
                'Business',
                'Arts',
            ]),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'biography' => fake()->paragraphs(3, true),
            'orcid_id' => '0000-0000-0000-' . fake()->randomNumber(4, true),
            'google_scholar_id' => fake()->userName(),
            'scopus_id' => fake()->randomNumber(9, true),
            'photo' => null,
            'remember_token' => Str::random(10),
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

    /**
     * State for admin users.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Admin ' . fake()->firstName(),
            'email' => 'admin' . fake()->randomNumber(3) . '@jurnalku.com',
        ]);
    }

    /**
     * State for editor users.
     */
    public function editor(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Editor ' . fake()->firstName() . ' ' . fake()->lastName(),
            'email' => 'editor' . fake()->randomNumber(3) . '@jurnalku.com',
        ]);
    }

    /**
     * State for reviewer users.
     */
    public function reviewer(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Reviewer ' . fake()->firstName() . ' ' . fake()->lastName(),
            'email' => 'reviewer' . fake()->randomNumber(3) . '@jurnalku.com',
        ]);
    }

    /**
     * State for author users.
     */
    public function author(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Author ' . fake()->firstName() . ' ' . fake()->lastName(),
            'email' => 'author' . fake()->randomNumber(3) . '@jurnalku.com',
        ]);
    }
}