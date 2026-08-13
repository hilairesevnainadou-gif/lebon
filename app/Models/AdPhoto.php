<?php

namespace App\Models;

use App\Models\Ad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AdPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_id',
        'disk',
        'path',
        'original_name',
        'mime_type',
        'size',
        'order',
    ];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    /**
     * Supprime le fichier physique
     */
    public function deleteWithFile(): bool
    {
        if ($this->path) {
            Storage::disk($this->disk ?? 'public')->delete($this->path);
        }
        return $this->delete();
    }

    /**
     * Supprime tous les fichiers d'une annonce
     */
    public static function deleteAllForAd(int $adId): void
    {
        $photos = self::where('ad_id', $adId)->get();

        foreach ($photos as $photo) {
            if ($photo->path) {
                Storage::disk($photo->disk ?? 'public')->delete($photo->path);
            }
            $photo->delete();
        }
    }
}
