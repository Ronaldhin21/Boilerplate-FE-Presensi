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
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_code_id')->constrained()->onDelete('cascade');
            $table->string('siswa_nis'); // NIS siswa
            $table->string('siswa_nama'); // Nama siswa
            $table->dateTime('waktu_presensi'); // Waktu siswa melakukan presensi
            $table->enum('status', ['hadir', 'terlambat', 'tidak_hadir'])->default('hadir');
            $table->string('keterangan')->nullable(); // Keterangan tambahan
            $table->timestamps();

            // Unique constraint: satu siswa hanya bisa presensi sekali untuk satu QR code
            $table->unique(['qr_code_id', 'siswa_nis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
