<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswas';

    protected $fillable = [
        'kelas',
        'rombel',
        'data',
    ];

    protected $casts = [
        'data' => 'array', // Auto-cast JSON to array
    ];

    /**
     * Relasi dengan presensi
     * Gunakan NIS dari JSON data
     */
    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'siswa_nis', 'data->nis');
    }

    /**
     * Accessor untuk mendapatkan field dari JSON data
     */
    public function __get($key)
    {
        // Cek dulu di attribute biasa
        $value = parent::__get($key);

        // Jika tidak ada, coba cari di JSON data
        if ($value === null && isset($this->data[$key])) {
            return $this->data[$key];
        }

        return $value;
    }

    /**
     * Scope untuk pencarian di JSON data
     */
    public function scopeSearchInData($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            // SQLite: gunakan LIKE pada kolom data (JSON as text)
            $q->where('data', 'LIKE', "%{$search}%");
        });
    }
}
