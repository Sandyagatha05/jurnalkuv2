<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewAssignment;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index()
    {
        $reviews = Review::with(['assignment.paper', 'assignment.reviewer'])
            ->latest()
            ->paginate(20);
        
        return view('editor.reviews.index', compact('reviews'));
    }

    /**
     * Display pending reviews.
     */
    public function pending()
    {
        $assignments = ReviewAssignment::with(['paper.author', 'reviewer'])
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->paginate(20);
        
        return view('editor.reviews.pending', compact('assignments'));
    }

    /**
     * Display completed reviews.
     */
    public function completed()
    {
        $reviews = Review::with(['assignment.paper', 'assignment.reviewer'])
            ->latest()
            ->paginate(20);
        
        return view('editor.reviews.completed', compact('reviews'));
    }

    /**
     * Display overdue reviews.
     */
    public function overdue()
    {
        $assignments = ReviewAssignment::with(['paper.author', 'reviewer'])
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->orderBy('due_date')
            ->paginate(20);
        
        return view('editor.reviews.overdue', compact('assignments'));
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        $review->load(['assignment.paper.author', 'assignment.reviewer']);
        
        return view('editor.reviews.show', compact('review'));
    }
}