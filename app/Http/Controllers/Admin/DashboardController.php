<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Paper;
use App\Models\Issue;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_users' => User::count(),
            'total_papers' => Paper::count(),
            'published_issues' => Issue::published()->count(),
            'total_roles' => Role::count(),
        ];
        
        // Get role statistics
        $roleStats = [];
        $colors = ['#4361ee', '#28a745', '#ffc107', '#17a2b8', '#6c757d'];
        
        $roles = Role::all();
        foreach ($roles as $index => $role) {
            $roleStats[] = [
                'name' => $role->name,
                'count' => User::role($role->name)->count(),
                'color' => $colors[$index] ?? '#6c757d'
            ];
        }
        
        // Get recent activities (simplified for now)
        $recentActivities = collect([]);
        
        return view('admin.dashboard', compact('stats', 'roleStats', 'recentActivities'));
    }
}