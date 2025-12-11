<?php

use Illuminate\Support\Facades\Route;



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


