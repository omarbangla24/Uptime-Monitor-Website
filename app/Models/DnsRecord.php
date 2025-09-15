<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DnsRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id', 'domain', 'record_type', 'name', 'value', 'ttl', 'priority', 'last_checked_at'
    ];

    protected function casts(): array
    {
        return [
            'last_checked_at' => 'datetime',
        ];
    }

    public function website()
    {
        return $this->belongsTo(Website::class);
    }
}
