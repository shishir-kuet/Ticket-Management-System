<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Skip for admin users
            if ($user->isAdmin()) {
                return $next($request);
            }

            // Get active subscription
            $subscription = $user->activeSubscription;
            
            // Check if user has no subscription or only free plan
            if (!$subscription || $subscription->plan_name === 'free') {
                // Get ticket count for this month
                $monthlyTickets = $user->createdTickets()
                    ->whereMonth('created_at', now()->month)
                    ->count();

                // If approaching or exceeded free plan limit, flash upgrade message
                if ($monthlyTickets >= 40) { // 80% of free plan limit (50)
                    $remaining = 50 - $monthlyTickets;
                    if ($remaining > 0) {
                        session()->flash('subscription_alert', [
                            'type' => 'warning',
                            'message' => "You have {$remaining} tickets remaining in your free plan this month. Consider upgrading to avoid service interruption."
                        ]);
                    } else {
                        session()->flash('subscription_alert', [
                            'type' => 'danger',
                            'message' => "You've reached your monthly ticket limit. Upgrade to our Professional plan for unlimited tickets!"
                        ]);
                    }
                }
            }
            
            // Check if subscription is expiring soon
            if ($subscription && $subscription->ends_at && $subscription->ends_at->diffInDays(now()) <= 7) {
                session()->flash('subscription_alert', [
                    'type' => 'info',
                    'message' => "Your subscription will expire in {$subscription->ends_at->diffInDays(now())} days. Renew now to maintain uninterrupted service."
                ]);
            }
        }

        return $next($request);
    }
}