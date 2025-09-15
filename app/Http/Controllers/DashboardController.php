<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\MonitoringResult;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get user's websites with latest status
        $websites = $user->websites()
                         ->with(['monitoringResults' => function($query) {
                             $query->latest()->limit(1);
                         }])
                         ->get();

        // Calculate statistics
        $stats = [
            'total_websites' => $websites->count(),
            'websites_up' => $websites->where('current_status', 'up')->count(),
            'websites_down' => $websites->where('current_status', 'down')->count(),
            'avg_response_time' => $websites->where('current_status', 'up')->avg('response_time') ?? 0,
        ];

        // Get recent alerts
        $recentAlerts = $user->websiteAlerts()
                            ->with('website')
                            ->latest()
                            ->limit(5)
                            ->get();

        return view('dashboard', [
            'websites' => $websites,
            'stats' => $stats,
            'recentAlerts' => $recentAlerts,
            'user' => $user
        ]);
    }

    public function reports()
    {
        return view('reports.index', [
            'title' => 'Monitoring Reports'
        ]);
    }

    public function billing()
    {
        $user = auth()->user();

        return view('billing.index', [
            'title' => 'Billing & Subscription',
            'user' => $user,
            'payments' => $user->payments()->latest()->limit(10)->get()
        ]);
    }
}
