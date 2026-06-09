<?php

namespace App\Models;

use App\Models\AdFeature;
use App\Models\AdLike;
use App\Models\AdPhoto;
use App\Models\Seller;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'title',
        'description',
        'price',
        'city',
        'postal_code',
        'likes_count',
        'status',
        'published_at',
        'share_token',
        'views',
    ];

    protected $casts = [
        'price'        => 'decimal:2',
        'published_at' => 'datetime',
        'views'        => 'integer',
        'likes_count'  => 'integer',
    ];

    // ── Relations ────────────────────────────────────────────

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function vehicle(): HasOne
    {
        return $this->hasOne(Vehicle::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(AdPhoto::class)->orderBy('order');
    }

    public function features(): HasMany
    {
        return $this->hasMany(AdFeature::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(AdLike::class);
    }

    // ── Scopes ────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeSold(Builder $query): Builder
    {
        return $query->where('status', 'sold');
    }

    public function scopeByCity(Builder $query, string $city): Builder
    {
        return $query->where('city', $city);
    }

    public function scopePriceBetween(Builder $query, float $min, float $max): Builder
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    // ── Accessors ─────────────────────────────────────────────

    public function getFormattedPriceAttribute(): string
    {
        return number_format((float) $this->price, 0, ',', ' ') . ' €';
    }

    public function getMainPhotoAttribute(): ?AdPhoto
    {
        return $this->photos->first();
    }

    // ── Helpers ───────────────────────────────────────────────

    public function markAsSold(): void
    {
        $this->update(['status' => 'sold']);
    }

    public function publish(): void
    {
        $this->update([
            'status'       => 'active',
            'published_at' => now(),
        ]);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function isLikedByIp(string $ip): bool
    {
        return $this->likes()->where('ip_address', $ip)->exists();
    }
}
