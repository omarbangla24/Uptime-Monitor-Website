<?php

namespace App\Jobs;

use App\Models\Website;
use App\Notifications\DomainExpiringNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class CheckDomainExpiry implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $website;

    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    public function handle()
    {
        if (!$this->website->monitor_domain_expiry) {
            return;
        }

        try {
            $expiryDate = $this->getDomainExpiryDate($this->website->domain);

            if ($expiryDate) {
                $this->checkExpiryNotification($expiryDate);

                // Update website with expiry info
                $this->website->update([
                    'domain_expiry_date' => $expiryDate,
                    'domain_expiry_checked_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("Domain expiry check failed for {$this->website->domain}: " . $e->getMessage());
        }
    }

    private function getDomainExpiryDate($domain)
    {
        // This is a simplified implementation
        // In production, you might want to use a WHOIS API service
        $whoisData = $this->getWhoisData($domain);

        if ($whoisData) {
            return $this->parseExpiryDate($whoisData);
        }

        return null;
    }

    private function getWhoisData($domain)
    {
        // Using a simple whois command (requires whois to be installed)
        $output = [];
        $returnVar = 0;

        exec("whois " . escapeshellarg($domain), $output, $returnVar);

        if ($returnVar === 0) {
            return implode("\n", $output);
        }

        return null;
    }

    private function parseExpiryDate($whoisData)
    {
        $patterns = [
            '/Registry Expiry Date:\s*(.+)/i',
            '/Expiration Date:\s*(.+)/i',
            '/Expires:\s*(.+)/i',
            '/Expiry Date:\s*(.+)/i',
            '/expire:\s*(.+)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $whoisData, $matches)) {
                $dateString = trim($matches[1]);

                try {
                    return Carbon::parse($dateString);
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return null;
    }

    private function checkExpiryNotification($expiryDate)
    {
        $daysUntilExpiry = now()->diffInDays($expiryDate, false);
        $reminderDays = $this->website->domain_expiry_reminder_days;

        if ($daysUntilExpiry <= $reminderDays && $daysUntilExpiry > 0) {
            $this->website->user->notify(
                new DomainExpiringNotification($this->website, $expiryDate)
            );
        }
    }
}
