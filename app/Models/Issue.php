<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'volume',
        'number',
        'year',
        'title',
        'description',
        'published_date',
        'status',
        'editor_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_date' => 'date',
    ];

    /**
     * Get the editor of this issue.
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    /**
     * Get the editorial for this issue.
     */
    public function editorial()
    {
        return $this->hasOne(Editorial::class);
    }

    /**
     * Get all papers for this issue.
     */
    public function papers()
    {
        return $this->hasMany(Paper::class);
    }

    /**
     * Get only published papers for this issue.
     */
    public function publishedPapers()
    {
        return $this->hasMany(Paper::class)->where('status', 'published');
    }

    /**
     * Get accepted papers for this issue.
     */
    public function acceptedPapers()
    {
        return $this->hasMany(Paper::class)->where('status', 'accepted');
    }

    /**
     * Scope for published issues.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for draft issues.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Get the full issue identifier (Vol. No. Year).
     */
    public function getFullIdentifierAttribute()
    {
        return "Vol. {$this->volume}, No. {$this->number} ({$this->year})";
    }

    /**
     * Check if issue is published.
     */
    public function isPublished()
    {
        return $this->status === 'published';
    }

    /**
     * Check if issue has editorial.
     */
    public function hasEditorial()
    {
        return $this->editorial()->exists();
    }
}