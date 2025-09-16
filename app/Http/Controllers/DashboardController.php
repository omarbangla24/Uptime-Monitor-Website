<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\MonitoringResult;
use App\Models\WebsiteAlert;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get user's websites with latest monitoring results
        $websites = $user->websites()
                         ->with(['monitoringResults' => function($query) {
                             $query->latest()->limit(1);
                         }])
                         ->orderBy('created_at', 'desc')
                         ->get();

        // Calculate comprehensive statistics
        $stats = $this->calculateUserStats($user, $websites);

        // Get recent alerts
        $recentAlerts = $user->websiteAlerts()
                            ->with('website')
                            ->latest()
                            ->limit(5)
                            ->get();

        // Get uptime data for charts (last 24 hours)
        $uptimeData = $this->getUptimeChartData($websites);

        // Get recent monitoring activity
        $recentActivity = $this->getRecentActivity($user);

        return view('dashboard.index', compact(
            'websites',
            'stats',
            'recentAlerts',
            'uptimeData',
            'recentActivity',
            'user'
        ));
    }

    private function calculateUserStats($user, $websites)
    {
        $totalWebsites = $websites->count();
        $websitesUp = $websites->where('current_status', 'up')->count();
        $websitesDown = $websites->where('current_status', 'down')->count();
        $websitesUnknown = $websites->where('current_status', 'unknown')->count();

        // Calculate average response time for up websites
        $avgResponseTime = $websites->where('current_status', 'up')
                                  ->where('response_time', '>', 0)
                                  ->avg('response_time') ?? 0;

        // Calculate overall uptime percentage
        $overallUptime = $totalWebsites > 0 ?
            $websites->avg('uptime_percentage') : 100;

        // Get total checks in last 24 hours
        $totalChecks = MonitoringResult::whereIn('website_id', $websites->pluck('id'))
                                     ->where('checked_at', '>=', now()->subDay())
                                     ->count();

        // Calculate incidents (downtime events) in last 7 days
        $incidents = MonitoringResult::whereIn('website_id', $websites->pluck('id'))
                                   ->where('status', '!=', 'up')
                                   ->where('checked_at', '>=', now()->subWeek())
                                   ->count();

        return [
            'total_websites' => $totalWebsites,
            'websites_up' => $websitesUp,
            'websites_down' => $websitesDown,
            'websites_unknown' => $websitesUnknown,
            'avg_response_time' => round($avgResponseTime),
            'overall_uptime' => round($overallUptime, 2),
            'total_checks_24h' => $totalChecks,
            'incidents_7d' => $incidents,
            'websites_limit' => $user->subscriptionPlan->websites_limit ?? 3,
            'websites_remaining' => $user->getWebsitesLimitRemaining(),
        ];
    }

    private function getUptimeChartData($websites)
    {
        if ($websites->isEmpty()) {
            return ['labels' => [], 'datasets' => []];
        }

        $hours = collect();
        for ($i = 23; $i >= 0; $i--) {
            $hours->push(now()->subHours($i));
        }

        $labels = $hours->map(function($hour) {
            return $hour->format('H:i');
        })->toArray();

        $datasets = [];

        foreach ($websites->take(5) as $website) {
            $data = [];

            foreach ($hours as $hour) {
                $result = $website->monitoringResults()
                                 ->where('checked_at', '>=', $hour)
                                 ->where('checked_at', '<', $hour->copy()->addHour())
                                 ->latest()
                                 ->first();

                $data[] = $result && $result->status === 'up' ? $result->response_time ?? 0 : null;
            }

            $datasets[] = [
                'label' => $website->name,
                'data' => $data,
                'borderColor' => $this->getWebsiteColor($website->id),
                'backgroundColor' => $this->getWebsiteColor($website->id) . '20',
                'tension' => 0.4,
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets
        ];
    }

    private function getRecentActivity($user)
    {
        return MonitoringResult::whereIn('website_id', $user->websites->pluck('id'))
                              ->with('website')
                              ->latest()
                              ->limit(10)
                              ->get()
                              ->map(function($result) {
                                  return [
                                      'website' => $result->website->name,
                                      'status' => $result->status,
                                      'response_time' => $result->response_time,
                                      'checked_at' => $result->checked_at,
                                      'message' => $this->getActivityMessage($result)
                                  ];
                              });
    }

    private function getActivityMessage($result)
    {
        switch ($result->status) {
            case 'up':
                return "Website is responding normally ({$result->response_time}ms)";
            case 'down':
                return "Website is not responding" . ($result->error_message ? ": {$result->error_message}" : '');
            case 'timeout':
                return "Website response timed out";
            case 'ssl_error':
                return "SSL certificate error detected";
            case 'dns_error':
                return "DNS resolution failed";
            default:
                return "Status check completed";
        }
    }

    private function getWebsiteColor($id)
    {
        $colors = [
            '#3B82F6', '#10B981', '#F59E0B', '#EF4444',
            '#8B5CF6', '#06B6D4', '#84CC16', '#F97316'
        ];

        return $colors[$id % count($colors)];
    }

    public function reports()
    {
        $user = auth()->user();
        $websites = $user->websites()->get();

        // Get date range from request or default to last 30 days
        $startDate = request('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = request('end_date', now()->format('Y-m-d'));

        $reportData = $this->generateReportData($websites, $startDate, $endDate);

        return view('dashboard.reports', compact(
            'websites',
            'reportData',
            'startDate',
            'endDate'
        ));
    }

    private function generateReportData($websites, $startDate, $endDate)
    {
        $data = [];

        foreach ($websites as $website) {
            $results = $website->monitoringResults()
                              ->whereBetween('checked_at', [$startDate, $endDate])
                              ->get();

            $totalChecks = $results->count();
            $upChecks = $results->where('status', 'up')->count();
            $avgResponseTime = $results->where('status', 'up')->avg('response_time') ?? 0;

            $data[] = [
                'website' => $website,
                'total_checks' => $totalChecks,
                'uptime_percentage' => $totalChecks > 0 ? ($upChecks / $totalChecks) * 100 : 0,
                'avg_response_time' => round($avgResponseTime),
                'downtime_incidents' => $results->where('status', '!=', 'up')->count(),
                'fastest_response' => $results->where('status', 'up')->min('response_time') ?? 0,
                'slowest_response' => $results->where('status', 'up')->max('response_time') ?? 0,
            ];
        }

        return collect($data);
    }

    public function billing()
    {
        $user = auth()->user();
        $plans = SubscriptionPlan::active()->ordered()->get();

        return view('dashboard.billing', [
            'user' => $user,
            'currentPlan' => $user->subscriptionPlan,
            'payments' => $user->payments()->latest()->limit(10)->get(),
            'plans' => $plans
        ]);
    }
}
