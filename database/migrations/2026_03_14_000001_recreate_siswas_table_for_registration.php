<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('siswas');

        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 50)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->string('father_name', 150);
            $table->string('mother_name', 150);
            $table->string('place_of_birth', 100);
            $table->date('date_of_birth');
            $table->string('religion', 50);
            $table->string('kelas', 20)->index();
            $table->string('rombel_kelas', 50)->index();
            $table->string('alamat', 255);
            $table->string('nomor_telepon', 30);
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->string('otp_code')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();

            $table->index(['kelas', 'rombel_kelas']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswas');

        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('kelas')->nullable()->index();
            $table->string('rombel')->nullable()->index();
            $table->json('data');
            $table->timestamps();
            $table->index(['kelas', 'rombel']);
        });
    }
};
