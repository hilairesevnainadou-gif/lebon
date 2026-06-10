<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'ad_id', 'first_name', 'last_name', 'email', 'phone', 'message', 'status', 'token',
    ];

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }
}
