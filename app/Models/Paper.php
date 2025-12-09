<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'abstract',
        'keywords',
        'doi',
        'file_path',
        'original_filename',
        'status',
        'author_id',
        'issue_id',
        'page_from',
        'page_to',
        'submitted_at',
        'reviewed_at',
        'published_at',
        'revision_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'submitted_at' => 'date',
        'reviewed_at' => 'date',
        'published_at' => 'date',
        'keywords' => 'array',
    ];

    /**
     * Get the author of this paper.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the issue this paper belongs to.
     */
    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }

    /**
     * Get all review assignments for this paper.
     */
    public function reviewAssignments()
    {
        return $this->hasMany(ReviewAssignment::class);
    }

    /**
     * Get all reviews for this paper (through assignments).
     */
    public function reviews()
    {
        return $this->hasManyThrough(Review::class, ReviewAssignment::class, 'paper_id', 'assignment_id');
    }

    /**
     * Get pending review assignments for this paper.
     */
    public function pendingReviews()
    {
        return $this->reviewAssignments()->where('status', 'pending');
    }

    /**
     * Get completed review assignments for this paper.
     */
    public function completedReviews()
    {
        return $this->reviewAssignments()->where('status', 'completed');
    }

    /**
     * Get the assigned reviewers for this paper.
     */
    public function reviewers()
    {
        return $this->belongsToMany(User::class, 'review_assignments', 'paper_id', 'reviewer_id')
                    ->withPivot('status', 'assigned_date', 'due_date')
                    ->withTimestamps();
    }

    /**
     * Check if paper is under review.
     */
    public function isUnderReview()
    {
        return $this->status === 'under_review';
    }

    /**
     * Check if paper needs revision.
     */
    public function needsRevision()
    {
        return $this->status === 'revision_minor' || $this->status === 'revision_major';
    }

    /**
     * Check if paper is accepted.
     */
    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if paper is published.
     */
    public function isPublished()
    {
        return $this->status === 'published';
    }

    /**
     * Get the review status summary.
     */
    public function getReviewStatusAttribute()
    {
        $total = $this->reviewAssignments()->count();
        $completed = $this->reviewAssignments()->where('status', 'completed')->count();
        
        if ($total === 0) {
            return 'No reviewers assigned';
        }
        
        return "{$completed}/{$total} reviews completed";
    }

    /**
     * Scope for papers by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for papers by author.
     */
    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    /**
     * Scope for papers under review.
     */
    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    /**
     * Scope for published papers.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for submitted papers.
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope for accepted papers.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }
}