<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'institution',
        'department',
        'phone',
        'address',
        'biography',
        'orcid_id',
        'google_scholar_id',
        'scopus_id',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========== RELATIONSHIPS ==========
    
    /**
     * Get the papers submitted by this user (as author).
     */
    public function papers()
    {
        return $this->hasMany(Paper::class, 'author_id');
    }

    /**
     * Get the review assignments for this user (as reviewer).
     */
    public function reviewAssignments()
    {
        return $this->hasMany(ReviewAssignment::class, 'reviewer_id');
    }

    /**
     * Get the reviews done by this user (through assignments).
     */
    public function reviews()
    {
        return $this->hasManyThrough(Review::class, ReviewAssignment::class, 'reviewer_id', 'assignment_id');
    }

    /**
     * Get the editorials written by this user.
     */
    public function editorials()
    {
        return $this->hasMany(Editorial::class, 'author_id');
    }

    /**
     * Get the issues managed by this user (as editor).
     */
    public function managedIssues()
    {
        return $this->hasMany(Issue::class, 'editor_id');
    }

    /**
     * Get the assignments made by this user (as editor assigning reviewers).
     */
    public function assignedReviews()
    {
        return $this->hasMany(ReviewAssignment::class, 'assigned_by');
    }

    /**
     * Get pending review assignments for this user.
     */
    public function pendingReviewAssignments()
    {
        return $this->reviewAssignments()->where('status', 'pending');
    }

    /**
     * Get completed review assignments for this user.
     */
    public function completedReviewAssignments()
    {
        return $this->reviewAssignments()->where('status', 'completed');
    }

    // ========== HELPER METHODS ==========
    
    /**
     * Check if user is an author.
     */
    public function isAuthor()
    {
        return $this->hasRole('author');
    }

    /**
     * Check if user is a reviewer.
     */
    public function isReviewer()
    {
        return $this->hasRole('reviewer');
    }

    /**
     * Check if user is an editor.
     */
    public function isEditor()
    {
        return $this->hasRole('editor');
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Get user's full name with institution.
     */
    public function getFullNameWithInstitutionAttribute()
    {
        if ($this->institution) {
            return "{$this->name} ({$this->institution})";
        }
        
        return $this->name;
    }

    /**
     * Get user's active review count.
     */
    public function getActiveReviewCountAttribute()
    {
        return $this->pendingReviewAssignments()->count();
    }

    /**
     * Get user's paper statistics.
     */
    public function getPaperStatisticsAttribute()
    {
        return [
            'submitted' => $this->papers()->count(),
            'under_review' => $this->papers()->where('status', 'under_review')->count(),
            'accepted' => $this->papers()->where('status', 'accepted')->count(),
            'published' => $this->papers()->where('status', 'published')->count(),
        ];
    }
}