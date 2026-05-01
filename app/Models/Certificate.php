<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'student_id',
        'course_level_id',
        'enrollment_id',
        'final_exam_attempt_id',
        'certificate_template_id',
        'certificate_number',
        'certificate_file',
        'issued_at',
        'status',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function courseLevel(): BelongsTo
    {
        return $this->belongsTo(CourseLevel::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(StudentCourseEnrollment::class, 'enrollment_id');
    }

    public function finalExamAttempt(): BelongsTo
    {
        return $this->belongsTo(FinalExamAttempt::class);
    }

    public function certificateTemplate(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class);
    }
}