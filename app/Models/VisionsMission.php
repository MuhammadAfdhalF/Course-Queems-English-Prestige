<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisionsMission extends Model
{
    protected $fillable = [
        'vision',
        'mission',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function missionItems()
    {
        return $this->hasMany(VisionMissionItem::class, 'visions_mission_id')
            ->where('type', 'mission')
            ->orderBy('sort_order');
    }
}
