<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewAssignment;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Show the form for creating/editing a review.
     */
    public function create(ReviewAssignment $assignment)
    {
        // Authorization: Only assigned reviewer can review
        if ($assignment->reviewer_id !== Auth::id()) {
            abort(403, 'You are not assigned to review this paper.');
        }
        
        if ($assignment->status === 'completed') {
            return redirect()->route('reviews.show', $assignment->review)
                ->with('info', 'Review already completed.');
        }
        
        $review = $assignment->review ?? new Review();
        $paper = $assignment->paper;
        
        return view('reviews.create', compact('assignment', 'review', 'paper'));
    }

    /**
     * Store a newly created review or update existing.
     */
    public function store(Request $request, ReviewAssignment $assignment)
    {
        // Authorization
        if ($assignment->reviewer_id !== Auth::id()) {
            abort(403, 'You are not assigned to review this paper.');
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
            'attachment' => 'nullable|file|max:5120', // Max 5MB
            'is_confidential' => 'boolean',
        ]);
        
        // Handle attachment upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = 'review_' . time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('reviews', $filename, 'public');
        }
        
        // Create or update review
        $review = $assignment->review ?? new Review();
        $review->fill($validated);
        $review->assignment_id = $assignment->id;
        $review->attachment_path = $attachmentPath;
        $review->reviewed_at = now();
        $review->save();
        
        // Update assignment status
        $assignment->status = 'completed';
        $assignment->completed_date = now();
        $assignment->save();
        
        return redirect()->route('reviewer.assignments.completed')
            ->with('success', 'Review submitted successfully!');
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        // Authorization
        $user = Auth::user();
        $assignment = $review->assignment;
        
        $canView = false;
        
        // Author can view non-confidential reviews of their paper
        if ($assignment->paper->author_id === $user->id && !$review->is_confidential) {
            $canView = true;
        }
        
        // Editors and admins can view all reviews
        if ($user->hasRole('editor') || $user->hasRole('admin')) {
            $canView = true;
        }
        
        // Reviewer can view their own review
        if ($assignment->reviewer_id === $user->id) {
            $canView = true;
        }
        
        if (!$canView) {
            abort(403, 'You do not have permission to view this review.');
        }
        
        $review->load(['assignment.paper', 'assignment.reviewer']);
        $assignment = $review->assignment;
        $paper = $assignment->paper;

        return view('reviews.show', compact('review', 'assignment', 'paper'));
    }

    /**
     * Show the form for editing a review.
     */
/**
 * Show the form for editing a review.
 */
    public function edit(Review $review)
    {
        // Authorization: Only the original reviewer can edit
        if ($review->assignment->reviewer_id !== Auth::id()) {
            abort(403, 'You can only edit your own reviews.');
        }
        
        // Maybe restrict editing after certain time
        $assignment = $review->assignment;
        $paper = $assignment->paper;
        
        return view('reviews.edit', compact('review', 'assignment', 'paper'));
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, Review $review)
    {
        // Authorization
        if ($review->assignment->reviewer_id !== Auth::id()) {
            abort(403, 'You can only edit your own reviews.');
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
            'attachment' => 'nullable|file|max:5120',
            'is_confidential' => 'boolean',
        ]);
        
        // Handle new attachment upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($review->attachment_path && Storage::disk('public')->exists($review->attachment_path)) {
                Storage::disk('public')->delete($review->attachment_path);
            }
            
            $file = $request->file('attachment');
            $filename = 'review_' . time() . '_' . $file->getClientOriginalName();
            $validated['attachment_path'] = $file->storeAs('reviews', $filename, 'public');
        }
        
        $review->update($validated);
        $review->reviewed_at = now(); // Update review timestamp
        $review->save();
        
        return redirect()->route('reviews.show', $review)
            ->with('success', 'Review updated successfully.');
    }

    
    
}