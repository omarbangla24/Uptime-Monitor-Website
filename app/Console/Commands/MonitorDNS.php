<?php

namespace App\Console\Commands;

use App\Models\Website;
use App\Jobs\CheckDNSRecords;
use Illuminate\Console\Command;

class MonitorDNS extends Command
{
    protected $signature = 'monitor:dns';
    protected $description = 'Monitor DNS records for changes';

    public function handle()
    {
        $websites = Website::where('is_active', true)
                          ->where('monitor_dns', true)
                          ->get();

        $this->info("Checking DNS records for {$websites->count()} websites");

        foreach ($websites as $website) {
            CheckDNSRecords::dispatch($website);
        }

        $this->info("DNS monitoring jobs dispatched");

        return 0;
    }
}
