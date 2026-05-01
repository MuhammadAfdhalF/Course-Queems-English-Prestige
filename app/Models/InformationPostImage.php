<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InformationPostImage extends Model
{
    protected $fillable = [
        'information_post_id',
        'image',
        'caption',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function informationPost(): BelongsTo
    {
        return $this->belongsTo(InformationPost::class);
    }
}