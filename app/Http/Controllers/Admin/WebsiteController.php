<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\User;
use App\Jobs\CheckWebsiteStatus;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    

    public function index(Request $request)
    {
        $query = Website::with(['user', 'monitoringResults' => function($q) {
            $q->latest()->limit(1);
        }]);

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('current_status', $request->status);
        }

        // Filter by active status
        if ($request->has('active') && $request->active !== '') {
            $query->where('is_active', $request->active === '1');
        }

        $websites = $query->latest()->paginate(20)->withQueryString();

        return view('admin.websites.index', compact('websites'));
    }

    public function show(Website $website)
    {
        $website->load([
            'user',
            'monitoringResults' => function($q) {
                $q->latest()->limit(50);
            },
            'sslCertificates' => function($q) {
                $q->latest()->limit(1);
            },
            'dnsRecords'
        ]);

        $stats = $website->getUptimeStats(30);

        return view('admin.websites.show', compact('website', 'stats'));
    }

    public function edit(Website $website)
    {
        $users = User::orderBy('name')->get();
        return view('admin.websites.edit', compact('website', 'users'));
    }

    public function update(Request $request, Website $website)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'check_interval' => 'required|integer|min:1|max:60',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
        ]);

        $parsedUrl = parse_url($validated['url']);

        $website->update([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'url' => $validated['url'],
            'domain' => $parsedUrl['host'],
            'protocol' => $parsedUrl['scheme'] ?? 'https',
            'port' => $parsedUrl['port'] ?? ($parsedUrl['scheme'] === 'https' ? 443 : 80),
            'check_interval' => $validated['check_interval'],
            'is_active' => $validated['is_active'] ?? true,
            'is_public' => $validated['is_public'] ?? false,
        ]);

        return back()->with('success', 'Website updated successfully.');
    }

    public function destroy(Website $website)
    {
        $website->delete();

        return redirect()->route('admin.websites.index')
                        ->with('success', 'Website deleted successfully.');
    }

    public function checkNow(Website $website)
    {
        CheckWebsiteStatus::dispatch($website);

        return back()->with('success', 'Website check initiated.');
    }

    public function bulkCheck(Request $request)
    {
        $websiteIds = $request->input('websites', []);
        $websites = Website::whereIn('id', $websiteIds)->get();

        foreach ($websites as $website) {
            CheckWebsiteStatus::dispatch($website);
        }

        return back()->with('success', "Initiated checks for {$websites->count()} websites.");
    }
}
