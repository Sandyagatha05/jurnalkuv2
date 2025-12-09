<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Issue;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Issue>
 */
class IssueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate unique combination of volume, number, year
        static $combinations = [];
        
        do {
            $year = fake()->numberBetween(2020, 2024);
            $volume = floor($year - 2019);
            $number = fake()->numberBetween(1, 4);
            
            $key = "{$volume}-{$number}-{$year}";
        } while (in_array($key, $combinations));
        
        $combinations[] = $key;
        
        return [
            'volume' => $volume,
            'number' => $number,
            'year' => $year,
            'title' => $this->generateTitle($volume, $number, $year),
            'description' => fake()->paragraphs(2, true),
            'published_date' => fake()->dateTimeBetween("{$year}-01-01", "{$year}-12-31"),
            'status' => fake()->randomElement(['draft', 'published', 'archived']),
            'editor_id' => null,
        ];
    }

    /**
     * Generate unique title based on issue details.
     */
    private function generateTitle($volume, $number, $year): string
    {
        $themes = [
            'Advances in Computer Science',
            'Recent Developments in Engineering',
            'Innovations in Medical Research',
            'Breakthroughs in Physics',
            'Modern Approaches to Education',
            'Sustainable Development Studies',
            'Artificial Intelligence Applications',
            'Biotechnology Research',
            'Data Science Innovations',
            'Cybersecurity Challenges',
            'Renewable Energy Solutions',
            'Climate Change Research',
            'Digital Transformation',
            'Healthcare Technologies',
            'Space Exploration',
        ];
        
        $theme = $themes[($volume + $number + $year) % count($themes)];
        return "{$theme} - Volume {$volume}, Number {$number}";
    }

    /**
     * State for published issues.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    /**
     * State for draft issues.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    /**
     * Configure the factory to create issues in sequence.
     */
    public function configure()
    {
        return $this->sequence(
            ['volume' => 1, 'number' => 1, 'year' => 2020],
            ['volume' => 1, 'number' => 2, 'year' => 2020],
            ['volume' => 1, 'number' => 3, 'year' => 2020],
            ['volume' => 1, 'number' => 4, 'year' => 2020],
            ['volume' => 2, 'number' => 1, 'year' => 2021],
            ['volume' => 2, 'number' => 2, 'year' => 2021],
            ['volume' => 2, 'number' => 3, 'year' => 2021],
            ['volume' => 2, 'number' => 4, 'year' => 2021],
            ['volume' => 3, 'number' => 1, 'year' => 2022],
            ['volume' => 3, 'number' => 2, 'year' => 2022],
            ['volume' => 3, 'number' => 3, 'year' => 2022],
            ['volume' => 3, 'number' => 4, 'year' => 2022],
        );
    }
}