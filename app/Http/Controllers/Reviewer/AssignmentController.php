<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\ReviewAssignment;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    /**
     * Display a listing of assignments.
     */
    public function index()
    {
        $assignments = ReviewAssignment::where('reviewer_id', Auth::id())
            ->with(['paper.author', 'paper.issue'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('reviewer.assignments.index', compact('assignments'));
    }

    /**
     * Display pending assignments.
     */
    public function pending()
    {
        $assignments = ReviewAssignment::where('reviewer_id', Auth::id())
            ->where('status', 'pending')
            ->with(['paper.author'])
            ->orderBy('due_date')
            ->paginate(15);
        
        return view('reviewer.assignments.pending', compact('assignments'));
    }

    /**
     * Display completed assignments.
     */
    public function completed()
    {
        $assignments = ReviewAssignment::where('reviewer_id', Auth::id())
            ->where('status', 'completed')
            ->with(['paper.author', 'review'])
            ->orderBy('completed_date', 'desc')
            ->paginate(15);
        
        return view('reviewer.assignments.completed', compact('assignments'));
    }

    /**
     * Display overdue assignments.
     */
    public function overdue()
    {
        $assignments = ReviewAssignment::where('reviewer_id', Auth::id())
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->with(['paper.author'])
            ->orderBy('due_date')
            ->paginate(15);
        
        return view('reviewer.assignments.overdue', compact('assignments'));
    }

    /**
     * Display the specified assignment.
     */
    public function show(ReviewAssignment $assignment)
    {
        // Authorization
        if ($assignment->reviewer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this review.');
        }
        
        $assignment->load(['paper.author', 'paper.issue', 'review']);
        
        return view('reviewer.assignments.show', compact('assignment'));
    }

    /**
     * Accept assignment.
     */
    public function accept(Request $request, ReviewAssignment $assignment)
    {
        if ($assignment->reviewer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this review.');
        }
        
        if ($assignment->status !== 'pending') {
            return back()->with('error', 'Assignment is not pending.');
        }
        
        $assignment->status = 'accepted';
        $assignment->save();
        
        return back()->with('success', 'Assignment accepted successfully.');
    }

    /**
     * Decline assignment.
     */
    public function decline(Request $request, ReviewAssignment $assignment)
    {
        if ($assignment->reviewer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this review.');
        }
        
        if ($assignment->status !== 'pending') {
            return back()->with('error', 'Assignment is not pending.');
        }
        
        $assignment->status = 'declined';
        $assignment->save();
        
        return back()->with('success', 'Assignment declined successfully.');
    }

    /**
     * Show review form.
     */
    public function review(ReviewAssignment $assignment)
    {
        if ($assignment->reviewer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this review.');
        }
        
        if ($assignment->status === 'completed') {
            return redirect()->route('reviewer.assignments.show', $assignment)
                ->with('info', 'Review already completed.');
        }
        
        $paper = $assignment->paper;
        $review = $assignment->review;
        
        return view('reviewer.assignments.review', compact('assignment', 'paper', 'review'));
    }

    /**
     * Submit review.
     */
    public function submitReview(Request $request, ReviewAssignment $assignment)
    {
        if ($assignment->reviewer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this review.');
        }
        
        if ($assignment->status === 'completed') {
            return back()->with('error', 'Review already completed.');
        }
        
        $validated = $request->validate([
            'comments_to_editor' => 'required|string',
            'comments_to_author' => 'required|string',
            'recommendation' => 'required|in:accept,minor_revision,major_revision,reject',
            'originality_score' => 'required|integer|min:1|max:5',
            'contribution_score' => 'required|integer|min:1|max:5',
            'clarity_score' => 'required|integer|min:1|max:5',
            'methodology_score' => 'required|integer|min:1|max:5',
            'overall_score' => 'required|integer|min:1|max:5',
            'is_confidential' => 'boolean',
        ]);
        
        // Create review
        $review = $assignment->review()->create([
            'comments_to_editor' => $validated['comments_to_editor'],
            'comments_to_author' => $validated['comments_to_author'],
            'recommendation' => $validated['recommendation'],
            'originality_score' => $validated['originality_score'],
            'contribution_score' => $validated['contribution_score'],
            'clarity_score' => $validated['clarity_score'],
            'methodology_score' => $validated['methodology_score'],
            'overall_score' => $validated['overall_score'],
            'is_confidential' => $validated['is_confidential'] ?? false,
            'reviewed_at' => now(),
        ]);
        
        // Update assignment
        $assignment->status = 'completed';
        $assignment->completed_date = now();
        $assignment->save();
        
        return redirect()->route('reviewer.assignments.completed')
            ->with('success', 'Review submitted successfully.');
    }

    /**
     * View paper (read-only).
     */
    public function viewPaper(ReviewAssignment $assignment)
    {
        if ($assignment->reviewer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this review.');
        }
        
        $paper = $assignment->paper;
        
        return view('reviewer.assignments.view-paper', compact('paper', 'assignment'));
    }

    /**
     * Stream the paper PDF inline (for embedding) with authorization.
     */
    public function viewPaperFile(ReviewAssignment $assignment)
    {
        if ($assignment->reviewer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this review.');
        }

        $paper = $assignment->paper;

        // Delegate to PaperController inline viewer
        return app('\App\Http\Controllers\PaperController')->viewInline($paper);
    }

    /**
     * Download paper file.
     */
    public function downloadPaper(ReviewAssignment $assignment)
    {
        if ($assignment->reviewer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this review.');
        }
        
        $paper = $assignment->paper;
        
        return app('App\Http\Controllers\PaperController')->download($paper);
    }

    /**
 * Save review as draft.
 */
    public function saveDraft(Request $request, ReviewAssignment $assignment)
    {
        if ($assignment->reviewer_id !== Auth::id()) {
            abort(403, 'You are not assigned to this review.');
        }
        
        if ($assignment->status === 'completed') {
            return back()->with('error', 'Review already completed.');
        }
        
        $validated = $request->validate([
            'comments_to_editor' => 'nullable|string',
            'comments_to_author' => 'nullable|string',
            'recommendation' => 'nullable|in:accept,minor_revision,major_revision,reject',
            'originality_score' => 'nullable|integer|min:1|max:5',
            'contribution_score' => 'nullable|integer|min:1|max:5',
            'clarity_score' => 'nullable|integer|min:1|max:5',
            'methodology_score' => 'nullable|integer|min:1|max:5',
            'overall_score' => 'nullable|integer|min:1|max:5',
            'is_confidential' => 'boolean',
        ]);
        
        // Create or update draft review
        $review = $assignment->review ?? new Review();
        $review->fill($validated);
        $review->assignment_id = $assignment->id;
        $review->save();
        
        // Don't update assignment status for draft
        
        return back()->with('success', 'Review draft saved successfully.');
    }
}