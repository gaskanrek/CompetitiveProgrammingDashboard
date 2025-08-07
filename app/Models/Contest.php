<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contest extends Model
{
    protected $fillable = [
        'contest_id',
        'name',
        'type',
        'phase',
        'frozen',
        'duration_seconds',
        'start_time',
        'relative_time_seconds',
        'prepared_by_id',
        'website_url',
        'description',
        'difficulty',
        'kind',
        'icpc_region',
        'country',
        'city',
        'season',
        'is_important'
    ];

    protected $casts = [
        'frozen' => 'boolean',
        'duration_seconds' => 'integer',
        'start_time' => 'datetime',
        'relative_time_seconds' => 'datetime',
        'prepared_by_id' => 'integer',
        'difficulty' => 'integer',
        'is_important' => 'boolean'
    ];

    /**
     * Get the problems for this contest.
     */
    public function problems(): HasMany
    {
        return $this->hasMany(Problem::class);
    }

    /**
     * Get the participations for this contest.
     */
    public function participations(): HasMany
    {
        return $this->hasMany(ContestParticipation::class);
    }

    /**
     * Get the rating histories for this contest.
     */
    public function ratingHistories(): HasMany
    {
        return $this->hasMany(RatingHistory::class);
    }

    /**
     * Check if contest is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->phase === 'BEFORE' && $this->start_time > now();
    }

    /**
     * Check if contest is running.
     */
    public function isRunning(): bool
    {
        return $this->phase === 'CODING';
    }

    /**
     * Check if contest is finished.
     */
    public function isFinished(): bool
    {
        return $this->phase === 'FINISHED';
    }

    /**
     * Get contest duration in hours.
     */
    public function getDurationInHours(): float
    {
        return $this->duration_seconds ? $this->duration_seconds / 3600 : 0;
    }
}
