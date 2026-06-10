<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_id',
        'brand',
        'model',
        'year',
        'mileage',
        'fuel_type',
        'gearbox',
        'finish',
        'version',
        'doors',
        'seats',
        'body_type',
        'color',
        'din_power',
        'fiscal_power',
        'critair',
        'upholstery',
        'first_registration_date',
        'condition',
        'history',
        'license',
    ];

    protected $casts = [
        'first_registration_date' => 'date',
        'mileage'                 => 'integer',
        'doors'                   => 'integer',
        'seats'                   => 'integer',
        'din_power'               => 'integer',
        'fiscal_power'            => 'integer',
        'critair'                 => 'integer',
        'year'                    => 'integer',
    ];

    // ── Relations ────────────────────────────────────────────

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    // ── Accessors ─────────────────────────────────────────────

    public function getFormattedMileageAttribute(): string
    {
        return number_format($this->mileage, 0, ',', ' ') . ' km';
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->brand} {$this->model} {$this->year}";
    }

    public function getAgeAttribute(): int
    {
        return now()->year - $this->year;
    }
}
