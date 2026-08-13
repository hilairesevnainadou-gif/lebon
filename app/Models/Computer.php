<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Computer extends Model
{
    protected $fillable = [
        'ad_id',
        'brand',
        'model',
        'cpu',
        'ram_gb',
        'storage_type',
        'storage_gb',
        'gpu',
        'screen_size',
        'os',
        'condition',
        'color',
    ];

    protected $casts = [
        'ram_gb'      => 'integer',
        'storage_gb'  => 'integer',
        'screen_size' => 'decimal:1',
    ];

    // ── Relations ────────────────────────────────────────────

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    // ── Accessors ─────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return "{$this->brand} {$this->model}";
    }

    public function getFormattedStorageAttribute(): string
    {
        return "{$this->storage_gb} Go " . strtoupper($this->storage_type);
    }
}
