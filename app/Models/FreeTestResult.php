<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreeTestResult extends Model
{
    protected $fillable = [
        'free_test_id',
        'participant_name',
        'participant_email',
        'participant_whatsapp',
        'total_score',
        'recommendation',
        'submitted_at',
    ];

    protected $casts = [
        'total_score' => 'integer',
        'submitted_at' => 'datetime',
    ];

    public function freeTest(): BelongsTo
    {
        return $this->belongsTo(FreeTest::class);
    }
}