<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int         $id
 * @property int         $ad_id
 * @property string      $disk
 * @property string      $path
 * @property string      $original_name
 * @property string|null $mime_type
 * @property int|null    $size
 * @property int         $order
 *
 * @property-read string $url
 * @property-read string $formatted_size
 */
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

    protected $casts = [
        'order' => 'integer',
        'size'  => 'integer',
    ];

    // ── Relations ────────────────────────────────────────────

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    // ── Accessors ─────────────────────────────────────────────

    /**
     * URL publique complète de la photo.
     * Utilisable dans les vues : $photo->url
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    /**
     * Taille lisible : "2.4 Mo", "340 Ko"
     *
     * @return string
     */
    public function getFormattedSizeAttribute(): string
    {
        if (!$this->size) {
            return '—';
        }

        $units = ['o', 'Ko', 'Mo', 'Go'];
        $i     = (int) floor(log($this->size, 1024));

        return round($this->size / pow(1024, $i), 1) . ' ' . $units[$i];
    }

    // ── Helpers ───────────────────────────────────────────────

    /**
     * Supprime le fichier physique ET l'enregistrement en base.
     */
    public function deleteWithFile(): bool
    {
        Storage::disk($this->disk)->delete($this->path);

        return $this->delete();
    }

    /**
     * Réordonne les photos à partir d'un tableau d'IDs ordonnés.
     *
     * @param  array<int> $orderedIds
     */
    public static function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $position => $id) {
            static::where('id', $id)->update(['order' => $position]);
        }
    }

    /**
     * Supprime tous les fichiers physiques + enregistrements d'une annonce.
     */
    public static function deleteAllForAd(int $adId): void
    {
        $photos = static::where('ad_id', $adId)->get();

        foreach ($photos as $photo) {
            Storage::disk($photo->disk)->delete($photo->path);
        }

        static::where('ad_id', $adId)->delete();
    }
}
