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
        Schema::create('penugasan_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lamaran_id')->constrained('lamaran')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('paket_tes_id')->constrained('paket_tes')->cascadeOnDelete();
            $table->json('paket_snapshot');
            $table->dateTime('waktu_tersedia');
            $table->dateTime('batas_waktu');
            $table->string('status_pengerjaan', 30)->default('belum_dimulai'); // belum_dimulai, sedang_mengerjakan, selesai, kedaluwarsa
            $table->string('status_penilaian', 30)->default('belum_dinilai'); // belum_dinilai, sedang_dinilai, selesai_dinilai
            $table->boolean('is_published')->default(false);
            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            $table->unique(['lamaran_id', 'paket_tes_id']);
            $table->index(['user_id', 'status_pengerjaan']);
        });

        Schema::create('percobaan_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penugasan_tes_id')->unique()->constrained('penugasan_tes')->cascadeOnDelete();
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_berakhir')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->string('status', 30)->default('sedang_mengerjakan'); // sedang_mengerjakan, diserahkan, waktu_habis
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        Schema::create('jawaban_kandidat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('percobaan_tes_id')->constrained('percobaan_tes')->cascadeOnDelete();
            $table->unsignedBigInteger('soal_id');
            $table->unsignedInteger('urutan')->default(1);
            $table->text('jawaban')->nullable();
            $table->dateTime('terakhir_disimpan_pada')->nullable();
            $table->timestamps();

            $table->unique(['percobaan_tes_id', 'soal_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_kandidat');
        Schema::dropIfExists('percobaan_tes');
        Schema::dropIfExists('penugasan_tes');
    }
};
