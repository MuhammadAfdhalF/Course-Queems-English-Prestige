<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateSetting extends Model
{
    protected $fillable = [
        'signer_name',
        'signer_title',
        'signature_image',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'signer_name' => 'Queens English Prestige',
            'signer_title' => 'Authorized Signature',
            'signature_image' => null,
        ]);
    }

    public function signerName(): string
    {
        return $this->signer_name ?: 'Queens English Prestige';
    }

    public function signerTitle(): string
    {
        return $this->signer_title ?: 'Authorized Signature';
    }
}
