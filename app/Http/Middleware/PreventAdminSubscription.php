<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventAdminSubscription
{
    /**
     * Prevent admin users from accessing subscription-related pages.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('info', 'Subscription management is not available for admin users.');
        }

        return $next($request);
    }
}