<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QrCode extends Model
{
    protected $fillable = [
        'code',
        'hari',
        'tanggal',
        'waktu_mulai',
        'batas_hadir',
        'batas_terlambat',
        'is_active',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_active' => 'boolean',
    ];

    public function presensis(): HasMany
    {
        return $this->hasMany(Presensi::class);
    }
}
