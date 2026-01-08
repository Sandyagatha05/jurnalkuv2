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
    
    // Get total counts for badges (across all pages)
    $statusCounts = [
        'published' => Issue::where('status', 'published')->count(),
        'draft' => Issue::where('status', 'draft')->count(),
        'archived' => Issue::where('status', 'archived')->count(),
    ];
    
    // Get available years and volumes for filter
    $years = Issue::distinct()->pluck('year')->sortDesc();
    $volumes = Issue::distinct()->pluck('volume')->sortDesc();
    
    return view('editor.issues.index', compact('issues', 'years', 'volumes', 'statusCounts'));
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
 * Change issue status.
 */
    public function changeStatus(Request $request, Issue $issue)
    {
        $request->validate([
            'status' => 'required|in:draft,published,archived'
        ]);
        
        $issue->status = $request->status;
        
        // If publishing, set published date
        if ($request->status === 'published' && !$issue->published_date) {
            $issue->published_date = now();
        }
        
        $issue->save();
        
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
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
        
        return view('editor.issues.show', compact('issue', 'previousIssue', 'nextIssue', 'categories'));
    }

    /**
     * Show the form for editing the specified issue.
     */
/**
 * Show the form for editing the specified issue.
 */
    public function edit(Issue $issue)
    {
        $issue->load(['papers.author']);
        
        // Get accepted papers that are not assigned to any issue
        $availablePapers = Paper::with('author')
            ->where('status', 'accepted')
            ->whereNull('issue_id')
            ->orderBy('submitted_at', 'desc')
            ->get();
        
        return view('editor.issues.edit', compact('issue', 'availablePapers'));
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
     *//**
 * Publish the issue.
 */
    public function publish(Issue $issue)
    {
        // Check if issue has editorial
        if (!$issue->hasEditorial()) {
            return response()->json(['success' => false, 'message' => 'Cannot publish issue without editorial.'], 400);
        }
        
        // Check if issue has at least one paper
        if ($issue->papers()->count() === 0) {
            return response()->json(['success' => false, 'message' => 'Cannot publish issue without any papers.'], 400);
        }
        
        $issue->status = 'published';
        $issue->published_date = now();
        $issue->save();
        
        // Mark all papers in issue as published
        $issue->papers()->update(['status' => 'published', 'published_at' => now()]);
        
        return response()->json(['success' => true, 'message' => 'Issue published successfully.']);
    }

    /**
     * Unpublish the issue.
     */
    public function unpublish(Issue $issue)
    {
        $issue->status = 'draft';
        $issue->save();
        
        return response()->json(['success' => true, 'message' => 'Issue unpublished successfully.']);
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

    /**
 * Add a paper to the issue.
 */
    public function addPaper(Request $request, Issue $issue)
    {
        $request->validate([
            'paper_id' => 'required|exists:papers,id',
            'page_from' => 'required|integer|min:1',
            'page_to' => 'required|integer|min:1|gte:page_from',
        ]);

        $paper = Paper::findOrFail($request->paper_id);
        
        // Check if paper is accepted and not assigned to another issue
        if ($paper->status !== 'accepted' || $paper->issue_id) {
            return back()->with('error', 'Paper cannot be added to this issue.');
        }

        $paper->update([
            'issue_id' => $issue->id,
            'page_from' => $request->page_from,
            'page_to' => $request->page_to,
        ]);

        return back()->with('success', 'Paper added to issue successfully.');
    }

    /**
     * Update paper page numbers in the issue.
     */
    public function updatePaper(Request $request, Issue $issue, Paper $paper)
    {
        $request->validate([
            'page_from' => 'required|integer|min:1',
            'page_to' => 'required|integer|min:1|gte:page_from',
        ]);

        // Check if paper belongs to this issue
        if ($paper->issue_id !== $issue->id) {
            return back()->with('error', 'Paper does not belong to this issue.');
        }

        $paper->update([
            'page_from' => $request->page_from,
            'page_to' => $request->page_to,
        ]);

        return back()->with('success', 'Page numbers updated successfully.');
    }

    /**
     * Remove a paper from the issue.
     */
    public function removePaper(Issue $issue, Paper $paper)
    {
        // Check if paper belongs to this issue
        if ($paper->issue_id !== $issue->id) {
            return back()->with('error', 'Paper does not belong to this issue.');
        }

        $paper->update([
            'issue_id' => null,
            'page_from' => null,
            'page_to' => null,
        ]);

        return back()->with('success', 'Paper removed from issue successfully.');
    }
}



