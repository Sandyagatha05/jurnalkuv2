<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\Paper;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * Display a listing of issues.
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
        
        $issues = $query->paginate(12);
        
        // Get available years and volumes for filter
        $years = Issue::published()->distinct()->pluck('year')->sortDesc();
        $volumes = Issue::published()->distinct()->pluck('volume')->sortDesc();
        
        return view('issues.index', compact('issues', 'years', 'volumes'));
    }

    /**
     * Show the form for creating a new issue.
     */
    public function create()
    {
        return view('editor.issues.create');
    }

    /**
     * Store a newly created issue.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'volume' => 'required|integer|min:1',
            'number' => 'required|integer|min:1',
            'year' => 'required|integer|min:2000|max:2050',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        // Check for duplicate issue
        $existing = Issue::where([
            'volume' => $validated['volume'],
            'number' => $validated['number'],
            'year' => $validated['year'],
        ])->exists();
        
        if ($existing) {
            return back()->withErrors([
                'volume' => 'An issue with this volume, number, and year already exists.'
            ])->withInput();
        }
        
        $issue = Issue::create([
            'volume' => $validated['volume'],
            'number' => $validated['number'],
            'year' => $validated['year'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'draft',
            'editor_id' => auth()->id(),
        ]);
        
        return redirect()->route('editor.issues.show', $issue)
            ->with('success', 'Issue created successfully.');
    }

    /**
     * Display the specified issue.
     */
    public function show(Issue $issue)
    {
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

    /**
     * Show the form for editing the specified issue.
     */
    public function edit(Issue $issue)
    {
        return view('editor.issues.edit', compact('issue'));
    }

    /**
     * Update the specified issue.
     */
    public function update(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'published_date' => 'nullable|date',
        ]);
        
        $issue->update($validated);
        
        return redirect()->route('editor.issues.show', $issue)
            ->with('success', 'Issue updated successfully.');
    }

    /**
     * Remove the specified issue.
     */
    public function destroy(Issue $issue)
    {
        // Can only delete draft issues
        if ($issue->status !== 'draft') {
            return back()->with('error', 'Only draft issues can be deleted.');
        }
        
        $issue->delete();
        
        return redirect()->route('editor.issues.index')
            ->with('success', 'Issue deleted successfully.');
    }

    /**
     * Publish the issue.
     */
    public function publish(Issue $issue)
    {
        // Check if issue has editorial
        if (!$issue->hasEditorial()) {
            return back()->with('error', 'Cannot publish issue without editorial.');
        }
        
        // Check if issue has at least one paper
        if ($issue->papers()->count() === 0) {
            return back()->with('error', 'Cannot publish issue without any papers.');
        }
        
        $issue->status = 'published';
        $issue->published_date = now();
        $issue->save();
        
        // Mark all papers in issue as published
        $issue->papers()->update(['status' => 'published', 'published_at' => now()]);
        
        return back()->with('success', 'Issue published successfully.');
    }

    /**
     * Unpublish the issue.
     */
    public function unpublish(Issue $issue)
    {
        $issue->status = 'draft';
        $issue->save();
        
        return back()->with('success', 'Issue unpublished successfully.');
    }

    /**
     * Show editorial for issue.
     */
    public function editorial(Issue $issue)
    {
        $editorial = $issue->editorial;
        return view('editor.issues.editorial', compact('issue', 'editorial'));
    }

    /**
     * Store editorial for issue.
     */
    public function storeEditorial(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);
        
        $editorial = $issue->editorial()->updateOrCreate(
            ['issue_id' => $issue->id],
            [
                'title' => $validated['title'],
                'content' => $validated['content'],
                'author_id' => auth()->id(),
                'is_published' => $request->has('is_published'),
                'published_date' => $request->has('is_published') ? now() : null,
            ]
        );
        
        return redirect()->route('editor.issues.show', $issue)
            ->with('success', 'Editorial saved successfully.');
    }
}



