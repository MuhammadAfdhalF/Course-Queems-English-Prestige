<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisionsMission extends Model
{
    protected $table = 'visions_missions';

    protected $fillable = [
        'vision',
        'mission',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
