<?php

namespace App\Console\Commands;

use App\Models\Website;
use App\Jobs\CheckSSLCertificate;
use Illuminate\Console\Command;

class MonitorSSL extends Command
{
    protected $signature = 'monitor:ssl {--force : Check all SSL certificates}';
    protected $description = 'Monitor SSL certificates for expiry';

    public function handle()
    {
        $force = $this->option('force');

        $query = Website::where('is_active', true)
                       ->where('verify_ssl', true);

        if (!$force) {
            $query->where(function($q) {
                $q->whereDoesntHave('sslCertificates')
                  ->orWhereHas('sslCertificates', function($subQ) {
                      $subQ->where('last_checked_at', '<=', now()->subHours(24));
                  });
            });
        }

        $websites = $query->get();

        $this->info("Checking SSL certificates for {$websites->count()} websites");

        foreach ($websites as $website) {
            CheckSSLCertificate::dispatch($website);
        }

        $this->info("SSL monitoring jobs dispatched");

        return 0;
    }
}
