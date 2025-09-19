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

    /**
     * Show the form for assigning a subscription plan to a user.
     *
     * This manual assignment workflow allows administrators to pick an existing
     * user and a plan, optionally specify start/end dates, and then attach
     * the selected plan to the user immediately. It is useful when no
     * automatic checkout flow is desired or a payment has been handled
     * offline.
     */
    public function assignForm()
    {
        // Exclude admin users from assignment list to prevent overriding their access
        $users = User::where('is_admin', false)->orderBy('name')->get();
        $plans = SubscriptionPlan::active()->orderBy('name')->get();
        return view('admin.subscriptions.assign', compact('users', 'plans'));
    }

    /**
     * Handle the manual assignment of a subscription plan to a user.
     *
     * Validates the incoming request, updates the user record with the
     * selected plan, determines reasonable start/end dates if none are
     * provided, and optionally records a Payment entry to keep track of
     * revenue for reporting purposes.
     */
    public function assign(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $plan = SubscriptionPlan::findOrFail($validated['subscription_plan_id']);

        // Determine subscription start and end dates. If none provided, default
        // to now and add a month/year based on the plan interval. This ensures
        // the plan period aligns with the plan definition.
        $start = $validated['start_date'] ? new \Carbon\Carbon($validated['start_date']) : now();
        $end = $validated['end_date'] ? new \Carbon\Carbon($validated['end_date']) : ($plan->interval === 'yearly'
            ? (clone $start)->addYear()
            : (clone $start)->addMonth());

        // Update the user's subscription fields
        $user->subscription_plan_id = $plan->id;
        $user->subscription_status = 'active';
        $user->subscription_starts_at = $start;
        $user->subscription_ends_at = $end;
        $user->trial_ends_at = null;
        $user->save();

        // Record a payment for accounting purposes. In a real deployment this
        // would reflect the actual method of payment; here we mark it manual.
        /*
         * Record a payment for accounting purposes. We intentionally omit the
         * `gateway` field here because some installations use an integer or
         * enum column for `gateway`, which cannot store arbitrary strings like
         * "manual" without causing a data‑truncation warning. By leaving it
         * unset, the column will fall back to its database default (often
         * NULL or 0), avoiding SQL errors. We also explicitly set the
         * `gateway_transaction_id` to null because many schemas define this
         * column as NOT NULL and expect a value, even if there was no actual
         * transaction with a payment processor.
         */
        Payment::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'transaction_id' => uniqid('manual_', true),
            'gateway_transaction_id' => null,
            'amount' => $plan->price,
            'currency' => 'USD',
            'type' => 'subscription',
            'status' => 'completed',
            'description' => 'Manual subscription assignment',
            'paid_at' => now(),
        ]);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription assigned successfully.');
    }
}
