<?php

<<<<<<< HEAD
=======
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
>>>>>>> 4db2fe4ab84f24aa3c590f9dee6c3428d6bfac9d
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

<<<<<<< HEAD
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
=======
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include role-based routes
require __DIR__.'/role/admin.php';
require __DIR__.'/role/editor.php';
require __DIR__.'/role/reviewer.php';
require __DIR__.'/role/author.php';

require __DIR__.'/auth.php';
>>>>>>> 4db2fe4ab84f24aa3c590f9dee6c3428d6bfac9d
