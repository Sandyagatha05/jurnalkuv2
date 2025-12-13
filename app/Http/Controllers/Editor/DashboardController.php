<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Paper;
use App\Models\Issue;
use App\Models\ReviewAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display editor dashboard.
     */
    public function index()
    {
        $stats = [
            'submitted' => Paper::where('status', 'submitted')->count(),
            'under_review' => Paper::where('status', 'under_review')->count(),
            'needs_decision' => Paper::where('status', 'under_review')
                ->has('reviewAssignments')
                ->count(),
            'active_issues' => Issue::where('status', 'published')->count(),
        ];
        
        $recentPapers = Paper::with('author')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        $pendingReviews = ReviewAssignment::with(['paper', 'reviewer'])
            ->where('status', 'pending')
            ->where('due_date', '>=', now())
            ->orderBy('due_date')
            ->take(5)
            ->get();
        
        return view('editor.dashboard', compact('stats', 'recentPapers', 'pendingReviews'));
    }
}