<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FreeTest extends Model
{
    protected $fillable = [
        'title',
        'category',
        'description',
        'duration_minutes',
        'total_questions',
        'passing_grade',
        'is_active',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'total_questions' => 'integer',
        'passing_grade' => 'integer',
        'is_active' => 'boolean',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(FreeTestQuestion::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(FreeTestResult::class);
    }
}
