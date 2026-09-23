<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AiProcessingResult extends Model
{
    use HasFactory;

    protected $table = 'ai_processing_results';

    protected $fillable = [
        'tipe',
        'status',
        'referensi_type',
        'referensi_id',
        'payload_input',
        'payload_output',
        'score',
        'model_version',
        'error_message',
        'execution_time_ms',
        'dispatched_at',
        'completed_at',
    ];

    protected $casts = [
        'payload_input' => 'array',
        'payload_output' => 'array',
        'score' => 'float',
        'execution_time_ms' => 'integer',
        'dispatched_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Relasi polimorfik ke model referensi (PenugasanTes, Lamaran, dll.)
     */
    public function referensi(): MorphTo
    {
        return $this->morphTo();
    }
}
