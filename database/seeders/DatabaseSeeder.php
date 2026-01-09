<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Issue;
use App\Models\Paper;
use App\Models\Editorial;
use App\Models\ReviewAssignment;
use App\Models\Review;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // ========== 0. CLEAR EXISTING DATA ==========
        // Truncate all tables to reset auto-increment
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Review::truncate();
        ReviewAssignment::truncate();
        Editorial::truncate();
        Paper::truncate();
        Issue::truncate();
        User::truncate();
        Role::truncate();
        Permission::truncate();
        \DB::table('model_has_roles')->truncate();
        \DB::table('model_has_permissions')->truncate();
        \DB::table('role_has_permissions')->truncate();
        
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ========== 1. CREATE ROLES ==========
        $roles = ['admin', 'editor', 'reviewer', 'author'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // ========== 2. CREATE USERS ==========
        
        // Create Admin
        $admin = User::create([
            'name' => 'Anderies',
            'email' => 'ander@jurnalku.com',
            'password' => bcrypt('password123'),
        ]);
        $admin->assignRole('admin');

        // Create Editor
        $editor = User::create([
            'name' => 'Matthew Nathanael',
            'email' => 'matthew@jurnalku.com',
            'password' => bcrypt('password123'),
        ]);
        $editor->assignRole('editor');

        // Create Reviewers
        $reviewer1 = User::create([
            'name' => 'Nathanael Hosea',
            'email' => 'hosea@jurnalku.com',
            'password' => bcrypt('password123'),
        ]);
        $reviewer1->assignRole('reviewer');

        $reviewer2 = User::create([
            'name' => 'Sandy Agatha',
            'email' => 'sandy@jurnalku.com',
            'password' => bcrypt('password123'),
        ]);
        $reviewer2->assignRole('reviewer');

        // Create Authors
        $author1 = User::create([
            'name' => 'Nicky Marcellino',
            'email' => 'nicky@jurnalku.com',
            'password' => bcrypt('password123'),
        ]);
        $author1->assignRole('author');

        $author2 = User::create([
            'name' => 'Vania Oriana',
            'email' => 'vania@jurnalku.com',
            'password' => bcrypt('password123'),
        ]);
        $author2->assignRole('author');

        // Collect users for later use
        $editors = collect([$editor]);
        $reviewers = collect([$reviewer1, $reviewer2]);
        $authors = collect([$author1, $author2]);

        // ========== 3. CREATE ISSUES ==========
        $issues = collect();

        // Create issues manually to avoid duplicate unique constraint
        for ($volume = 1; $volume <= 3; $volume++) {
            for ($number = 1; $number <= 4; $number++) {
                $year = 2019 + $volume;
                
                $issue = Issue::create([
                    'volume' => $volume,
                    'number' => $number,
                    'year' => $year,
                    'title' => "Journal Research - Vol. {$volume}, No. {$number} ({$year})",
                    'description' => fake()->paragraphs(2, true),
                    'published_date' => fake()->dateTimeBetween("{$year}-01-01", "{$year}-12-31"),
                    'status' => fake()->randomElement(['draft', 'published', 'archived']),
                    'editor_id' => $editors->random()->id,
                ]);
                
                $issues->push($issue);
            }
        }

        // ========== 4. CREATE EDITORIALS ==========
        foreach ($issues as $issue) {
            Editorial::create([
                'title' => 'Editorial: ' . $issue->title,
                'content' => fake()->paragraphs(10, true),
                'issue_id' => $issue->id,
                'author_id' => $issue->editor_id,
                'is_published' => $issue->status === 'published',
                'published_date' => $issue->published_date,
            ]);
        }

        // ========== 5. CREATE PAPERS ==========
        $papers = collect();
        
        for ($i = 0; $i < 50; $i++) {
            $paper = Paper::create([
                'title' => rtrim(fake()->sentence(6), '.'),
                'abstract' => fake()->paragraphs(3, true),
                'keywords' => implode(', ', fake()->words(5)),
                'doi' => '10.1000/' . fake()->unique()->bothify('????-####'),
                'file_path' => 'papers/' . fake()->uuid() . '.pdf',
                'original_filename' => fake()->word() . '_paper.pdf',
                'status' => 'submitted',
                'author_id' => $authors->random()->id,
                'issue_id' => null,
                'page_from' => fake()->numberBetween(1, 100),
                'page_to' => fake()->numberBetween(101, 300),
                'submitted_at' => fake()->dateTimeBetween('-1 year', 'now'),
                'reviewed_at' => null,
                'published_at' => null,
                'revision_count' => 0,
            ]);
            
            $papers->push($paper);
        }

        // Assign papers to published issues
        $publishedIssues = $issues->where('status', 'published');
        $availablePapers = $papers->shuffle();
        $paperIndex = 0;
        
        // Ensure each published issue has at least 1 paper
        foreach ($publishedIssues as $issue) {
            if ($paperIndex < $availablePapers->count()) {
                $availablePapers[$paperIndex]->update([
                    'issue_id' => $issue->id,
                    'status' => 'published',
                    'published_at' => $issue->published_date,
                ]);
                $paperIndex++;
            }
        }
        
        // Assign remaining papers randomly to published issues
        $remainingToPublish = min(10, $availablePapers->count() - $paperIndex);
        for ($i = 0; $i < $remainingToPublish; $i++) {
            if ($paperIndex < $availablePapers->count() && $publishedIssues->isNotEmpty()) {
                $availablePapers[$paperIndex]->update([
                    'issue_id' => $publishedIssues->random()->id,
                    'status' => 'published',
                    'published_at' => now()->subDays(fake()->numberBetween(1, 180)),
                ]);
                $paperIndex++;
            }
        }

        // Set status for other papers
        $statusCounts = [
            'submitted' => 10,
            'under_review' => 10,
            'accepted' => 8,
            'revision_minor' => 4,
            'revision_major' => 3,
        ];
        
        $remainingPapers = $papers->where('status', 'submitted');
        $index = 0;
        
        foreach ($statusCounts as $status => $count) {
            for ($j = 0; $j < $count; $j++) {
                if (isset($remainingPapers[$index])) {
                    $remainingPapers[$index]->update(['status' => $status]);
                    $index++;
                }
            }
        }

        // ========== 6. CREATE REVIEW ASSIGNMENTS ==========
        $reviewAssignments = [];
        
        // For papers under review or revision
        $papersToReview = $papers->filter(function($paper) {
            return in_array($paper->status, ['under_review', 'revision_minor', 'revision_major']);
        });
        
        foreach ($papersToReview as $paper) {
            // Assign 1-2 reviewers per paper (since we only have 2 reviewers)
            $numReviewers = fake()->numberBetween(1, 2);
            $assignedReviewers = $reviewers->random($numReviewers);
            
            foreach ($assignedReviewers as $reviewer) {
                $assignment = ReviewAssignment::create([
                    'paper_id' => $paper->id,
                    'reviewer_id' => $reviewer->id,
                    'assigned_by' => $editors->random()->id,
                    'status' => fake()->randomElement(['pending', 'completed']),
                    'assigned_date' => fake()->dateTimeBetween('-3 months', 'now'),
                    'due_date' => fake()->dateTimeBetween('+1 week', '+1 month'),
                    'completed_date' => null,
                    'editor_notes' => fake()->boolean(30) ? fake()->paragraph() : null,
                ]);
                
                $reviewAssignments[] = $assignment;
            }
        }

        // ========== 7. CREATE REVIEWS ==========
        foreach ($reviewAssignments as $assignment) {
            if ($assignment->status === 'completed') {
                Review::create([
                    'assignment_id' => $assignment->id,
                    'comments_to_editor' => fake()->paragraphs(3, true),
                    'comments_to_author' => fake()->paragraphs(2, true),
                    'recommendation' => fake()->randomElement(['accept', 'minor_revision', 'major_revision', 'reject']),
                    'attachment_path' => fake()->boolean(20) ? 'reviews/' . fake()->uuid() . '.pdf' : null,
                    'originality_score' => fake()->numberBetween(1, 5),
                    'contribution_score' => fake()->numberBetween(1, 5),
                    'clarity_score' => fake()->numberBetween(1, 5),
                    'methodology_score' => fake()->numberBetween(1, 5),
                    'overall_score' => fake()->numberBetween(1, 5),
                    'is_confidential' => fake()->boolean(70),
                    'reviewed_at' => fake()->dateTimeBetween('-2 months', 'now'),
                ]);
            }
        }

        echo "✅ Database seeded successfully!\n";
        echo "Total Users: " . User::count() . "\n";
        echo "Total Issues: " . Issue::count() . "\n";
        echo "Total Papers: " . Paper::count() . "\n";
        echo "Total Review Assignments: " . ReviewAssignment::count() . "\n";
        echo "Total Reviews: " . Review::count() . "\n";
        
        echo "\n📋 Login Credentials:\n";
        echo "Admin: ander@jurnalku.com / password123\n";
        echo "Editor: matthew@jurnalku.com / password123\n";
        echo "Reviewers: hosea@jurnalku.com, sandy@jurnalku.com / password123\n";
        echo "Authors: nicky@jurnalku.com, vania@jurnalku.com / password123\n";
    }
}