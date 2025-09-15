<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SslCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id', 'domain', 'issuer', 'subject', 'fingerprint', 'serial_number',
        'signature_algorithm', 'san_domains', 'valid_from', 'valid_to',
        'days_until_expiry', 'is_valid', 'is_self_signed', 'is_expired',
        'validation_errors', 'last_checked_at'
    ];

    protected function casts(): array
    {
        return [
            'san_domains' => 'array',
            'valid_from' => 'datetime',
            'valid_to' => 'datetime',
            'is_valid' => 'boolean',
            'is_self_signed' => 'boolean',
            'is_expired' => 'boolean',
            'last_checked_at' => 'datetime',
        ];
    }

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('days_until_expiry', '<=', $days)
                    ->where('is_expired', false);
    }

    public function getExpiryStatusAttribute()
    {
        if ($this->is_expired) return 'expired';
        if ($this->days_until_expiry <= 7) return 'critical';
        if ($this->days_until_expiry <= 30) return 'warning';
        return 'valid';
    }
}
