<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModulePracticeQuestion extends Model
{
    protected $fillable = [
        'module_practice_id',
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

    public function practice(): BelongsTo
    {
        return $this->belongsTo(ModulePractice::class, 'module_practice_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(ModulePracticeQuestionOption::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ModulePracticeAnswer::class);
    }
}
