<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Subscription;

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

            // Get active subscription (may be null)
            $subscription = $user->activeSubscription;

            // Determine ticket limit for this user (from subscription if present, otherwise default free plan)
            if ($subscription) {
                $ticketLimit = $subscription->getTicketLimit();
                $planName = $subscription->plan_name;
            } else {
                // Use fully-qualified constants to avoid resolution issues in some static analyzers
                $planName = \App\Models\Subscription::PLAN_FREE;
                $ticketLimit = \App\Models\Subscription::defaultTicketLimitForPlan($planName);
            }

            // Only compute usage alerts for finite (non -1) limits
            if ($ticketLimit > 0) {
                // Get ticket count for this month
                $monthlyTickets = $user->createdTickets()
                    ->whereMonth('created_at', now()->month)
                    ->count();

                // Compute threshold at 80% of allowed tickets
                $threshold = (int) floor($ticketLimit * 0.8);

                if ($monthlyTickets >= $threshold) {
                    $remaining = max(0, $ticketLimit - $monthlyTickets);
                    if ($remaining > 0) {
                        session()->flash('subscription_alert', [
                            'type' => 'warning',
                            'message' => "You have {$remaining} tickets remaining in your {$planName} plan this month. Consider upgrading to avoid service interruption."
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