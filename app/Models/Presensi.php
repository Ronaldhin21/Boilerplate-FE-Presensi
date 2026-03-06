<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presensi extends Model
{
    protected $fillable = [
        'qr_code_id',
        'siswa_nis',
        'siswa_nama',
        'waktu_presensi',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'waktu_presensi' => 'datetime',
    ];

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }
}
