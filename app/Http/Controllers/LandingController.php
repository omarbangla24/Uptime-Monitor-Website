<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\MonitoringResult;
use App\Models\Blog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class LandingController extends Controller
{
    public function welcome()
    {
        // Get live website status updates (cached for performance)
        $liveUpdates = Cache::remember('landing.live_updates', 300, function () {
            return Website::where('is_public', true)
                ->with(['monitoringResults' => function($query) {
                    $query->latest()->limit(1);
                }])
                ->limit(20)
                ->get()
                ->map(function($website) {
                    $latestResult = $website->monitoringResults->first();
                    return [
                        'name' => $website->name,
                        'url' => $website->url,
                        'domain' => $website->domain,
                        'status' => $website->current_status,
                        'response_time' => $website->response_time,
                        'uptime_percentage' => $website->uptime_percentage,
                        'last_checked' => $website->last_checked_at,
                        'location' => $latestResult->location ?? 'Server'
                    ];
                });
        });

        // Get recently checked websites
        $recentlyChecked = Cache::remember('landing.recently_checked', 60, function () {
            return Website::where('is_public', true)
                ->whereNotNull('last_checked_at')
                ->orderBy('last_checked_at', 'desc')
                ->limit(10)
                ->get(['name', 'domain', 'current_status', 'response_time', 'last_checked_at']);
        });

        // Get global statistics
        $stats = Cache::remember('landing.stats', 3600, function () {
            $totalChecks = MonitoringResult::count();
            $uptimeChecks = MonitoringResult::where('status', 'up')->count();
            $avgResponseTime = MonitoringResult::where('status', 'up')
                ->where('checked_at', '>=', now()->subDays(7))
                ->avg('response_time');

            return [
                'total_websites' => Website::where('is_public', true)->count(),
                'total_checks' => $totalChecks,
                'uptime_percentage' => $totalChecks > 0 ? round(($uptimeChecks / $totalChecks) * 100, 2) : 100,
                'avg_response_time' => round($avgResponseTime ?? 0),
                'total_users' => \App\Models\User::count()
            ];
        });

        // Get featured blog posts
        $featuredBlogs = Cache::remember('landing.featured_blogs', 1800, function () {
            return Blog::published()
                ->featured()
                ->with(['category', 'author'])
                ->limit(3)
                ->get();
        });

        // Get recent blog posts
        $recentBlogs = Cache::remember('landing.recent_blogs', 1800, function () {
            return Blog::published()
                ->with(['category', 'author'])
                ->latest('published_at')
                ->limit(6)
                ->get();
        });

        return view('welcome', compact(
            'liveUpdates',
            'recentlyChecked',
            'stats',
            'featuredBlogs',
            'recentBlogs'
        ));
    }

    public function liveUpdates()
    {
        $updates = Website::where('is_public', true)
            ->with(['monitoringResults' => function($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->map(function($website) {
                return [
                    'id' => $website->id,
                    'name' => $website->name,
                    'domain' => $website->domain,
                    'status' => $website->current_status,
                    'response_time' => $website->response_time,
                    'uptime_percentage' => $website->uptime_percentage,
                    'last_checked' => $website->last_checked_at?->toISOString()
                ];
            });

        return response()->json($updates);
    }
}
