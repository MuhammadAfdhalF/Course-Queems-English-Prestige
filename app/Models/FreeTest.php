<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreeTest extends Model
{
    protected $fillable = [
        'free_test_category_id',
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
    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(FreeTestCategory::class, 'free_test_category_id');
    }
}
