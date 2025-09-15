<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id', 'user_id', 'type', 'severity', 'title', 'message', 'details',
        'status', 'channels', 'triggered_at', 'sent_at', 'resolved_at', 'failure_reason'
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
            'channels' => 'array',
            'triggered_at' => 'datetime',
            'sent_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function getSeverityColorAttribute()
    {
        return match ($this->severity) {
            'low' => 'text-blue-600',
            'medium' => 'text-yellow-600',
            'high' => 'text-orange-600',
            'critical' => 'text-red-600',
        };
    }
}
