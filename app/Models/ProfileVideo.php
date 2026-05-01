<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileVideo extends Model
{
    protected $fillable = [
        'title',
        'description',
        'video_file',
        'thumbnail',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];
}