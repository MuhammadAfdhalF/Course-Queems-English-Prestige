<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinalExamAnswer extends Model
{
    protected $fillable = [
        'final_exam_attempt_id',
        'final_exam_question_id',
        'selected_option_id',
        'answer_text',
        'uploaded_file',
        'score',
        'feedback',
        'is_correct',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'is_correct' => 'boolean',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(FinalExamAttempt::class, 'final_exam_attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(FinalExamQuestion::class, 'final_exam_question_id');
    }

    public function selectedOption(): BelongsTo
    {
        return $this->belongsTo(FinalExamQuestionOption::class, 'selected_option_id');
    }
}