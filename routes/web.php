<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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