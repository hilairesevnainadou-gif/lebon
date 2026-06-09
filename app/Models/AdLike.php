<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdLike extends Model
{
    const UPDATED_AT = null;

    protected $table = 'ad_likes';

    protected $fillable = ['ad_id', 'ip_address'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }
}
