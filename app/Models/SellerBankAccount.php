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
        'iban',
        'bic',
        'bank_name',
        'account_holder_name',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // ── Relations ────────────────────────────────────────────

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    // ── Accessors ─────────────────────────────────────────────

    public function getMaskedIbanAttribute(): string
    {
        $clean = preg_replace('/\s+/', '', $this->iban);
        return substr($clean, 0, 4)
             . ' **** **** **** **** '
             . substr($clean, -4);
    }

    // ── Helpers ───────────────────────────────────────────────

    public function setAsDefault(): void
    {
        static::where('seller_id', $this->seller_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }
}
