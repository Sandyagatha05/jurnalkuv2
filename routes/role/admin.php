<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// Admin Routes Group
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route::get('/dashboard', function () {
    //     return view('admin.dashboard');
    // })->name('dashboard');
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', function ($userId) {
            $user = \App\Models\User::with(['roles', 'papers', 'reviewAssignments'])->findOrFail($userId);
            return view('admin.users.show', compact('user'));
        })->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/assign-role', [UserController::class, 'assignRole'])->name('assign-role');
        
        // Bulk Actions
        Route::post('/bulk/delete', function () {
            // TODO: Implement bulk delete
            return back()->with('success', 'Users deleted successfully.');
        })->name('bulk.delete');
        
        Route::post('/bulk/assign-role', function () {
            // TODO: Implement bulk role assignment
            return back()->with('success', 'Roles assigned successfully.');
        })->name('bulk.assign-role');
        
        // Import/Export
        Route::get('/export', function () {
            // TODO: Export users to CSV/Excel
            return back()->with('success', 'Export started.');
        })->name('export');
        
        Route::post('/import', function () {
            // TODO: Import users from CSV/Excel
            return back()->with('success', 'Import completed.');
        })->name('import');
    });
    
    // Role & Permission Management
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{role}', function ($roleId) {
            $role = \Spatie\Permission\Models\Role::with('permissions')->findOrFail($roleId);
            $users = \App\Models\User::role($role->name)->paginate(10);
            return view('admin.roles.show', compact('role', 'users'));
        })->name('show');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
        
        // Permission Management
        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', function () {
                $permissions = \Spatie\Permission\Models\Permission::all()->groupBy(function($permission) {
                    $parts = explode(' ', $permission->name);
                    return $parts[0] ?? 'other';
                });
                return view('admin.permissions.index', compact('permissions'));
            })->name('index');
            
            Route::post('/', function () {
                // TODO: Create new permission
                return back()->with('success', 'Permission created.');
            })->name('store');
        });
    });
    
    // System Settings
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/settings', [SystemController::class, 'index'])->name('settings');
        Route::post('/settings', [SystemController::class, 'update'])->name('update-settings');
        
        // General Settings
        Route::get('/general', function () {
            return view('admin.system.general');
        })->name('general');
        
        // Journal Settings
        Route::get('/journal', function () {
            return view('admin.system.journal');
        })->name('journal');
        
        // Email Settings
        Route::get('/email', function () {
            return view('admin.system.email');
        })->name('email');
        
        // File Settings
        Route::get('/files', function () {
            return view('admin.system.files');
        })->name('files');
        
        // Review Settings
        Route::get('/review', function () {
            return view('admin.system.review');
        })->name('review');
        
        // Backup Management
        Route::get('/backup', [SystemController::class, 'backup'])->name('backup');
        Route::post('/backup/create', [SystemController::class, 'createBackup'])->name('backup.create');
        Route::delete('/backup/{filename}', [SystemController::class, 'deleteBackup'])->name('backup.delete');
        Route::get('/backup/{filename}/download', [SystemController::class, 'downloadBackup'])->name('backup.download');
        
        // Maintenance
        Route::get('/maintenance', function () {
            return view('admin.system.maintenance');
        })->name('maintenance');
        
        Route::post('/maintenance/clear-cache', function () {
            \Artisan::call('optimize:clear');
            return back()->with('success', 'Cache cleared successfully.');
        })->name('maintenance.clear-cache');
        
        Route::post('/maintenance/migrate', function () {
            \Artisan::call('migrate', ['--force' => true]);
            return back()->with('success', 'Database migrated successfully.');
        })->name('maintenance.migrate');
    });
    
    // Activity Logs
    Route::prefix('activities')->name('activities.')->group(function () {
        Route::get('/', function () {
            $activities = \App\Models\ActivityLog::with(['causer', 'subject'])
                ->latest()
                ->paginate(50);
            return view('admin.activities.index', compact('activities'));
        })->name('index');
        
        Route::get('/{activity}', function ($activityId) {
            $activity = \App\Models\ActivityLog::with(['causer', 'subject'])->findOrFail($activityId);
            return view('admin.activities.show', compact('activity'));
        })->name('show');
        
        Route::delete('/clear', function () {
            \App\Models\ActivityLog::truncate();
            return back()->with('success', 'Activity logs cleared.');
        })->name('clear');
    });
    
    // Reports & Analytics
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () {
            return view('admin.reports.index');
        })->name('index');
        
        Route::get('/users', function () {
            return view('admin.reports.users');
        })->name('users');
        
        Route::get('/papers', function () {
            return view('admin.reports.papers');
        })->name('papers');
        
        Route::get('/reviews', function () {
            return view('admin.reports.reviews');
        })->name('reviews');
        
        Route::get('/financial', function () {
            return view('admin.reports.financial');
        })->name('financial');
        
        // Export Reports
        Route::get('/export/{type}', function ($type) {
            // TODO: Export report based on type
            return back()->with('success', 'Report exported.');
        })->name('export');
    });
    
    // Audit Trail
    Route::get('/audit-trail', function () {
        return view('admin.audit-trail');
    })->name('audit-trail');

    
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::resource('users', UserController::class);
    
    // Role Management
    Route::resource('roles', RoleController::class);

        Route::prefix('roles')->name('roles.')->controller(RoleController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{role}', 'show')->name('show');
        Route::get('/{role}/edit', 'edit')->name('edit');
        Route::put('/{role}', 'update')->name('update');
        Route::delete('/{role}', 'destroy')->name('destroy');
        
        // Permission Management
        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', [RoleController::class, 'permissions'])->name('index');
            Route::post('/', [RoleController::class, 'storePermission'])->name('store');
            Route::delete('/{permission}', [RoleController::class, 'destroyPermission'])->name('destroy');
        });
        
        // Assign permissions to role
        Route::post('/{role}/assign-permissions', [RoleController::class, 'assignPermissions'])->name('assign-permissions');
    });
});
});