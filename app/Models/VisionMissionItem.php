<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisionMissionItem extends Model
{
    protected $fillable = [
        'visions_mission_id',
        'type',
        'content',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function visionMission()
    {
        return $this->belongsTo(VisionsMission::class, 'visions_mission_id');
    }
}