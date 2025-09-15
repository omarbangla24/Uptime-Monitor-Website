<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id', 'status', 'response_time', 'response_code', 'response_headers',
        'response_body', 'ip_address', 'error_message', 'ssl_info', 'dns_info',
        'redirect_chain', 'total_time', 'namelookup_time', 'connect_time',
        'pretransfer_time', 'starttransfer_time', 'location', 'checked_at'
    ];

    protected function casts(): array
    {
        return [
            'ssl_info' => 'array',
            'dns_info' => 'array',
            'redirect_chain' => 'array',
            'checked_at' => 'datetime',
        ];
    }

    // Relationships
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    // Scopes
    public function scopeUp($query)
    {
        return $query->where('status', 'up');
    }

    public function scopeDown($query)
    {
        return $query->whereIn('status', ['down', 'timeout', 'ssl_error', 'dns_error']);
    }

    public function scopeRecent($query, $minutes = 60)
    {
        return $query->where('checked_at', '>=', now()->subMinutes($minutes));
    }

    public function scopeToday($query)
    {
        return $query->whereDate('checked_at', today());
    }

    // Accessors
    public function getIsUpAttribute()
    {
        return $this->status === 'up';
    }

    public function getFormattedResponseTimeAttribute()
    {
        return $this->response_time ? $this->response_time . 'ms' : 'N/A';
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'up' => 'text-success-600',
            'down', 'timeout', 'ssl_error', 'dns_error' => 'text-danger-600',
            default => 'text-warning-600',
        };
    }
}
