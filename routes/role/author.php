<?php

use App\Http\Controllers\Author\PaperController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'author'])->prefix('author')->name('author.')->group(function () {
    
    // Author Dashboard
    Route::get('/dashboard', function () {
        return view('author.dashboard');
    })->name('dashboard');
    
    // Paper Submission
    Route::prefix('papers')->name('papers.')->group(function () {
        Route::get('/', [PaperController::class, 'index'])->name('index');
        Route::get('/create', [PaperController::class, 'create'])->name('create');
        Route::post('/', [PaperController::class, 'store'])->name('store');
        Route::get('/{paper}', [PaperController::class, 'show'])->name('show');
        Route::get('/{paper}/edit', [PaperController::class, 'edit'])->name('edit');
        Route::put('/{paper}', [PaperController::class, 'update'])->name('update');
        Route::delete('/{paper}', [PaperController::class, 'destroy'])->name('destroy');
        
        // Submit Revision
        Route::get('/{paper}/revision', [PaperController::class, 'revision'])->name('revision');
        Route::post('/{paper}/revision', [PaperController::class, 'submitRevision'])->name('submit-revision');
        
        // View Reviews
        Route::get('/{paper}/reviews', [PaperController::class, 'reviews'])->name('reviews');
        
        // Download Paper
        Route::get('/{paper}/download', [PaperController::class, 'download'])->name('download');
    });
    
    // My Published Papers
    Route::get('/published', function () {
        return view('author.published');
    })->name('published');
    
    // Co-authors (future feature)
    Route::get('/coauthors', function () {
        return view('author.coauthors');
    })->name('coauthors');
});