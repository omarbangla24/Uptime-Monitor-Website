<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'subject', 'message', 'phone', 'company', 'type',
        'status', 'ip_address', 'user_agent', 'metadata', 'replied_at', 'admin_notes'
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'replied_at' => 'datetime',
        ];
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeUnreplied($query)
    {
        return $query->whereNull('replied_at');
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'new' => 'text-blue-600',
            'in_progress' => 'text-yellow-600',
            'resolved' => 'text-green-600',
            'closed' => 'text-gray-600',
        };
    }
}
