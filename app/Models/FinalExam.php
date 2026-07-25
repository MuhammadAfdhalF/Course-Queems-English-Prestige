<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinalExam extends Model
{
    protected $fillable = [
        'course_level_id',
        'title',
        'description',
        'passing_grade',
        'grading_method',
        'max_attempts',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'passing_grade' => 'integer',
        'max_attempts' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function courseLevel(): BelongsTo
    {
        return $this->belongsTo(CourseLevel::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(FinalExamQuestion::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(FinalExamAttempt::class);
    }
}
