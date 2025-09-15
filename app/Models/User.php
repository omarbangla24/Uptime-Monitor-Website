<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Billable;

    protected $fillable = [
        'name', 'email', 'password', 'subscription_plan_id', 'subscription_starts_at',
        'subscription_ends_at', 'subscription_status', 'trial_ends_at', 'timezone',
        'notification_settings', 'is_admin', 'avatar', 'bio', 'company', 'website', 'phone'
    ];

    protected $hidden = [
        'password', 'remember_token', 'stripe_customer_id', 'paypal_customer_id'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'subscription_starts_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'notification_settings' => 'array',
            'is_admin' => 'boolean',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function websites()
    {
        return $this->hasMany(Website::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    public function websiteAlerts()
    {
        return $this->hasMany(WebsiteAlert::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('subscription_status', 'active');
    }

    public function scopeSubscribed($query)
    {
        return $query->whereIn('subscription_status', ['active', 'trialing']);
    }

    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    // Accessors & Mutators
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function getIsSubscribedAttribute()
    {
        return in_array($this->subscription_status, ['active', 'trialing']);
    }

    public function getSubscriptionExpiredAttribute()
    {
        return $this->subscription_ends_at && $this->subscription_ends_at->isPast();
    }

    public function getDaysUntilSubscriptionExpiryAttribute()
    {
        if (!$this->subscription_ends_at) return null;
        return max(0, Carbon::now()->diffInDays($this->subscription_ends_at, false));
    }

    // Methods
    public function canAddWebsites()
    {
        if (!$this->subscriptionPlan) return false;

        $limit = $this->subscriptionPlan->websites_limit;
        if ($limit === 0) return true; // Unlimited

        return $this->websites()->count() < $limit;
    }

    public function getWebsitesLimitRemaining()
    {
        if (!$this->subscriptionPlan) return 0;

        $limit = $this->subscriptionPlan->websites_limit;
        if ($limit === 0) return 999999; // Unlimited

        return max(0, $limit - $this->websites()->count());
    }

    public function hasFeature($feature)
    {
        if (!$this->subscriptionPlan) return false;

        return $this->subscriptionPlan->{$feature} ?? false;
    }

    public function updateLastActivity()
    {
        $this->update(['last_activity_at' => now()]);
    }
}
