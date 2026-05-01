<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModulePracticeQuestionOption extends Model
{
    protected $fillable = [
        'module_practice_question_id',
        'option_label',
        'option_text',
        'is_correct',
        'sort_order',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(ModulePracticeQuestion::class, 'module_practice_question_id');
    }
}
