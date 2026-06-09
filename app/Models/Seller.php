<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'city',
        'is_reactive',
        'last_active_at',
    ];

    protected $casts = [
        'is_reactive'    => 'boolean',
        'last_active_at' => 'datetime',
    ];

    // ── Relations ────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(SellerBankAccount::class);
    }

    public function defaultBankAccount(): HasOne
    {
        return $this->hasOne(SellerBankAccount::class)->where('is_default', true);
    }

    public function ads(): HasMany
    {
        return $this->hasMany(Ad::class);
    }

    // ── Accessors ─────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    // ── Helpers ───────────────────────────────────────────────

    public static function firstOrCreateFromData(array $data): static
    {
        $match = [];

        if (!empty($data['user_id'])) {
            $match['user_id'] = $data['user_id'];
        } elseif (!empty($data['email'])) {
            $match['email'] = $data['email'];
        }

        return static::firstOrCreate($match, $data);
    }

    public function activeAdsCount(): int
    {
        return $this->ads()->where('status', 'active')->count();
    }

    public function touchActivity(): void
    {
        $this->update(['last_active_at' => now()]);
    }
}
