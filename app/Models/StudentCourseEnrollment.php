<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentCourseEnrollment extends Model
{
    protected $fillable = [
        'student_id',
        'course_level_id',
        'order_id',
        'enrollment_source',
        'created_by',
        'status',
        'progress_percentage',
        'enrolled_at',
        'completed_at',
        'expired_at',
        'note',
    ];

    protected $casts = [
        'progress_percentage' => 'decimal:2',
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function courseLevel(): BelongsTo
    {
        return $this->belongsTo(CourseLevel::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function moduleProgress(): HasMany
    {
        return $this->hasMany(StudentModuleProgress::class, 'enrollment_id');
    }
}
