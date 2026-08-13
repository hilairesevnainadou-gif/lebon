<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_id',
        'name',
    ];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    /**
     * Synchronise les équipements pour une annonce
     */
    public static function syncForAd(int $adId, array $features): void
    {
        // Supprimer les anciens équipements
        self::where('ad_id', $adId)->delete();

        // Ajouter les nouveaux équipements
        foreach ($features as $feature) {
            if (!empty($feature)) {
                self::create([
                    'ad_id' => $adId,
                    'name' => trim($feature),
                ]);
            }
        }
    }
}
