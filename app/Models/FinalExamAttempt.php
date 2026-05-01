<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinalExamAttempt extends Model
{
    protected $fillable = [
        'student_id',
        'final_exam_id',
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

    public function finalExam(): BelongsTo
    {
        return $this->belongsTo(FinalExam::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(FinalExamAnswer::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }
}
