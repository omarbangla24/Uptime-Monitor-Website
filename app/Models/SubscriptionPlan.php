<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'interval', 'websites_limit',
        'checks_per_minute', 'ssl_monitoring', 'dns_monitoring', 'domain_expiry_monitoring',
        'email_alerts', 'sms_alerts', 'webhook_alerts', 'data_retention_days',
        'api_access', 'white_label', 'team_members', 'features', 'stripe_plan_id',
        'paypal_plan_id', 'is_active', 'sort_order'
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'ssl_monitoring' => 'boolean',
            'dns_monitoring' => 'boolean',
            'domain_expiry_monitoring' => 'boolean',
            'email_alerts' => 'boolean',
            'sms_alerts' => 'boolean',
            'webhook_alerts' => 'boolean',
            'api_access' => 'boolean',
            'white_label' => 'boolean',
            'is_active' => 'boolean',
            'features' => 'array',
        ];
    }

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    public function getIsPopularAttribute()
    {
        return $this->slug === 'pro'; // Or any logic to determine popular plan
    }

    public function getMonthlyPriceAttribute()
    {
        return $this->interval === 'yearly' ? $this->price / 12 : $this->price;
    }
}
