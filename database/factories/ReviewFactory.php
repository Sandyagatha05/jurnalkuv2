<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $recommendation = fake()->randomElement(['accept', 'minor_revision', 'major_revision', 'reject']);
        
        return [
            'assignment_id' => null, // akan diisi di seeder
            'comments_to_editor' => fake()->paragraphs(3, true),
            'comments_to_author' => fake()->paragraphs(2, true),
            'recommendation' => $recommendation,
            'attachment_path' => fake()->boolean(20) ? 'reviews/' . fake()->uuid() . '.pdf' : null,
            'originality_score' => fake()->numberBetween(1, 5),
            'contribution_score' => fake()->numberBetween(1, 5),
            'clarity_score' => fake()->numberBetween(1, 5),
            'methodology_score' => fake()->numberBetween(1, 5),
            'overall_score' => fake()->numberBetween(1, 5),
            'is_confidential' => fake()->boolean(70),
            'reviewed_at' => fake()->dateTimeBetween('-2 months', 'now'),
        ];
    }

    /**
     * State for accept recommendation.
     */
    public function accept(): static
    {
        return $this->state(fn (array $attributes) => [
            'recommendation' => 'accept',
            'originality_score' => fake()->numberBetween(4, 5),
            'overall_score' => fake()->numberBetween(4, 5),
        ]);
    }

    /**
     * State for minor revision recommendation.
     */
    public function minorRevision(): static
    {
        return $this->state(fn (array $attributes) => [
            'recommendation' => 'minor_revision',
            'originality_score' => fake()->numberBetween(3, 4),
            'overall_score' => fake()->numberBetween(3, 4),
        ]);
    }

    /**
     * State for major revision recommendation.
     */
    public function majorRevision(): static
    {
        return $this->state(fn (array $attributes) => [
            'recommendation' => 'major_revision',
            'originality_score' => fake()->numberBetween(2, 3),
            'overall_score' => fake()->numberBetween(2, 3),
        ]);
    }

    /**
     * State for reject recommendation.
     */
    public function reject(): static
    {
        return $this->state(fn (array $attributes) => [
            'recommendation' => 'reject',
            'originality_score' => fake()->numberBetween(1, 2),
            'overall_score' => fake()->numberBetween(1, 2),
        ]);
    }
}