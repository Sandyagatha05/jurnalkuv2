<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SystemController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/assign-role', [UserController::class, 'assignRole'])->name('assign-role');
    });
    
    // Role Management
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
    });
    
    // System Settings
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/settings', [SystemController::class, 'index'])->name('index');
        Route::post('/settings', [SystemController::class, 'update'])->name('update');
        Route::get('/logs', [SystemController::class, 'logs'])->name('logs');
        Route::get('/backup', [SystemController::class, 'backup'])->name('backup');
    });
    
    // Reports
    Route::get('/reports', function () {
        return view('admin.reports');
    })->name('reports');
    
    // Activity Logs
    Route::get('/activities', function () {
        return view('admin.activities');
    })->name('activities');
});