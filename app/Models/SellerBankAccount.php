<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerBankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'ad_id',
        'iban',
        'bic',
        'bank_name',
        'account_holder_name',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // ─────────────────────────────────────────────
    // Relations
    // ─────────────────────────────────────────────

    /**
     * Vendeur propriétaire du compte.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Annonce à laquelle appartient ce compte bancaire.
     */
    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    // ─────────────────────────────────────────────
    // Accessors
    // ─────────────────────────────────────────────

    public function getMaskedIbanAttribute(): string
    {
        $clean = preg_replace('/\s+/', '', (string) $this->iban);

        if (strlen($clean) <= 8) {
            return $clean;
        }

        return substr($clean, 0, 4)
            . ' **** **** **** **** '
            . substr($clean, -4);
    }

    // ─────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────

    /**
     * Définit ce compte comme compte par défaut
     * pour son vendeur.
     */
    public function setAsDefault(): void
    {
        static::where('seller_id', $this->seller_id)
            ->where('id', '!=', $this->id)
            ->update([
                'is_default' => false,
            ]);

        $this->update([
            'is_default' => true,
        ]);
    }
}
