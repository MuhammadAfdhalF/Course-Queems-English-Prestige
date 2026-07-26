<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Enums\AssessmentResultMode;

class FreeTestResult extends Model
{
    protected $fillable = [
        'free_test_id',
        'participant_name',
        'participant_email',
        'participant_whatsapp',
        'raw_score',
        'max_score',
        'percentage_score',
        'result_mode',
        'passing_score',
        'is_passed',
        'total_score',
        'recommendation',
        'submitted_at',
    ];

    protected $casts = [
        'raw_score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'percentage_score' => 'decimal:2',
        'result_mode' => AssessmentResultMode::class,
        'passing_score' => 'decimal:2',
        'is_passed' => 'boolean',
        'total_score' => 'integer',
        'submitted_at' => 'datetime',
    ];

    public function freeTest(): BelongsTo
    {
        return $this->belongsTo(FreeTest::class);
    }
}