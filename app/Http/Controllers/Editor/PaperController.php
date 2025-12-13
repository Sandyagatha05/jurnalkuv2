<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Paper;
use App\Models\User;
use App\Models\Issue;
use App\Models\ReviewAssignment;
use Illuminate\Http\Request;

class PaperController extends Controller
{
    /**
     * Display a listing of papers.
     */
     public function index()
    {
        $query = Paper::with(['author', 'issue', 'reviewAssignments.reviewer']);
        
        // Filter by status
        if (request()->has('status')) {
            $query->where('status', request('status'));
        }
        
        // Search
        if (request()->has('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhereHas('author', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }
        
        $papers = $query->orderBy('created_at', 'desc')->paginate(20);
        $issues = Issue::published()->get();
        
        return view('editor.papers.index', compact('papers', 'issues'));
    }

    /**
     * Display submitted papers.
     */
    public function submitted()
    {
        $papers = Paper::with(['author', 'reviewAssignments.reviewer'])
            ->where('status', 'submitted')
            ->orderBy('submitted_at', 'desc')
            ->paginate(20);
        
        return view('editor.papers.submitted', compact('papers'));
    }

    /**
     * Display papers under review.
     */
    public function underReview()
    {
        $papers = Paper::with(['author', 'reviewAssignments.reviewer'])
            ->where('status', 'under_review')
            ->orderBy('reviewed_at', 'desc')
            ->paginate(20);
        
        return view('editor.papers.under-review', compact('papers'));
    }

    /**
     * Display accepted papers.
     */
    public function accepted()
    {
        $papers = Paper::with(['author', 'issue'])
            ->where('status', 'accepted')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        
        return view('editor.papers.accepted', compact('papers'));
    }

    /**
     * Display rejected papers.
     */
    public function rejected()
    {
        $papers = Paper::with(['author'])
            ->where('status', 'rejected')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        
        return view('editor.papers.rejected', compact('papers'));
    }

    /**
     * Display the specified paper.
     */

    public function show(Paper $paper)
    {
        $paper->load([
            'author', 
            'issue', 
            'reviewAssignments.reviewer', 
            'reviewAssignments.review'
        ]);
        
        $completedReviews = $paper->reviewAssignments->where('status', 'completed')->count();
        $totalReviews = $paper->reviewAssignments->count();
        $issues = Issue::published()->get();
        
        return view('editor.papers.show', compact('paper', 'completedReviews', 'totalReviews', 'issues'));
    }

    /**
     * Show form to assign reviewers.
     */
    public function assignReviewers(Paper $paper)
    {
        // Get all reviewers
        $reviewers = User::role('reviewer')
            ->withCount([
                'reviewAssignments as pending_assignments' => function($query) {
                    $query->where('status', 'pending');
                },
                'reviewAssignments as completed_assignments' => function($query) {
                    $query->where('status', 'completed');
                }
            ])
            ->with(['reviewAssignments' => function($query) {
                $query->where('status', 'pending')->limit(3);
            }])
            ->get();
        
        $assignedReviewers = $paper->reviewers;
        
        return view('editor.papers.assign-reviewers', compact('paper', 'reviewers', 'assignedReviewers'));
    }

    /**
     * Store assigned reviewers.
     */
    public function storeAssignReviewers(Request $request, Paper $paper)
    {
        $validated = $request->validate([
            'reviewers' => 'required|array|min:1|max:3',
            'reviewers.*' => 'exists:users,id',
            'due_date' => 'required|date|after:today',
            'editor_notes' => 'nullable|string',
        ]);
        
        // Remove existing assignments
        ReviewAssignment::where('paper_id', $paper->id)->delete();
        
        // Create new assignments
        foreach ($validated['reviewers'] as $reviewerId) {
            ReviewAssignment::create([
                'paper_id' => $paper->id,
                'reviewer_id' => $reviewerId,
                'assigned_by' => auth()->id(),
                'assigned_date' => now(),
                'due_date' => $validated['due_date'],
                'editor_notes' => $validated['editor_notes'],
                'status' => 'pending',
            ]);
        }
        
        // Update paper status
        $paper->status = 'under_review';
        $paper->reviewed_at = now();
        $paper->save();
        
        return redirect()->route('editor.papers.show', $paper)
            ->with('success', 'Reviewers assigned successfully.');
    }

    /**
     * Show form to make decision.
     */
    public function decision(Paper $paper)
    {
        $paper->load(['reviewAssignments.review']);
        
        return view('editor.papers.decision', compact('paper'));
    }

    /**
     * Store decision.
     */
    public function storeDecision(Request $request, Paper $paper)
    {
        $validated = $request->validate([
            'decision' => 'required|in:accept,revision_minor,revision_major,reject',
            'editor_notes' => 'nullable|string',
            'notify_author' => 'boolean',
        ]);
        
        $paper->status = $validated['decision'];
        $paper->save();
        
        // TODO: Send notification to author if requested
        
        return redirect()->route('editor.papers.show', $paper)
            ->with('success', 'Decision saved successfully.');
    }

    /**
     * Assign paper to issue.
     */
    public function assignIssue(Request $request, Paper $paper)
    {
        $request->validate([
            'issue_id' => 'required|exists:issues,id',
            'page_from' => 'nullable|integer|min:1',
            'page_to' => 'nullable|integer|gt:page_from',
        ]);

        $paper->update([
            'issue_id' => $request->issue_id,
            'page_from' => $request->page_from,
            'page_to' => $request->page_to,
        ]);

        return back()->with('success', 'Paper assigned to issue successfully.');
    }

    public function updateStatus(Request $request, Paper $paper)
    {
        $request->validate([
            'status' => 'required|in:submitted,under_review,accepted,revision_minor,revision_major,rejected,published',
        ]);

        $oldStatus = $paper->status;
        $paper->status = $request->status;
        
        // Update timestamps based on status change
        if ($request->status === 'under_review' && $oldStatus !== 'under_review') {
            $paper->reviewed_at = now();
        }
        
        if ($request->status === 'published' && $oldStatus !== 'published') {
            $paper->published_at = now();
        }
        
        $paper->save();
        
        return back()->with('success', 'Paper status updated successfully.');
    }

    public function assignIssueForm(Paper $paper)
    {
        $issues = \App\Models\Issue::published()->orWhere('status', 'draft')->get();
        return view('editor.papers.assign-issue', compact('paper', 'issues'));
    }

   

}