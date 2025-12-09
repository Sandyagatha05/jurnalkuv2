<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReviewAssignment>
 */
class ReviewAssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $assignedDate = fake()->dateTimeBetween('-3 months', 'now');
        $dueDate = fake()->dateTimeBetween($assignedDate, '+1 month');
        
        return [
            'paper_id' => null, // akan diisi di seeder
            'reviewer_id' => null, // akan diisi di seeder
            'assigned_by' => null, // akan diisi di seeder
            'status' => 'pending',
            'assigned_date' => $assignedDate,
            'due_date' => $dueDate,
            'completed_date' => null,
            'editor_notes' => fake()->boolean(30) ? fake()->paragraph() : null,
        ];
    }

    /**
     * State for pending assignments.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'completed_date' => null,
        ]);
    }

    /**
     * State for completed assignments.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'completed_date' => fake()->dateTimeBetween($attributes['assigned_date'], 'now'),
        ]);
    }

    /**
     * State for accepted assignments.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'accepted',
        ]);
    }

    /**
     * State for declined assignments.
     */
    public function declined(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'declined',
        ]);
    }
}