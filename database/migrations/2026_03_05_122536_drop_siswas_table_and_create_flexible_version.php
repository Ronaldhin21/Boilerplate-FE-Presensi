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
        // Drop tabel lama
        Schema::dropIfExists('siswas');

        // Buat tabel baru dengan struktur fleksibel
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('kelas')->nullable()->index(); // Filter kelas
            $table->string('rombel')->nullable()->index(); // Filter rombel
            $table->json('data'); // Semua data dari CSV disimpan di sini
            $table->timestamps();

            // Index gabungan untuk filter kelas + rombel
            $table->index(['kelas', 'rombel']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');

        // Restore tabel struktur lama
        Schema::create('siswas', function (Blueprint $table) {
            $table->string('nis')->primary();
            $table->string('nama');
            $table->enum('kelas', ['10', '11', '12']);
            $table->string('rombel');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('alamat')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
            $table->index(['kelas', 'rombel']);
            $table->index('nama');
        });
    }
};
