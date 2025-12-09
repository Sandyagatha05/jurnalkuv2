<?php

<<<<<<< HEAD
use App\Http\Controllers\Author\PaperController as AuthorPaperController;
use App\Http\Controllers\EditorialController;
use Illuminate\Support\Facades\Route;

// Author Routes Group
=======
use App\Http\Controllers\Author\PaperController;
use Illuminate\Support\Facades\Route;

>>>>>>> 4db2fe4ab84f24aa3c590f9dee6c3428d6bfac9d
Route::middleware(['auth', 'author'])->prefix('author')->name('author.')->group(function () {
    
    // Author Dashboard
    Route::get('/dashboard', function () {
        return view('author.dashboard');
    })->name('dashboard');
    
<<<<<<< HEAD
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
=======
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
>>>>>>> 4db2fe4ab84f24aa3c590f9dee6c3428d6bfac9d
    Route::get('/published', function () {
        return view('author.published');
    })->name('published');
    
<<<<<<< HEAD
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
=======
    // Co-authors (future feature)
    Route::get('/coauthors', function () {
        return view('author.coauthors');
    })->name('coauthors');
>>>>>>> 4db2fe4ab84f24aa3c590f9dee6c3428d6bfac9d
});