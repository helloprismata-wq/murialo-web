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
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penugasan_tes_id')->unique()->constrained('penugasan_tes')->cascadeOnDelete();
            $table->foreignId('penilai_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('total_skor_diperoleh', 6, 2)->default(0);
            $table->decimal('total_skor_maksimum', 6, 2)->default(0);
            $table->decimal('nilai_akhir', 6, 2)->default(0); // (total_skor_diperoleh / total_skor_maksimum) * 100
            $table->text('catatan_umum')->nullable();
            $table->dateTime('waktu_penilaian')->nullable();
            $table->timestamps();
        });

        Schema::create('penilaian_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilaian_id')->constrained('penilaian')->cascadeOnDelete();
            $table->foreignId('jawaban_kandidat_id')->constrained('jawaban_kandidat')->cascadeOnDelete();
            $table->unsignedBigInteger('soal_rubrik_id')->nullable();
            $table->string('kriteria');
            $table->decimal('skor_maksimum', 6, 2);
            $table->decimal('skor', 6, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('penilaian_riwayat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilaian_id')->constrained('penilaian')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('alasan_perubahan');
            $table->decimal('skor_sebelumnya', 6, 2);
            $table->decimal('skor_baru', 6, 2);
            $table->json('snapshot_perubahan');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_riwayat');
        Schema::dropIfExists('penilaian_detail');
        Schema::dropIfExists('penilaian');
    }
};
