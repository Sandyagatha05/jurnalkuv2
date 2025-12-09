<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'assignment_id',
        'comments_to_editor',
        'comments_to_author',
        'recommendation',
        'attachment_path',
        'originality_score',
        'contribution_score',
        'clarity_score',
        'methodology_score',
        'overall_score',
        'is_confidential',
        'reviewed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'reviewed_at' => 'date',
        'is_confidential' => 'boolean',
    ];

    /**
     * Get the assignment for this review.
     */
    public function assignment()
    {
        return $this->belongsTo(ReviewAssignment::class, 'assignment_id');
    }

    /**
     * Get the reviewer through assignment.
     */
    public function reviewer()
    {
        return $this->assignment->reviewer();
    }

    /**
     * Get the paper being reviewed.
     */
    public function paper()
    {
        return $this->assignment->paper();
    }

    /**
     * Calculate average score.
     */
    public function getAverageScoreAttribute()
    {
        $scores = [
            $this->originality_score,
            $this->contribution_score,
            $this->clarity_score,
            $this->methodology_score,
            $this->overall_score,
        ];
        
        $validScores = array_filter($scores);
        
        if (count($validScores) > 0) {
            return array_sum($validScores) / count($validScores);
        }
        
        return null;
    }

    /**
     * Get recommendation as text.
     */
    public function getRecommendationTextAttribute()
    {
        $recommendations = [
            'accept' => 'Accept',
            'minor_revision' => 'Minor Revision',
            'major_revision' => 'Major Revision',
            'reject' => 'Reject',
        ];
        
        return $recommendations[$this->recommendation] ?? $this->recommendation;
    }

    /**
     * Check if review recommends acceptance.
     */
    public function recommendsAcceptance()
    {
        return $this->recommendation === 'accept';
    }

    /**
     * Check if review recommends revision.
     */
    public function recommendsRevision()
    {
        return $this->recommendation === 'minor_revision' || $this->recommendation === 'major_revision';
    }

    /**
     * Check if review recommends rejection.
     */
    public function recommendsRejection()
    {
        return $this->recommendation === 'reject';
    }

    /**
     * Scope for reviews by recommendation.
     */
    public function scopeByRecommendation($query, $recommendation)
    {
        return $query->where('recommendation', $recommendation);
    }
}