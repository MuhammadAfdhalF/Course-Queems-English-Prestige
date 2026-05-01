<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = [
        'course_level_id',
        'title',
        'slug',
        'short_description',
        'opening_media_type',
        'opening_media_file',
        'sort_order',
        'is_preview',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_preview' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function courseLevel(): BelongsTo
    {
        return $this->belongsTo(CourseLevel::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(ModuleMaterial::class);
    }

    public function practices(): HasMany
    {
        return $this->hasMany(ModulePractice::class);
    }
}
