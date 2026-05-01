<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    protected $fillable = [
        'whatsapp_label',
        'whatsapp_link',
        'email_label',
        'email_link',
        'operational_hours',
        'address',
        'map_embed_url',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    public function socialLinks(): HasMany
    {
        return $this->hasMany(ContactSocialLink::class);
    }
}