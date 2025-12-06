<?php

use App\Http\Controllers\Reviewer\AssignmentController;
use Illuminate\Support\Facades\Route;

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
        
        // View Paper
        Route::get('/{assignment}/paper', [AssignmentController::class, 'viewPaper'])->name('view-paper');
        
        // Download Paper
        Route::get('/{assignment}/download', [AssignmentController::class, 'downloadPaper'])->name('download-paper');
    });
    
    // My Reviews
    Route::get('/reviews', function () {
        return view('reviewer.reviews');
    })->name('reviews');
    
    // Profile as Reviewer
    Route::get('/profile', function () {
        return view('reviewer.profile');
    })->name('profile');
});