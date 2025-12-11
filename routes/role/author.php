<?php

use App\Http\Controllers\Author\PaperController as AuthorPaperController;
use Illuminate\Support\Facades\Route;

// Author Routes Group
Route::middleware(['auth', 'author'])->prefix('author')->name('author.')->group(function () {
    
    // Author Dashboard
    Route::get('/dashboard', function () {
        return view('author.dashboard');
    })->name('dashboard');
    
    // Papers Management
    Route::prefix('papers')->name('papers.')->group(function () {
        Route::get('/', [AuthorPaperController::class, 'index'])->name('index');
        Route::get('/create', [AuthorPaperController::class, 'create'])->name('create');
        Route::post('/', [AuthorPaperController::class, 'store'])->name('store');
        Route::get('/{paper}', [AuthorPaperController::class, 'show'])->name('show');
        Route::get('/{paper}/edit', [AuthorPaperController::class, 'edit'])->name('edit');
        Route::put('/{paper}', [AuthorPaperController::class, 'update'])->name('update');
        Route::delete('/{paper}', [AuthorPaperController::class, 'destroy'])->name('destroy');
        
        // Revision
        Route::get('/{paper}/revision', [AuthorPaperController::class, 'revision'])->name('revision');
        Route::post('/{paper}/revision', [AuthorPaperController::class, 'submitRevision'])->name('submit-revision');
        
        // Reviews
        Route::get('/{paper}/reviews', [AuthorPaperController::class, 'reviews'])->name('reviews');
        
        // Download
        Route::get('/{paper}/download', [AuthorPaperController::class, 'download'])->name('download');
    });
    
    // Published Papers
    Route::get('/published', function () {
        return view('author.published');
    })->name('published');
    
    // Statistics
    Route::get('/statistics', function () {
        return view('author.statistics');
    })->name('statistics');
    
    // Author Profile (Extended)
    Route::get('/profile', function () {
        return view('author.profile');
    })->name('profile');
    
    // Co-authors Management
    Route::prefix('coauthors')->name('coauthors.')->group(function () {
        Route::get('/', function () {
            return view('author.coauthors.index');
        })->name('index');
        
        Route::get('/add', function () {
            return view('author.coauthors.create');
        })->name('create');
    });
    
    // Submission Guidelines
    Route::get('/submission-guide', function () {
        return view('author.submission-guide');
    })->name('submission-guide');
});