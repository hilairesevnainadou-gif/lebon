<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'role_id',
        'invitation_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_admin'          => 'boolean',
        'otp_expires_at'    => 'datetime',
    ];

    // ── OTP de connexion ─────────────────────────────────────

    public function generateOtp(): string
    {
        $code = (string) random_int(100000, 999999);

        $this->forceFill([
            'otp_code'       => bcrypt($code),
            'otp_expires_at' => now()->addMinutes(10),
            'otp_attempts'   => 0,
        ])->save();

        return $code;
    }

    public function verifyOtp(string $code): bool
    {
        if (!$this->otp_code || !$this->otp_expires_at || $this->otp_expires_at->isPast()) {
            return false;
        }

        if ($this->otp_attempts >= 5) {
            return false;
        }

        if (!password_verify($code, $this->otp_code)) {
            $this->increment('otp_attempts');
            return false;
        }

        $this->clearOtp();
        return true;
    }

    public function clearOtp(): void
    {
        $this->forceFill([
            'otp_code'       => null,
            'otp_expires_at' => null,
            'otp_attempts'   => 0,
        ])->save();
    }

    // ── Relations ────────────────────────────────────────────

    public function seller(): HasOne
    {
        return $this->hasOne(Seller::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    // ── Helpers ───────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function hasPermission(string $slug): bool
    {
        return $this->isAdmin() || $this->permissions->contains('slug', $slug);
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
