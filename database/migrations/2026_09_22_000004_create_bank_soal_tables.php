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
        Schema::create('soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('kode_soal', 50)->unique();
            $table->string('judul');
            $table->string('kategori', 50); // pemahaman_informasi, instruksi, penalaran
            $table->text('teks_bacaan')->nullable();
            $table->text('pertanyaan');
            $table->unsignedInteger('skor_maksimum');
            $table->string('sumber')->nullable();
            $table->boolean('is_dummy')->default(false);
            $table->string('status', 20)->default('draft'); // draft, aktif, arsip
            $table->unsignedInteger('versi')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('soal_jawaban_acuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_id')->constrained('soal')->cascadeOnDelete();
            $table->text('jawaban');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('soal_rubrik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_id')->constrained('soal')->cascadeOnDelete();
            $table->string('kriteria');
            $table->unsignedInteger('poin_maksimum');
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });

        Schema::create('soal_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_id')->constrained('soal')->cascadeOnDelete();
            $table->unsignedInteger('versi');
            $table->json('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_snapshots');
        Schema::dropIfExists('soal_rubrik');
        Schema::dropIfExists('soal_jawaban_acuan');
        Schema::dropIfExists('soal');
    }
};
