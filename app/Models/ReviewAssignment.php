<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewAssignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'paper_id',
        'reviewer_id',
        'assigned_by',
        'status',
        'assigned_date',
        'due_date',
        'completed_date',
        'editor_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'assigned_date' => 'date',
        'due_date' => 'date',
        'completed_date' => 'date',
    ];

    /**
     * Get the paper being reviewed.
     */
    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    /**
     * Get the reviewer assigned.
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Get the editor who assigned this review.
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Get the review for this assignment.
     */
    public function review()
    {
        return $this->hasOne(Review::class, 'assignment_id');
    }

    /**
     * Check if assignment is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if assignment is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if assignment is overdue.
     */
    public function isOverdue()
    {
        return $this->status === 'pending' && $this->due_date < now();
    }

    /**
     * Check if reviewer has submitted review.
     */
    public function hasReview()
    {
        return $this->review()->exists();
    }

    /**
     * Scope for pending assignments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for completed assignments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for assignments by reviewer.
     */
    public function scopeByReviewer($query, $reviewerId)
    {
        return $query->where('reviewer_id', $reviewerId);
    }

    /**
     * Scope for overdue assignments.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
                     ->where('due_date', '<', now());
    }
}