<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Website extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'url', 'domain', 'protocol', 'port', 'check_interval',
        'timeout', 'follow_redirects', 'expected_status_codes', 'expected_content',
        'request_headers', 'method', 'post_data', 'verify_ssl', 'check_ssl_expiry',
        'ssl_expiry_reminder_days', 'monitor_dns', 'monitor_domain_expiry',
        'domain_expiry_reminder_days', 'is_active', 'is_public', 'current_status',
        'last_checked_at', 'last_uptime_at', 'last_downtime_at', 'response_time',
        'uptime_percentage', 'consecutive_failures', 'failure_reason',
        'contact_groups', 'tags', 'notes'
    ];

    protected function casts(): array
    {
        return [
            'follow_redirects' => 'boolean',
            'verify_ssl' => 'boolean',
            'check_ssl_expiry' => 'boolean',
            'monitor_dns' => 'boolean',
            'monitor_domain_expiry' => 'boolean',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
            'last_checked_at' => 'datetime',
            'last_uptime_at' => 'datetime',
            'last_downtime_at' => 'datetime',
            'contact_groups' => 'array',
            'tags' => 'array',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function monitoringResults()
    {
        return $this->hasMany(MonitoringResult::class);
    }

    public function sslCertificates()
    {
        return $this->hasMany(SslCertificate::class);
    }

    public function dnsRecords()
    {
        return $this->hasMany(DnsRecord::class);
    }

    public function alerts()
    {
        return $this->hasMany(WebsiteAlert::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeUp($query)
    {
        return $query->where('current_status', 'up');
    }

    public function scopeDown($query)
    {
        return $query->where('current_status', 'down');
    }

    public function scopeNeedsCheck($query)
    {
        return $query->where(function (Builder $q) {
            $q->whereNull('last_checked_at')
              ->orWhere('last_checked_at', '<=', now()->subMinutes(5)); // Default check interval
        });
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return match ($this->current_status) {
            'up' => '<span class="status-online">Online</span>',
            'down' => '<span class="status-offline">Offline</span>',
            default => '<span class="status-warning">Unknown</span>',
        };
    }

    public function getUptimePercentageColorAttribute()
    {
        return match (true) {
            $this->uptime_percentage >= 99 => 'text-success-600',
            $this->uptime_percentage >= 95 => 'text-warning-600',
            default => 'text-danger-600',
        };
    }

    public function getLastCheckedHumanAttribute()
    {
        if (!$this->last_checked_at) return 'Never';
        return $this->last_checked_at->diffForHumans();
    }

    public function getFormattedResponseTimeAttribute()
    {
        if (!$this->response_time) return 'N/A';
        return $this->response_time . 'ms';
    }

    public function getExpectedStatusCodesArrayAttribute()
    {
        return explode(',', $this->expected_status_codes);
    }

    // Methods
    public function shouldBeChecked()
    {
        if (!$this->is_active) return false;

        if (!$this->last_checked_at) return true;

        return $this->last_checked_at->addMinutes($this->check_interval)->isPast();
    }

    public function updateStatus($status, $responseTime = null, $errorMessage = null)
    {
        $this->update([
            'current_status' => $status,
            'last_checked_at' => now(),
            'response_time' => $responseTime,
            'failure_reason' => $errorMessage,
            'consecutive_failures' => $status === 'down' ? $this->consecutive_failures + 1 : 0,
            $status === 'up' ? 'last_uptime_at' : 'last_downtime_at' => now(),
        ]);

        // Update uptime percentage (last 24h)
        $this->calculateUptimePercentage();
    }

    public function calculateUptimePercentage($hours = 24)
    {
        $totalChecks = $this->monitoringResults()
            ->where('checked_at', '>=', now()->subHours($hours))
            ->count();

        if ($totalChecks === 0) {
            $this->update(['uptime_percentage' => 100]);
            return;
        }

        $uptimeChecks = $this->monitoringResults()
            ->where('checked_at', '>=', now()->subHours($hours))
            ->where('status', 'up')
            ->count();

        $percentage = ($uptimeChecks / $totalChecks) * 100;
        $this->update(['uptime_percentage' => round($percentage, 2)]);
    }

    public function getUptimeStats($days = 30)
    {
        $startDate = now()->subDays($days);

        $totalChecks = $this->monitoringResults()
            ->where('checked_at', '>=', $startDate)
            ->count();

        $uptimeChecks = $this->monitoringResults()
            ->where('checked_at', '>=', $startDate)
            ->where('status', 'up')
            ->count();

        $downtimeChecks = $totalChecks - $uptimeChecks;

        return [
            'total_checks' => $totalChecks,
            'uptime_checks' => $uptimeChecks,
            'downtime_checks' => $downtimeChecks,
            'uptime_percentage' => $totalChecks > 0 ? ($uptimeChecks / $totalChecks) * 100 : 100,
            'average_response_time' => $this->monitoringResults()
                ->where('checked_at', '>=', $startDate)
                ->where('status', 'up')
                ->avg('response_time') ?? 0,
        ];
    }
}
