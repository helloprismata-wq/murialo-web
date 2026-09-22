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
        Schema::create('paket_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->text('petunjuk')->nullable();
            $table->unsignedInteger('durasi_menit');
            $table->unsignedInteger('total_skor_maksimum')->default(0);
            $table->string('status', 20)->default('draft'); // draft, aktif, arsip
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('paket_tes_soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_tes_id')->constrained('paket_tes')->cascadeOnDelete();
            $table->foreignId('soal_id')->constrained('soal')->cascadeOnDelete();
            $table->unsignedInteger('urutan')->default(1);
            $table->timestamps();

            $table->unique(['paket_tes_id', 'soal_id']);
        });

        Schema::create('lowongan_paket_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lowongan_id')->constrained('lowongan')->cascadeOnDelete();
            $table->foreignId('paket_tes_id')->constrained('paket_tes')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['lowongan_id', 'paket_tes_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lowongan_paket_tes');
        Schema::dropIfExists('paket_tes_soal');
        Schema::dropIfExists('paket_tes');
    }
};
