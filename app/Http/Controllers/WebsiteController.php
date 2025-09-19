<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Jobs\CheckWebsiteStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WebsiteController extends Controller
{
     use AuthorizesRequests; 
    public function index(Request $request)
    {
        $query = auth()->user()->websites()->with(['monitoringResults' => function($q) {
            $q->latest()->limit(1);
        }]);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('current_status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%");
            });
        }

        $websites = $query->latest()->paginate(12)->withQueryString();

        return view('dashboard.websites.index', compact('websites'));
    }

    public function create()
    {
        $this->authorize('create', Website::class);

        return view('dashboard.websites.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Website::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => ['required', 'url', 'max:500',
                Rule::unique('websites')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })
            ],
            'check_interval' => 'required|integer|min:1|max:60',
            'expected_status_codes' => 'nullable|string|max:100',
            'expected_content' => 'nullable|string|max:500',
            'verify_ssl' => 'boolean',
            'check_ssl_expiry' => 'boolean',
            'monitor_dns' => 'boolean',
            'contact_groups' => 'nullable|array',
            'contact_groups.*' => 'email',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ]);

        $parsedUrl = parse_url($validated['url']);

        $website = auth()->user()->websites()->create([
            'name' => $validated['name'],
            'url' => $validated['url'],
            'domain' => $parsedUrl['host'],
            'protocol' => $parsedUrl['scheme'] ?? 'https',
            'port' => $parsedUrl['port'] ?? ($parsedUrl['scheme'] === 'https' ? 443 : 80),
            'check_interval' => $validated['check_interval'],
            'expected_status_codes' => $validated['expected_status_codes'] ?? '200,301,302',
            'expected_content' => $validated['expected_content'],
            'verify_ssl' => $validated['verify_ssl'] ?? true,
            'check_ssl_expiry' => $validated['check_ssl_expiry'] ?? true,
            'monitor_dns' => $validated['monitor_dns'] ?? false,
            'contact_groups' => $validated['contact_groups'] ?? [],
            'tags' => $validated['tags'] ?? [],
            'is_active' => true,
        ]);

        // Queue immediate check
        CheckWebsiteStatus::dispatch($website);

        return redirect()->route('websites.show', $website)
                        ->with('success', 'Website added successfully! Initial check is in progress.');
    }

    public function show(Website $website)
    {
        $this->authorize('view', $website);

        $website->load(['monitoringResults' => function($query) {
            $query->latest()->limit(50);
        }, 'sslCertificates' => function($query) {
            $query->latest()->limit(1);
        }]);

        // Get uptime stats for different periods
        $stats = [
            '24h' => $website->getUptimeStats(1),
            '7d' => $website->getUptimeStats(7),
            '30d' => $website->getUptimeStats(30),
            '90d' => $website->getUptimeStats(90),
        ];

        // Get recent incidents
        $incidents = $website->monitoringResults()
                            ->where('status', '!=', 'up')
                            ->latest()
                            ->limit(10)
                            ->get();

        // Chart data for response times
        $chartData = $this->getWebsiteChartData($website);

        return view('dashboard.websites.show', compact(
            'website',
            'stats',
            'incidents',
            'chartData'
        ));
    }

    public function edit(Website $website)
    {
        $this->authorize('update', $website);

        return view('dashboard.websites.edit', compact('website'));
    }

    public function update(Request $request, Website $website)
    {
        $this->authorize('update', $website);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => ['required', 'url', 'max:500',
                Rule::unique('websites')->where(function ($query) use ($website) {
                    return $query->where('user_id', auth()->id());
                })->ignore($website->id)
            ],
            'check_interval' => 'required|integer|min:1|max:60',
            'expected_status_codes' => 'nullable|string|max:100',
            'expected_content' => 'nullable|string|max:500',
            'verify_ssl' => 'boolean',
            'check_ssl_expiry' => 'boolean',
            'monitor_dns' => 'boolean',
            'contact_groups' => 'nullable|array',
            'contact_groups.*' => 'email',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'is_active' => 'boolean',
        ]);

        $parsedUrl = parse_url($validated['url']);

        $website->update([
            'name' => $validated['name'],
            'url' => $validated['url'],
            'domain' => $parsedUrl['host'],
            'protocol' => $parsedUrl['scheme'] ?? 'https',
            'port' => $parsedUrl['port'] ?? ($parsedUrl['scheme'] === 'https' ? 443 : 80),
            'check_interval' => $validated['check_interval'],
            'expected_status_codes' => $validated['expected_status_codes'] ?? '200,301,302',
            'expected_content' => $validated['expected_content'],
            'verify_ssl' => $validated['verify_ssl'] ?? true,
            'check_ssl_expiry' => $validated['check_ssl_expiry'] ?? true,
            'monitor_dns' => $validated['monitor_dns'] ?? false,
            'contact_groups' => $validated['contact_groups'] ?? [],
            'tags' => $validated['tags'] ?? [],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return back()->with('success', 'Website settings updated successfully!');
    }

    public function destroy(Website $website)
    {
        $this->authorize('delete', $website);

        $website->delete();

        return redirect()->route('websites.index')
                        ->with('success', 'Website removed successfully!');
    }

    public function checkNow(Website $website)
    {
        $this->authorize('update', $website);

        CheckWebsiteStatus::dispatch($website);

        return back()->with('success', 'Check initiated! Results will be available shortly.');
    }

    public function pause(Website $website)
    {
        $this->authorize('update', $website);

        $website->update(['is_active' => false]);

        return back()->with('success', 'Website monitoring paused.');
    }

    public function resume(Website $website)
    {
        $this->authorize('update', $website);

        $website->update(['is_active' => true]);

        return back()->with('success', 'Website monitoring resumed.');
    }

    private function getWebsiteChartData(Website $website, $hours = 24)
    {
        $timePoints = collect();
        for ($i = $hours - 1; $i >= 0; $i--) {
            $timePoints->push(now()->subHours($i));
        }

        $labels = $timePoints->map(function($time) {
            return $time->format('H:i');
        })->toArray();

        $responseData = [];
        $uptimeData = [];

        foreach ($timePoints as $time) {
            $result = $website->monitoringResults()
                             ->where('checked_at', '>=', $time)
                             ->where('checked_at', '<', $time->copy()->addHour())
                             ->latest()
                             ->first();

            if ($result) {
                $responseData[] = $result->status === 'up' ? $result->response_time : null;
                $uptimeData[] = $result->status === 'up' ? 100 : 0;
            } else {
                $responseData[] = null;
                $uptimeData[] = null;
            }
        }

        return [
            'labels' => $labels,
            'response_time' => $responseData,
            'uptime' => $uptimeData,
        ];
    }
}
