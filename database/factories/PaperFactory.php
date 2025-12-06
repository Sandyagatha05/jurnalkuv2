<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paper>
 */
class PaperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $submittedDate = fake()->dateTimeBetween('-1 year', 'now');
        
        return [
            'title' => rtrim(fake()->sentence(6), '.'),
            'abstract' => fake()->paragraphs(3, true),
            'keywords' => implode(', ', fake()->words(5)),
            'doi' => '10.1000/' . fake()->unique()->bothify('????-####'),
            'file_path' => 'papers/' . fake()->uuid() . '.pdf',
            'original_filename' => fake()->word() . '_paper.pdf',
            'status' => 'submitted',
            'author_id' => null, // akan diisi di seeder
            'issue_id' => null, // akan diisi di seeder
            'page_from' => fake()->numberBetween(1, 100),
            'page_to' => fake()->numberBetween(101, 300),
            'submitted_at' => $submittedDate,
            'reviewed_at' => fake()->dateTimeBetween($submittedDate, 'now'),
            'published_at' => null,
            'revision_count' => 0,
        ];
    }

    /**
     * State for submitted papers.
     */
    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'submitted',
        ]);
    }

    /**
     * State for under review papers.
     */
    public function underReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'under_review',
        ]);
    }

    /**
     * State for accepted papers.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'accepted',
        ]);
    }

    /**
     * State for published papers.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    /**
     * State for rejected papers.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }

    /**
     * State for minor revision papers.
     */
    public function minorRevision(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'revision_minor',
            'revision_count' => fake()->numberBetween(1, 3),
        ]);
    }

    /**
     * State for major revision papers.
     */
    public function majorRevision(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'revision_major',
            'revision_count' => fake()->numberBetween(1, 5),
        ]);
    }
}