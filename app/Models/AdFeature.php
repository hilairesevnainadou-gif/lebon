<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_id',
        'label',
    ];

    // ── Relations ────────────────────────────────────────────

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    // ── Helpers ───────────────────────────────────────────────

    public static function syncForAd(int $adId, array $labels): void
    {
        static::where('ad_id', $adId)->delete();

        $records = array_map(fn($label) => [
            'ad_id'      => $adId,
            'label'      => $label,
            'created_at' => now(),
            'updated_at' => now(),
        ], $labels);

        static::insert($records);
    }
}
