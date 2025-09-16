<?php

namespace App\Console\Commands;

use App\Models\Website;
use App\Jobs\CheckDomainExpiry;
use Illuminate\Console\Command;

class MonitorDomains extends Command
{
    protected $signature = 'monitor:domains';
    protected $description = 'Monitor domain expiry dates';

    public function handle()
    {
        $websites = Website::where('is_active', true)
                          ->where('monitor_domain_expiry', true)
                          ->where(function($q) {
                              $q->whereNull('domain_expiry_checked_at')
                                ->orWhere('domain_expiry_checked_at', '<=', now()->subDays(7));
                          })
                          ->get();

        $this->info("Checking domain expiry for {$websites->count()} websites");

        foreach ($websites as $website) {
            CheckDomainExpiry::dispatch($website);
        }

        $this->info("Domain monitoring jobs dispatched");

        return 0;
    }
}
