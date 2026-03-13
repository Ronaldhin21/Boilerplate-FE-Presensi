<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Crypt;

class Siswa extends Model
{
    protected $table = 'siswas';

    protected $fillable = [
        'nis',
        'first_name',
        'last_name',
        'father_name',
        'mother_name',
        'place_of_birth',
        'date_of_birth',
        'religion',
        'kelas',
        'rombel_kelas',
        'alamat',
        'nomor_telepon',
        'email',
        'password',
        'password_plain_encrypted',
        'otp_code',
        'otp_expires_at',
        'email_verified_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'otp_expires_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'password_plain_encrypted',
        'otp_code',
    ];

    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'siswa_nis', 'nis');
    }

    public function getNamaLengkapAttribute(): string
    {
        return trim(($this->first_name ?? '').' '.($this->last_name ?? ''));
    }

    public function getPasswordRegisterAttribute(): ?string
    {
        if (!$this->password_plain_encrypted) {
            return null;
        }

        try {
            return Crypt::decryptString($this->password_plain_encrypted);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $q) use ($search) {
            $q->where('nis', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('father_name', 'like', "%{$search}%")
                ->orWhere('mother_name', 'like', "%{$search}%")
                ->orWhere('alamat', 'like', "%{$search}%")
                ->orWhere('nomor_telepon', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at');
    }
}
