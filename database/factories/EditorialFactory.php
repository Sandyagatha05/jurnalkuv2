<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Editorial>
 */
class EditorialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement([
                'Welcome to Our Latest Issue',
                'Editor\'s Note: Looking Forward',
                'Introduction to Current Research',
                'Perspectives on Recent Developments',
                'Editorial: The Future of Our Field',
            ]),
            'content' => fake()->paragraphs(10, true),
            'author_id' => null, // akan diisi di seeder
            'issue_id' => null, // akan diisi di seeder
            'is_published' => fake()->boolean(80),
            'published_date' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * State for published editorials.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
        ]);
    }
}