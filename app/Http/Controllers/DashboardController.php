<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('editor')) {
            return redirect()->route('editor.dashboard');
        } elseif ($user->hasRole('reviewer')) {
            return redirect()->route('reviewer.dashboard');
        } elseif ($user->hasRole('author')) {
            return $this->authorDashboard();
        }
        
        // Default fallback for authenticated users without specific role
        return view('dashboard', [
            'user' => $user,
            'message' => 'You are authenticated but do not have a specific role assigned. Please contact administrator.'
        ]);
    }

    /**
     * Author dashboard.
     */
    protected function authorDashboard()
    {
        $user = Auth::user();
        $papers = $user->papers;
        
        $stats = [
            'submitted' => $papers->where('status', 'submitted')->count(),
            'under_review' => $papers->where('status', 'under_review')->count(),
            'accepted' => $papers->where('status', 'accepted')->count(),
            'published' => $papers->where('status', 'published')->count(),
        ];
        
        $recentPapers = $user->papers()
            ->with(['issue', 'reviewAssignments'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('author.dashboard', compact('stats', 'recentPapers'));
    }

    /**
     * Editor dashboard.
     */
    protected function editorDashboard()
    {
        // Data untuk editor
        $stats = [
            'submitted' => \App\Models\Paper::where('status', 'submitted')->count(),
            'under_review' => \App\Models\Paper::where('status', 'under_review')->count(),
            'needs_decision' => \App\Models\Paper::where('status', 'under_review')->count(),
            'active_issues' => \App\Models\Issue::where('status', 'published')->count(),
        ];
        
        $recentPapers = \App\Models\Paper::with('author')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $pendingReviews = \App\Models\ReviewAssignment::with(['paper', 'reviewer'])
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->take(5)
            ->get();
        
        return view('editor.dashboard', compact('stats', 'recentPapers', 'pendingReviews'));
    }

    /**
     * Reviewer dashboard.
     */
    protected function reviewerDashboard()
    {
        $user = Auth::user();
        
        return view('reviewer.dashboard', [
            'user' => $user
        ]);
    }

    /**
     * Admin dashboard.
     */
    protected function adminDashboard()
    {
        return view('admin.dashboard', [
            'userCount' => \App\Models\User::count(),
            'roleCount' => \Spatie\Permission\Models\Role::count(),
        ]);
    }
}