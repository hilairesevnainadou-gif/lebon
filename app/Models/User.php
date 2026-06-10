<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'invitation_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_admin'          => 'boolean',
    ];

    // ── Relations ────────────────────────────────────────────

    public function seller(): HasOne
    {
        return $this->hasOne(Seller::class);
    }

    // ── Helpers ───────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function isActivated(): bool
    {
        return $this->invitation_token === null;
    }

    public function hasSeller(): bool
    {
        return $this->seller()->exists();
    }

    public function getOrCreateSeller(array $data = []): Seller
    {
        if ($this->seller) {
            return $this->seller;
        }

        return $this->seller()->create(array_merge([
            'first_name' => explode(' ', $this->name)[0] ?? $this->name,
            'last_name'  => explode(' ', $this->name, 2)[1] ?? '',
            'email'      => $this->email,
        ], $data));
    }
}
