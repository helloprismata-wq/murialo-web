<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('judul');
            $table->string('perusahaan');
            $table->string('lokasi');
            $table->string('tipe_pekerjaan', 30);
            $table->text('deskripsi');
            $table->text('persyaratan');
            $table->text('skills')->nullable();
            $table->unsignedBigInteger('gaji_min')->nullable();
            $table->unsignedBigInteger('gaji_max')->nullable();
            $table->date('batas_lamaran')->nullable();
            $table->string('status', 20)->default('draft');
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongan');
    }
};
