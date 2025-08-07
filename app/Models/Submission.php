<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    protected $fillable = [
        'codeforces_handle_id',
        'problem_id',
        'submission_id',
        'creation_time',
        'author_handle',
        'programming_language',
        'verdict',
        'testset',
        'passed_test_count',
        'time_consumed_millis',
        'memory_consumed_bytes'
    ];

    protected $casts = [
        'submission_id' => 'integer',
        'creation_time' => 'datetime',
        'passed_test_count' => 'integer',
        'time_consumed_millis' => 'integer',
        'memory_consumed_bytes' => 'integer'
    ];

    /**
     * Get the codeforces handle that owns the submission.
     */
    public function codeforcesHandle(): BelongsTo
    {
        return $this->belongsTo(CodeforcesHandle::class);
    }

    /**
     * Get the problem that owns the submission.
     */
    public function problem(): BelongsTo
    {
        return $this->belongsTo(Problem::class);
    }

    /**
     * Check if submission is accepted.
     */
    public function isAccepted(): bool
    {
        return $this->verdict === 'OK';
    }

    /**
     * Get verdict color.
     */
    public function getVerdictColorAttribute(): string
    {
        return match($this->verdict) {
            'OK' => '#00ff00',
            'WRONG_ANSWER' => '#ff0000',
            'TIME_LIMIT_EXCEEDED' => '#ff8c00',
            'MEMORY_LIMIT_EXCEEDED' => '#ff69b4',
            'COMPILATION_ERROR' => '#800080',
            'RUNTIME_ERROR' => '#ff1493',
            default => '#808080'
        };
    }

    /**
     * Get time consumed in seconds.
     */
    public function getTimeConsumedSecondsAttribute(): float
    {
        return $this->time_consumed_millis / 1000;
    }

    /**
     * Get memory consumed in MB.
     */
    public function getMemoryConsumedMbAttribute(): float
    {
        return $this->memory_consumed_bytes / (1024 * 1024);
    }
}
