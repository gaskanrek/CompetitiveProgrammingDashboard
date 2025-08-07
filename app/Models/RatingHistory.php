<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RatingHistory extends Model
{
    protected $fillable = [
        'codeforces_handle_id',
        'contest_id',
        'contest_name',
        'old_rating',
        'new_rating',
        'rank',
        'rating_update_time'
    ];

    protected $casts = [
        'old_rating' => 'integer',
        'new_rating' => 'integer',
        'rank' => 'integer',
        'rating_update_time' => 'datetime'
    ];

    /**
     * Get the codeforces handle that owns the rating history.
     */
    public function codeforcesHandle(): BelongsTo
    {
        return $this->belongsTo(CodeforcesHandle::class);
    }

    /**
     * Get the contest that owns the rating history.
     */
    public function contest(): BelongsTo
    {
        return $this->belongsTo(Contest::class);
    }

    /**
     * Get rating change.
     */
    public function getRatingChangeAttribute(): int
    {
        return $this->new_rating - $this->old_rating;
    }

    /**
     * Check if rating increased.
     */
    public function isRatingIncrease(): bool
    {
        return $this->getRatingChangeAttribute() > 0;
    }

    /**
     * Check if rating decreased.
     */
    public function isRatingDecrease(): bool
    {
        return $this->getRatingChangeAttribute() < 0;
    }

    /**
     * Get rating change color.
     */
    public function getRatingChangeColorAttribute(): string
    {
        $change = $this->getRatingChangeAttribute();
        
        if ($change > 0) return '#00ff00';
        if ($change < 0) return '#ff0000';
        
        return '#808080';
    }
}
