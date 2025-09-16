<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Website;
use App\Models\MonitoringResult;
use App\Models\Payment;
use App\Models\ContactMessage;
use App\Models\Blog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Remove the __construct method with middleware
    // Middleware will be handled in routes

    public function dashboard()
    {
        // Key metrics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('last_activity_at', '>=', now()->subDays(30))->count(),
            'paid_users' => User::whereIn('subscription_status', ['active', 'trialing'])->count(),
            'total_websites' => Website::count(),
            'active_websites' => Website::where('is_active', true)->count(),
            'websites_up' => Website::where('current_status', 'up')->count(),
            'websites_down' => Website::where('current_status', 'down')->count(),
            'total_checks_today' => MonitoringResult::whereDate('checked_at', today())->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'revenue_this_month' => Payment::where('status', 'completed')
                                          ->whereMonth('created_at', now()->month)
                                          ->sum('amount'),
            'pending_contacts' => ContactMessage::where('status', 'new')->count(),
        ];

        // Recent activity
        $recentUsers = User::latest()->limit(5)->get();
        $recentWebsites = Website::with('user')->latest()->limit(10)->get();
        $recentContacts = ContactMessage::latest()->limit(5)->get();

        // Charts data
        $userGrowthData = $this->getUserGrowthData();
        $revenueData = $this->getRevenueData();
        $uptimeData = $this->getSystemUptimeData();

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentWebsites',
            'recentContacts',
            'userGrowthData',
            'revenueData',
            'uptimeData'
        ));
    }

    private function getUserGrowthData()
    {
        $days = collect();
        for ($i = 29; $i >= 0; $i--) {
            $days->push(now()->subDays($i));
        }

        $labels = $days->map(fn($day) => $day->format('M j'))->toArray();

        $data = $days->map(function($day) {
            return User::whereDate('created_at', $day->toDateString())->count();
        })->toArray();

        return ['labels' => $labels, 'data' => $data];
    }

    private function getRevenueData()
    {
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push(now()->subMonths($i));
        }

        $labels = $months->map(fn($month) => $month->format('M Y'))->toArray();

        $data = $months->map(function($month) {
            return Payment::where('status', 'completed')
                         ->whereYear('created_at', $month->year)
                         ->whereMonth('created_at', $month->month)
                         ->sum('amount');
        })->toArray();

        return ['labels' => $labels, 'data' => $data];
    }

    private function getSystemUptimeData()
    {
        $hours = collect();
        for ($i = 23; $i >= 0; $i--) {
            $hours->push(now()->subHours($i));
        }

        $labels = $hours->map(fn($hour) => $hour->format('H:i'))->toArray();

        $data = $hours->map(function($hour) {
            $total = MonitoringResult::where('checked_at', '>=', $hour)
                                   ->where('checked_at', '<', $hour->copy()->addHour())
                                   ->count();

            $up = MonitoringResult::where('checked_at', '>=', $hour)
                                ->where('checked_at', '<', $hour->copy()->addHour())
                                ->where('status', 'up')
                                ->count();

            return $total > 0 ? ($up / $total) * 100 : 100;
        })->toArray();

        return ['labels' => $labels, 'data' => $data];
    }

    public function systemInfo()
    {
        $systemInfo = [
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_version' => $this->getDatabaseVersion(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'disk_space' => $this->getDiskSpace(),
        ];

        return view('admin.system-info', compact('systemInfo'));
    }

    private function getDatabaseVersion()
    {
        try {
            return DB::select('SELECT VERSION() as version')[0]->version;
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    private function getDiskSpace()
    {
        $bytes = disk_free_space(storage_path());
        $total = disk_total_space(storage_path());

        return [
            'free' => $this->formatBytes($bytes),
            'total' => $this->formatBytes($total),
            'used_percent' => round((($total - $bytes) / $total) * 100, 1),
        ];
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
