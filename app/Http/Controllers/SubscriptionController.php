<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Show the billing dashboard
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $currentPlan = $user->subscriptionPlan;
        $payments = $user->payments()->latest()->limit(10)->get();
        $upcomingInvoice = $user->getUpcomingInvoice();

        return view('billing.index', [
            'user' => $user,
            'currentPlan' => $currentPlan,
            'payments' => $payments,
            'upcomingInvoice' => $upcomingInvoice,
            'title' => 'Billing & Subscription',
        ]);
    }

    /**
     * Show all available plans
     */
    public function showPlans()
    {
        $plans = SubscriptionPlan::active()->ordered()->get();
        $user = Auth::user();

        return view('billing.plans', [
            'plans' => $plans,
            'user' => $user,
            'title' => 'Choose Your Plan',
        ]);
    }

    /**
     * Show the subscription upgrade page.
     */
    public function showUpgradeForm(Request $request)
    {
        $plans = SubscriptionPlan::active()->ordered()->get();
        $user = Auth::user();

        return view('billing.upgrade', [
            'plans' => $plans,
            'user' => $user,
            'title' => 'Upgrade Your Plan',
            'description' => 'Choose a subscription plan that fits your needs and unlock more features.'
        ]);
    }

    /**
     * Process the subscription upgrade
     */
    public function processUpgrade(Request $request, SubscriptionPlan $plan)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Validate the plan
        if (!$plan->is_active) {
            return back()->with('error', 'This subscription plan is not available.');
        }

        // Check if user already has this plan
        if ($user->subscription_plan_id === $plan->id) {
            return back()->with('info', 'You already have this subscription plan.');
        }

        DB::beginTransaction();

        try {
            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'amount' => $plan->price,
                'currency' => 'USD',
                'status' => 'completed',
                'payment_method' => $request->input('payment_method', 'stripe'),
                'transaction_id' => 'txn_' . uniqid(),
                'paid_at' => now(),
            ]);

            // Update user subscription
            $user->update([
                'subscription_plan_id' => $plan->id,
                'subscription_status' => 'active',
                'subscription_starts_at' => now(),
                'subscription_ends_at' => $plan->interval === 'yearly' ? now()->addYear() : now()->addMonth(),
            ]);

            DB::commit();

            return redirect()->route('billing.success')->with('success', 'Successfully upgraded to ' . $plan->name . ' plan!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'There was an error processing your upgrade. Please try again.');
        }
    }

    /**
     * Show upgrade success page
     */
    public function success()
    {
        return view('billing.success', [
            'title' => 'Upgrade Successful',
        ]);
    }

    /**
     * Cancel subscription
     */
    public function cancel(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        try {
            $user->update([
                'subscription_status' => 'cancelled',
                'subscription_ends_at' => now()->addDays(30), // Grace period
            ]);

            return back()->with('success', 'Your subscription has been cancelled. You can continue using the service until ' . $user->subscription_ends_at->format('M d, Y'));

        } catch (\Exception $e) {
            return back()->with('error', 'There was an error cancelling your subscription. Please contact support.');
        }
    }

    /**
     * Reactivate cancelled subscription
     */
    public function reactivate()
    {
        $user = Auth::user();

        if ($user->subscription_status !== 'cancelled') {
            return back()->with('error', 'Your subscription is not cancelled.');
        }

        try {
            $user->update([
                'subscription_status' => 'active',
                'subscription_ends_at' => $user->subscriptionPlan->interval === 'yearly'
                    ? now()->addYear()
                    : now()->addMonth(),
            ]);

            return back()->with('success', 'Your subscription has been reactivated.');

        } catch (\Exception $e) {
            return back()->with('error', 'There was an error reactivating your subscription.');
        }
    }

    /**
     * Download invoice
     */
    public function downloadInvoice(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        // Generate PDF invoice here
        // For now, return a simple response
        return response()->json([
            'message' => 'Invoice download would be implemented here',
            'payment' => $payment
        ]);
    }
}
