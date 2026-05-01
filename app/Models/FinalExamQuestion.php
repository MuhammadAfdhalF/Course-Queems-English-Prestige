<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinalExamQuestion extends Model
{
    protected $fillable = [
        'final_exam_id',
        'question_type',
        'question',
        'explanation',
        'score',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function finalExam(): BelongsTo
    {
        return $this->belongsTo(FinalExam::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(FinalExamQuestionOption::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(FinalExamAnswer::class);
    }
}
