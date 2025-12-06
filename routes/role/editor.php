<?php

use App\Http\Controllers\Editor\IssueController;
use App\Http\Controllers\Editor\PaperController;
use App\Http\Controllers\Editor\ReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'editor'])->prefix('editor')->name('editor.')->group(function () {
    
    // Editor Dashboard
    Route::get('/dashboard', function () {
        return view('editor.dashboard');
    })->name('dashboard');
    
    // Issue Management
    Route::prefix('issues')->name('issues.')->group(function () {
        Route::get('/', [IssueController::class, 'index'])->name('index');
        Route::get('/create', [IssueController::class, 'create'])->name('create');
        Route::post('/', [IssueController::class, 'store'])->name('store');
        Route::get('/{issue}', [IssueController::class, 'show'])->name('show');
        Route::get('/{issue}/edit', [IssueController::class, 'edit'])->name('edit');
        Route::put('/{issue}', [IssueController::class, 'update'])->name('update');
        Route::delete('/{issue}', [IssueController::class, 'destroy'])->name('destroy');
        Route::post('/{issue}/publish', [IssueController::class, 'publish'])->name('publish');
        Route::post('/{issue}/unpublish', [IssueController::class, 'unpublish'])->name('unpublish');
        
        // Editorial
        Route::get('/{issue}/editorial', [IssueController::class, 'editorial'])->name('editorial');
        Route::post('/{issue}/editorial', [IssueController::class, 'storeEditorial'])->name('store-editorial');
    });
    
    // Paper Management
    Route::prefix('papers')->name('papers.')->group(function () {
        Route::get('/', [PaperController::class, 'index'])->name('index');
        Route::get('/submitted', [PaperController::class, 'submitted'])->name('submitted');
        Route::get('/under-review', [PaperController::class, 'underReview'])->name('under-review');
        Route::get('/accepted', [PaperController::class, 'accepted'])->name('accepted');
        Route::get('/rejected', [PaperController::class, 'rejected'])->name('rejected');
        Route::get('/{paper}', [PaperController::class, 'show'])->name('show');
        
        // Assign Reviewers
        Route::get('/{paper}/assign-reviewers', [PaperController::class, 'assignReviewers'])->name('assign-reviewers');
        Route::post('/{paper}/assign-reviewers', [PaperController::class, 'storeAssignReviewers'])->name('store-assign-reviewers');
        
        // Decision
        Route::get('/{paper}/decision', [PaperController::class, 'decision'])->name('decision');
        Route::post('/{paper}/decision', [PaperController::class, 'storeDecision'])->name('store-decision');
        
        // Assign to Issue
        Route::post('/{paper}/assign-issue', [PaperController::class, 'assignIssue'])->name('assign-issue');
    });
    
    // Review Management
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::get('/pending', [ReviewController::class, 'pending'])->name('pending');
        Route::get('/completed', [ReviewController::class, 'completed'])->name('completed');
        Route::get('/{review}', [ReviewController::class, 'show'])->name('show');
    });
});