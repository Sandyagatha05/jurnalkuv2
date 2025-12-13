<?php

use App\Http\Controllers\Reviewer\AssignmentController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Reviewer Routes Group
Route::middleware(['auth', 'reviewer'])->prefix('reviewer')->name('reviewer.')->group(function () {
    
    // Reviewer Dashboard
    Route::get('/dashboard', function () {
        return view('reviewer.dashboard');
    })->name('dashboard');
    
    // Review Assignments
    Route::prefix('assignments')->name('assignments.')->group(function () {
        Route::get('/', [AssignmentController::class, 'index'])->name('index');
        Route::get('/pending', [AssignmentController::class, 'pending'])->name('pending');
        Route::get('/completed', [AssignmentController::class, 'completed'])->name('completed');
        Route::get('/overdue', [AssignmentController::class, 'overdue'])->name('overdue');
        Route::get('/{assignment}', [AssignmentController::class, 'show'])->name('show');
        
        // Accept/Decline Assignment
        Route::post('/{assignment}/accept', [AssignmentController::class, 'accept'])->name('accept');
        Route::post('/{assignment}/decline', [AssignmentController::class, 'decline'])->name('decline');
        
        // Submit Review
        Route::get('/{assignment}/review', [AssignmentController::class, 'review'])->name('review');
        Route::post('/{assignment}/review', [AssignmentController::class, 'submitReview'])->name('submit-review');

        // Save Draft (TAMBAHKAN INI)
        Route::post('/{assignment}/save-draft', [AssignmentController::class, 'saveDraft'])->name('save-draft');
        
        // View Paper
        Route::get('/{assignment}/paper', [AssignmentController::class, 'viewPaper'])->name('view-paper');
        
        // Download Paper
        Route::get('/{assignment}/download', [AssignmentController::class, 'downloadPaper'])->name('download-paper');
        
        // Request Extension
        Route::post('/{assignment}/request-extension', function ($assignmentId) {
            // TODO: Implement extension request
            return back()->with('success', 'Extension request sent to editor.');
        })->name('request-extension');
    });
    
    // My Reviews
    Route::get('/reviews', function () {
        $reviews = \App\Models\Review::whereHas('assignment', function($query) {
            $query->where('reviewer_id', auth()->id());
        })->with(['assignment.paper'])->paginate(15);
        
        return view('reviewer.reviews.index', compact('reviews'));
    })->name('reviews.index');
    
    Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    
    // Reviewer Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', function () {
            $user = auth()->user();
            $user->load(['reviewAssignments' => function($query) {
                $query->with(['paper', 'review'])->latest()->take(10);
            }]);
            return view('reviewer.profile.show', compact('user'));
        })->name('show');
        
        Route::get('/edit', function () {
            return view('reviewer.profile.edit');
        })->name('edit');
        
        Route::get('/availability', function () {
            return view('reviewer.profile.availability');
        })->name('availability');
        
        Route::post('/availability', function () {
            // TODO: Update reviewer availability
            return back()->with('success', 'Availability updated.');
        })->name('update-availability');
    });
    
    // Reviewer Statistics
    Route::get('/statistics', function () {
        $user = auth()->user();
        $stats = [
            'total_assignments' => $user->reviewAssignments()->count(),
            'pending_assignments' => $user->reviewAssignments()->where('status', 'pending')->count(),
            'completed_assignments' => $user->reviewAssignments()->where('status', 'completed')->count(),
            'avg_completion_time' => $user->reviewAssignments()
                ->where('status', 'completed')
                ->selectRaw('AVG(DATEDIFF(completed_date, assigned_date)) as avg_days')
                ->first()->avg_days ?? 0,
        ];
        
        return view('reviewer.statistics', compact('stats'));
    })->name('statistics');
    
    // Review Guidelines
    Route::get('/guidelines', function () {
        return view('reviewer.guidelines');
    })->name('guidelines');
    
    // Conflict of Interest
    Route::get('/conflict-of-interest', function () {
        return view('reviewer.conflict-of-interest');
    })->name('conflict-of-interest');
});