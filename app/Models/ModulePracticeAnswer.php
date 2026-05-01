<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModulePracticeAnswer extends Model
{
    protected $fillable = [
        'module_practice_attempt_id',
        'module_practice_question_id',
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
        return $this->belongsTo(ModulePracticeAttempt::class, 'module_practice_attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(ModulePracticeQuestion::class, 'module_practice_question_id');
    }

    public function selectedOption(): BelongsTo
    {
        return $this->belongsTo(ModulePracticeQuestionOption::class, 'selected_option_id');
    }
}