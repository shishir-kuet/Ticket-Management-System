<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $currentPlan = $user->activeSubscription?->plan_name ?? 'free';
        
        return view('subscription.plans', [
            'currentPlan' => $currentPlan
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:professional,enterprise'
        ]);

        $user = auth()->user();
        $planPrices = [
            'professional' => 2999.00,
            'enterprise' => 7999.00
        ];

        // Start database transaction
        DB::beginTransaction();
        try {
            // Create transaction record with 'pending' status
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'amount' => $planPrices[$request->plan],
                'type' => 'subscription',
                'status' => 'pending',
                'currency' => 'BDT',
                'description' => ucfirst($request->plan) . ' Plan Subscription',
            ]);

            DB::commit();

            // Redirect to payment gateway selection page
            return redirect()->route('subscription.payment', [
                'plan' => $request->plan,
                'transaction_id' => $transaction->id,
                'amount' => $planPrices[$request->plan]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Subscription checkout error: ' . $e->getMessage());
            return back()->with('error', 'Unable to process subscription. Please try again.');
        }
    }

    public function cancel()
    {
        $user = auth()->user();
        $subscription = $user->activeSubscription()->first();

        if ($subscription) {
            $subscription->cancel();
            return back()->with('success', 'Your subscription has been cancelled. You can continue using premium features until the end of your billing period.');
        }

        return back()->with('error', 'No active subscription found.');
    }

    public function downgrade()
    {
        $user = auth()->user();
        $subscription = $user->activeSubscription()->first();

        if ($subscription && $subscription->plan_name !== 'free') {
            // Create free plan subscription starting after current one ends
            Subscription::create([
                'user_id' => $user->id,
                'plan_name' => 'free',
                'price' => 0,
                'billing_cycle' => 'monthly',
                'starts_at' => $subscription->ends_at ?? now(),
                'auto_renew' => true,
            ]);

            return back()->with('success', 'Your plan will be downgraded to Free at the end of your current billing period.');
        }

        return back()->with('error', 'You are already on the Free plan.');
    }

    /**
     * Show payment gateway selection page
     */
    public function showPayment(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:professional,enterprise',
            'transaction_id' => 'required|integer|exists:transactions,id',
            'amount' => 'required|numeric'
        ]);

        $user = auth()->user();
        
        // Verify transaction belongs to user
        $transaction = Transaction::where('id', $request->transaction_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('subscription.payment', [
            'plan' => $request->plan,
            'transactionId' => $request->transaction_id,
            'amount' => $request->amount,
            'transaction' => $transaction
        ]);
    }

    /**
     * Process payment and activate subscription
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:professional,enterprise',
            'transaction_id' => 'required|integer|exists:transactions,id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|in:bkash,nagad,rocket,bank_transfer'
        ]);

        $user = auth()->user();
        
        // Verify transaction
        $transaction = Transaction::where('id', $request->transaction_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Update transaction with payment method (but keep status as pending for now)
        $transaction->update([
            'payment_method' => $request->payment_method,
        ]);

        // TODO: Integrate with actual payment gateway (bKash API, Nagad API, etc.)
        // For now, we'll simulate successful payment
        // In production, you would:
        // 1. Call the payment gateway API
        // 2. Get payment confirmation
        // 3. Verify payment status
        // 4. Then activate subscription

        DB::beginTransaction();
        try {
            // Mark transaction as completed (simulating successful payment)
            $transaction->update(['status' => 'completed', 'paid_at' => now()]);

            // Create or update subscription
            $subscription = Subscription::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'plan_name' => $request->plan,
                    'price' => $request->amount,
                    'billing_cycle' => 'monthly',
                    'starts_at' => now(),
                    'ends_at' => now()->addMonth(),
                    'auto_renew' => true,
                    'cancelled_at' => null,
                ]
            );

            DB::commit();

            return redirect()->route('subscription.billing')->with('success', 
                'Payment successful! Your ' . ucfirst($request->plan) . ' plan is now active. Transaction ID: #' . $transaction->id
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $transaction->update(['status' => 'failed']);
            \Log::error('Payment processing error: ' . $e->getMessage());
            return back()->with('error', 'Payment processing failed. Please try again.');
        }
    }
    public function billing()
    {
        $user = auth()->user();
        $subscription = $user->activeSubscription()->first();
        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('subscription.billing', [
            'subscription' => $subscription,
            'transactions' => $transactions,
            'currentPlan' => $subscription?->plan_name ?? 'free'
        ]);
    }

    /**
     * Show user's invoices
     */
    public function invoices()
    {
        $user = auth()->user();
        $invoices = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('subscription.invoices', [
            'invoices' => $invoices
        ]);
    }

    /**
     * Download an invoice
     */
    public function downloadInvoice($invoiceId)
    {
        $user = auth()->user();
        $invoice = Transaction::where('id', $invoiceId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // TODO: Implement actual PDF generation when payment system is ready
        return back()->with('info', 'Invoice download not yet implemented. Please contact support.');
    }
}