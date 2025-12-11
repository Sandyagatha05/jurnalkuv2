<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\PaperController;
use Illuminate\Support\Facades\Route;

// Welcome/Home Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// About Page
Route::get('/about', function () {
    return view('public.about');
})->name('about');

// Contact Page
Route::get('/contact', function () {
    return view('public.contact');
})->name('contact');

// Guidelines for Authors
Route::get('/guidelines', function () {
    return view('public.guidelines');
})->name('guidelines');

// Issues - Public Listing
Route::get('/issues', [IssueController::class, 'index'])->name('issues.index');
Route::get('/issues/{issue}', [IssueController::class, 'show'])->name('issues.show');

// Papers - Public Listing
Route::get('/papers', [PaperController::class, 'index'])->name('papers.index');
Route::get('/papers/{paper}', [PaperController::class, 'show'])->name('papers.show');
Route::get('/papers/{paper}/download', [PaperController::class, 'download'])->name('papers.download');

// Search Papers
Route::get('/search', function () {
    return view('public.search');
})->name('search');

// Archive
Route::get('/archive', function () {
    return view('public.archive');
})->name('archive');

// Editorial Board
Route::get('/editorial-board', function () {
    return view('public.editorial-board');
})->name('editorial-board');

// Privacy Policy
Route::get('/privacy-policy', function () {
    return view('public.privacy-policy');
})->name('privacy-policy');

// Terms of Service
Route::get('/terms', function () {
    return view('public.terms');
})->name('terms');

// Archive
Route::get('/archive', function () {
    return view('public.archive');
})->name('archive');