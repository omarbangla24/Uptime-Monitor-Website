<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Remove __construct method

    public function index(Request $request)
    {
        $query = User::with('subscriptionPlan');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        // Filter by subscription status
        if ($request->has('status') && $request->status) {
            $query->where('subscription_status', $request->status);
        }

        // Filter by plan
        if ($request->has('plan') && $request->plan) {
            $query->where('subscription_plan_id', $request->plan);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        $plans = SubscriptionPlan::all();
        $statuses = ['active', 'inactive', 'cancelled', 'past_due', 'trialing'];

        return view('admin.users.index', compact('users', 'plans', 'statuses'));
    }

    // ... rest of your methods remain the same
    public function show(User $user)
    {
        $user->load(['subscriptionPlan', 'websites.monitoringResults' => function($q) {
            $q->latest()->limit(10);
        }, 'payments' => function($q) {
            $q->latest()->limit(10);
        }]);

        $stats = [
            'total_websites' => $user->websites->count(),
            'active_websites' => $user->websites->where('is_active', true)->count(),
            'total_payments' => $user->payments->sum('amount'),
            'last_login' => $user->last_activity_at,
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function create()
    {
        $plans = SubscriptionPlan::active()->get();
        return view('admin.users.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'subscription_plan_id' => 'nullable|exists:subscription_plans,id',
            'subscription_status' => 'nullable|in:active,inactive,cancelled,past_due,trialing',
            'is_admin' => 'boolean',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();

        User::create($validated);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $plans = SubscriptionPlan::active()->get();
        return view('admin.users.edit', compact('user', 'plans'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user)],
            'password' => 'nullable|string|min:8|confirmed',
            'subscription_plan_id' => 'nullable|exists:subscription_plans,id',
            'subscription_status' => 'nullable|in:active,inactive,cancelled,past_due,trialing',
            'subscription_starts_at' => 'nullable|date',
            'subscription_ends_at' => 'nullable|date',
            'is_admin' => 'boolean',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return back()->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->is_admin && User::where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'Cannot delete the last admin user.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User deleted successfully.');
    }

    public function impersonate(User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'Cannot impersonate admin users.');
        }

        session(['impersonate_user_id' => $user->id]);

        return redirect()->route('dashboard')
                        ->with('success', "Now impersonating {$user->name}. Click here to return to admin panel.");
    }

    public function stopImpersonating()
    {
        session()->forget('impersonate_user_id');

        return redirect()->route('admin.dashboard')
                        ->with('success', 'Stopped impersonating user.');
    }
}
