<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editorial extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'author_id',
        'issue_id',
        'is_published',
        'published_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_date' => 'date',
        'is_published' => 'boolean',
    ];

    /**
     * Get the author of this editorial.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the issue this editorial belongs to.
     */
    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }

    /**
     * Scope for published editorials.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Check if editorial is published.
     */
    public function isPublished()
    {
        return $this->is_published;
    }

    /**
     * Get excerpt of content.
     */
    public function getExcerptAttribute($length = 200)
    {
        return strlen($this->content) > $length 
            ? substr($this->content, 0, $length) . '...' 
            : $this->content;
    }
}