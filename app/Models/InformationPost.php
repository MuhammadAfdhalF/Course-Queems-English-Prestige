<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InformationPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'type',
        'thumbnail',
        'excerpt',
        'content',
        'external_url',
        'event_date',
        'published_at',
        'is_published',
    ];

    protected $casts = [
        'event_date' => 'date',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(InformationPostImage::class);
    }
}