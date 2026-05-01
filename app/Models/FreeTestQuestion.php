<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreeTestQuestion extends Model
{
    protected $fillable = [
        'free_test_id',
        'question_type',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'score',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'score' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function freeTest(): BelongsTo
    {
        return $this->belongsTo(FreeTest::class);
    }
}