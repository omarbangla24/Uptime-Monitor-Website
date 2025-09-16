<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\MonitorWebsites::class,
        Commands\MonitorSSL::class,
        Commands\MonitorDNS::class,
        Commands\MonitorDomains::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Monitor websites every minute
        $schedule->command('monitor:websites')
                 ->everyMinute()
                 ->withoutOverlapping()
                 ->runInBackground();

        // Check SSL certificates daily
        $schedule->command('monitor:ssl')
                 ->daily()
                 ->at('03:00')
                 ->withoutOverlapping();

        // Check DNS records every 4 hours
        $schedule->command('monitor:dns')
                 ->everyFourHours()
                 ->withoutOverlapping();

        // Check domain expiry weekly
        $schedule->command('monitor:domains')
                 ->weekly()
                 ->sundays()
                 ->at('04:00')
                 ->withoutOverlapping();

        // Cleanup old monitoring results
        $schedule->call(function () {
            // Keep monitoring results based on user's plan
            \DB::table('monitoring_results')
               ->where('created_at', '<', now()->subDays(90))
               ->delete();
        })->daily()->at('02:00');

        // Process queued jobs
        $schedule->command('queue:work --stop-when-empty')
                 ->everyMinute()
                 ->withoutOverlapping()
                 ->runInBackground();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
