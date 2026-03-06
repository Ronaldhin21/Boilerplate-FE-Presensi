<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // QR code unique identifier
            $table->string('hari'); // Hari (Senin, Selasa, etc)
            $table->date('tanggal'); // Tanggal presensi
            $table->time('waktu_mulai'); // Waktu mulai presensi
            $table->time('batas_hadir'); // Batas waktu hadir (waktu_mulai + 30 menit)
            $table->time('batas_terlambat'); // Batas waktu terlambat (waktu_mulai + 60 menit)
            $table->boolean('is_active')->default(true); // Status aktif QR code
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
