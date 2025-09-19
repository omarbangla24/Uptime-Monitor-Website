<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::withCount(['users', 'payments'])
            ->orderBy('sort_order')
            ->paginate(10);

        return view('admin.subscriptions.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.subscriptions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'interval' => 'required|in:monthly,yearly',
            'websites_limit' => 'required|integer|min:1',
            'checks_per_minute' => 'required|integer|min:1',
            'ssl_monitoring' => 'boolean',
            'dns_monitoring' => 'boolean',
            'domain_expiry_monitoring' => 'boolean',
            'email_alerts' => 'boolean',
            'sms_alerts' => 'boolean',
            'webhook_alerts' => 'boolean',
            'data_retention_days' => 'required|integer|min:1',
            'api_access' => 'boolean',
            'white_label' => 'boolean',
            'team_members' => 'required|integer|min:1',
            'features' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        SubscriptionPlan::create($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan created successfully.');
    }

    public function show(SubscriptionPlan $subscription)
    {
        $subscription->load(['users.payments', 'payments']);
        $stats = [
            'total_users' => $subscription->users->count(),
            'active_users' => $subscription->users->where('subscription_status', 'active')->count(),
            'total_revenue' => $subscription->payments->sum('amount'),
            'monthly_revenue' => $subscription->payments()
                ->where('created_at', '>=', now()->subMonth())
                ->sum('amount'),
        ];

        return view('admin.subscriptions.show', compact('subscription', 'stats'));
    }

    public function edit(SubscriptionPlan $subscription)
    {
        return view('admin.subscriptions.edit', compact('subscription'));
    }

    public function update(Request $request, SubscriptionPlan $subscription)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'interval' => 'required|in:monthly,yearly',
            'websites_limit' => 'required|integer|min:1',
            'checks_per_minute' => 'required|integer|min:1',
            'ssl_monitoring' => 'boolean',
            'dns_monitoring' => 'boolean',
            'domain_expiry_monitoring' => 'boolean',
            'email_alerts' => 'boolean',
            'sms_alerts' => 'boolean',
            'webhook_alerts' => 'boolean',
            'data_retention_days' => 'required|integer|min:1',
            'api_access' => 'boolean',
            'white_label' => 'boolean',
            'team_members' => 'required|integer|min:1',
            'features' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $subscription->update($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan updated successfully.');
    }

    public function destroy(SubscriptionPlan $subscription)
    {
        if ($subscription->users()->exists()) {
            return back()->with('error', 'Cannot delete subscription plan with active users.');
        }

        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan deleted successfully.');
    }

    public function users(SubscriptionPlan $subscription)
    {
        $users = $subscription->users()
            ->with('payments')
            ->paginate(15);

        return view('admin.subscriptions.users', compact('subscription', 'users'));
    }

    public function toggleStatus(SubscriptionPlan $subscription)
    {
        $subscription->update([
            'is_active' => !$subscription->is_active
        ]);

        $status = $subscription->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Subscription plan {$status} successfully.");
    }
}
