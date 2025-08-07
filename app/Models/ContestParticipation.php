<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContestParticipation extends Model
{
    protected $fillable = [
        'codeforces_handle_id',
        'contest_id',
        'rank',
        'points',
        'penalty',
        'successful_hack_count',
        'unsuccessful_hack_count',
        'participation_time'
    ];

    protected $casts = [
        'rank' => 'integer',
        'points' => 'integer',
        'penalty' => 'integer',
        'successful_hack_count' => 'integer',
        'unsuccessful_hack_count' => 'integer',
        'participation_time' => 'datetime'
    ];

    /**
     * Get the codeforces handle that owns the participation.
     */
    public function codeforcesHandle(): BelongsTo
    {
        return $this->belongsTo(CodeforcesHandle::class);
    }

    /**
     * Get the contest that owns the participation.
     */
    public function contest(): BelongsTo
    {
        return $this->belongsTo(Contest::class);
    }

    /**
     * Get total hack count.
     */
    public function getTotalHackCountAttribute(): int
    {
        return $this->successful_hack_count + $this->unsuccessful_hack_count;
    }

    /**
     * Get hack success rate.
     */
    public function getHackSuccessRateAttribute(): float
    {
        $total = $this->getTotalHackCountAttribute();
        
        if ($total === 0) return 0;
        
        return ($this->successful_hack_count / $total) * 100;
    }

    /**
     * Get performance level based on rank percentile.
     */
    public function getPerformanceLevelAttribute(): string
    {
        // This would need the total participants count to calculate percentile
        // For now, we'll use rank ranges
        if ($this->rank <= 10) return 'Excellent';
        if ($this->rank <= 50) return 'Very Good';
        if ($this->rank <= 200) return 'Good';
        if ($this->rank <= 500) return 'Average';
        
        return 'Below Average';
    }
}
