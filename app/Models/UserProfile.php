<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nim',
        'full_name',
        'phone',
        'bio',
        'university',
        'major',
        'graduation_year'
    ];

    protected $casts = [
        'graduation_year' => 'integer'
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
