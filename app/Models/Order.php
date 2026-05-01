<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'student_id',
        'course_level_id',
        'order_code',
        'price',
        'status',
        'order_date',
        'approved_at',
        'rejected_at',
        'note',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'order_date' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function courseLevel(): BelongsTo
    {
        return $this->belongsTo(CourseLevel::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function enrollment(): HasOne
    {
        return $this->hasOne(StudentCourseEnrollment::class);
    }
}
