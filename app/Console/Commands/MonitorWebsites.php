<?php

namespace App\Console\Commands;

use App\Models\Website;
use App\Jobs\CheckWebsiteStatus;
use Illuminate\Console\Command;

class MonitorWebsites extends Command
{
    protected $signature = 'monitor:websites {--force : Force check all websites}';
    protected $description = 'Check website statuses that need monitoring';

    public function handle()
    {
        $force = $this->option('force');

        $query = Website::where('is_active', true);

        if (!$force) {
            $query->where(function($q) {
                $q->whereNull('last_checked_at')
                  ->orWhere('last_checked_at', '<=', now()->subMinutes(1));
            });
        }

        $websites = $query->get();

        $this->info("Found {$websites->count()} websites to check");

        $dispatched = 0;
        foreach ($websites as $website) {
            if ($force || $website->shouldBeChecked()) {
                CheckWebsiteStatus::dispatch($website);
                $dispatched++;
            }
        }

        $this->info("Dispatched {$dispatched} monitoring jobs");

        return 0;
    }
}
