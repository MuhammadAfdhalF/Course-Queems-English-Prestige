<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseLevel extends Model
{
    protected $fillable = [
        'course_program_id',
        'name',
        'slug',
        'thumbnail_type',
        'thumbnail_file',
        'short_description',
        'description',
        'price',
        'access_type',
        'access_duration_days',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'access_duration_days' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function courseProgram(): BelongsTo
    {
        return $this->belongsTo(CourseProgram::class);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }
}
