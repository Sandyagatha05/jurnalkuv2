<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;



// Public routes (accessible tanpa authentication)
require __DIR__.'/public.php';

// Authentication routes (Breeze)
require __DIR__.'/auth.php';

// Authenticated role-based routes
require __DIR__.'/role/author.php';
require __DIR__.'/role/editor.php';
require __DIR__.'/role/reviewer.php';
require __DIR__.'/role/admin.php';

// Fallback route
Route::fallback(function () {
    return view('errors.404');
});

// Di luar role groups
Route::middleware('auth')->prefix('reviews')->name('reviews.')->group(function () {
    Route::get('/{review}', [ReviewController::class, 'show'])->name('show');
    Route::get('/{review}/edit', [ReviewController::class, 'edit'])->name('edit');
    Route::put('/{review}', [ReviewController::class, 'update'])->name('update');
});
