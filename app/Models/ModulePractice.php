<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModulePractice extends Model
{
    protected $fillable = [
        'module_id',
        'title',
        'description',
        'passing_grade',
        'grading_method',
        'max_attempts',
        'is_required',
        'is_active',
    ];

    protected $casts = [
        'passing_grade' => 'integer',
        'max_attempts' => 'integer',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(ModulePracticeQuestion::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ModulePracticeAttempt::class);
    }
}