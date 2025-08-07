<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CodeforcesHandle extends Model
{
    protected $fillable = [
        'user_id',
        'handle',
        'current_rating',
        'max_rating',
        'rank',
        'max_rank',
        'contribution',
        'organization',
        'country',
        'city',
        'avatar_url',
        'last_crawled_at',
        'is_active'
    ];

    protected $casts = [
        'current_rating' => 'integer',
        'max_rating' => 'integer',
        'contribution' => 'integer',
        'last_crawled_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * Get the user that owns the codeforces handle.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the submissions for this handle.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Get the rating histories for this handle.
     */
    public function ratingHistories(): HasMany
    {
        return $this->hasMany(RatingHistory::class);
    }

    /**
     * Get the contest participations for this handle.
     */
    public function contestParticipations(): HasMany
    {
        return $this->hasMany(ContestParticipation::class);
    }

    /**
     * Get the rank color based on rating.
     */
    public function getRankColorAttribute(): string
    {
        $rating = $this->current_rating;
        
        if ($rating >= 3000) return '#ff0000'; // legendary grandmaster
        if ($rating >= 2600) return '#ff8c00'; // international grandmaster
        if ($rating >= 2400) return '#ff0000'; // grandmaster
        if ($rating >= 2300) return '#ff8c00'; // international master
        if ($rating >= 2100) return '#ffc0cb'; // master
        if ($rating >= 1900) return '#ff69b4'; // candidate master
        if ($rating >= 1600) return '#0000ff'; // expert
        if ($rating >= 1400) return '#00ffff'; // specialist
        if ($rating >= 1200) return '#008000'; // pupil
        
        return '#808080'; // newbie
    }

    /**
     * Get solved problems count.
     */
    public function getSolvedProblemsCountAttribute(): int
    {
        return $this->submissions()->where('verdict', 'OK')->distinct('problem_id')->count();
    }
}
