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
        Schema::create('ai_processing_results', function (Blueprint $table) {
            $table->id();
            $table->string('tipe', 50); // 'smart_grading', 'skill_matching', dll.
            $table->string('status', 30)->default('pending'); // 'pending', 'processing', 'completed', 'failed'
            $table->string('referensi_type', 100); // e.g. App\Models\PenugasanTes, App\Models\Lamaran
            $table->unsignedBigInteger('referensi_id');
            $table->json('payload_input')->nullable();
            $table->json('payload_output')->nullable();
            $table->decimal('score', 6, 2)->nullable();
            $table->string('model_version', 100)->nullable();
            $table->text('error_message')->nullable();
            $table->integer('execution_time_ms')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['referensi_type', 'referensi_id']);
            $table->index(['tipe', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_processing_results');
    }
};
