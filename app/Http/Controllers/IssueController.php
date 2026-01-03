<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Paper;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * Display a listing of the issues (public).
     */
    public function index()
    {
        $query = Issue::with(['editorial', 'papers'])
            ->published()
            ->orderBy('year', 'desc')
            ->orderBy('volume', 'desc')
            ->orderBy('number', 'desc');
        
        // Filters
        if (request()->has('year')) {
            $query->where('year', request('year'));
        }
        
        if (request()->has('volume')) {
            $query->where('volume', request('volume'));
        }
        
        if (request()->has('search')) {
            $query->where('title', 'like', '%' . request('search') . '%');
        }
        
        $issues = $query->paginate(9);
        
        // Get available years and volumes for filter
        $years = Issue::published()->distinct()->pluck('year')->sortDesc()->toArray();
        $volumes = Issue::published()->distinct()->pluck('volume')->sortDesc()->toArray();
        
        return view('issues.index', compact('issues', 'years', 'volumes'));
    }

    /**
     * Display the specified issue (public).
     */
    public function show(Issue $issue)
    {
        // Only show published issues to public
        if (!$issue->isPublished() && !auth()->check()) {
            abort(404);
        }
        
        $issue->load(['editorial.author', 'papers.author']);
        
        // Get next and previous issues
        $previousIssue = Issue::published()
            ->where('year', '<=', $issue->year)
            ->where('id', '<>', $issue->id)
            ->orderBy('year', 'desc')
            ->orderBy('volume', 'desc')
            ->orderBy('number', 'desc')
            ->first();
        
        $nextIssue = Issue::published()
            ->where('year', '>=', $issue->year)
            ->where('id', '<>', $issue->id)
            ->orderBy('year', 'asc')
            ->orderBy('volume', 'asc')
            ->orderBy('number', 'asc')
            ->first();
        
        // Extract categories from papers (example)
        $categories = $issue->papers->pluck('category')->filter()->unique()->values();
        
        return view('issues.show', compact('issue', 'previousIssue', 'nextIssue', 'categories'));
    }
}