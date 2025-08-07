<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Problem extends Model
{
    protected $fillable = [
        'contest_id',
        'problem_id',
        'name',
        'index',
        'rating',
        'tags',
        'solved_count',
        'type',
        'points',
        'problem_url'
    ];

    protected $casts = [
        'rating' => 'integer',
        'tags' => 'array',
        'solved_count' => 'integer',
        'points' => 'integer'
    ];

    /**
     * Get the contest that owns the problem.
     */
    public function contest(): BelongsTo
    {
        return $this->belongsTo(Contest::class);
    }

    /**
     * Get the submissions for this problem.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Get successful submissions for this problem.
     */
    public function successfulSubmissions(): HasMany
    {
        return $this->submissions()->where('verdict', 'OK');
    }

    /**
     * Get the difficulty level based on rating.
     */
    public function getDifficultyLevelAttribute(): string
    {
        if (!$this->rating) return 'Unknown';
        
        if ($this->rating >= 3000) return 'Legendary';
        if ($this->rating >= 2600) return 'Extreme';
        if ($this->rating >= 2400) return 'Very Hard';
        if ($this->rating >= 2100) return 'Hard';
        if ($this->rating >= 1800) return 'Medium-Hard';
        if ($this->rating >= 1500) return 'Medium';
        if ($this->rating >= 1200) return 'Easy-Medium';
        if ($this->rating >= 900) return 'Easy';
        
        return 'Very Easy';
    }

    /**
     * Get the problem URL.
     */
    public function getUrlAttribute(): string
    {
        if ($this->problem_url) {
            return $this->problem_url;
        }
        
        if ($this->contest) {
            return "https://codeforces.com/contest/{$this->contest->contest_id}/problem/{$this->index}";
        }
        
        return '';
    }
}
