<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModulePracticeAttempt extends Model
{
    protected $fillable = [
        'student_id',
        'module_practice_id',
        'attempt_number',
        'total_score',
        'status',
        'started_at',
        'submitted_at',
        'graded_at',
    ];

    protected $casts = [
        'attempt_number' => 'integer',
        'total_score' => 'decimal:2',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function practice(): BelongsTo
    {
        return $this->belongsTo(ModulePractice::class, 'module_practice_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ModulePracticeAnswer::class);
    }
}
