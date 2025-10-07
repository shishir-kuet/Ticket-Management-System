<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ChatbotRateLimit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $identifier = $this->getIdentifier($request);
        $key = "chatbot_rate_limit:{$identifier}";
        
        // Rate limits
        $maxRequests = 30; // requests per minute
        $maxAIRequests = 10; // AI requests per minute (more expensive)
        
        $currentRequests = Cache::get($key, 0);
        $aiRequestsKey = "chatbot_ai_limit:{$identifier}";
        $currentAIRequests = Cache::get($aiRequestsKey, 0);
        
        // Check if this is an AI request (no action = AI request)
        $isAIRequest = !$request->has('action') && $request->filled('message');
        
        // Check general rate limit
        if ($currentRequests >= $maxRequests) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please wait a moment before trying again.',
                'retry_after' => 60
            ], 429);
        }
        
        // Check AI-specific rate limit
        if ($isAIRequest && $currentAIRequests >= $maxAIRequests) {
            return response()->json([
                'success' => false,
                'message' => 'AI request limit reached. Please try using quick actions or wait a moment.',
                'retry_after' => 60
            ], 429);
        }
        
        // Increment counters
        Cache::put($key, $currentRequests + 1, 60); // 1 minute TTL
        
        if ($isAIRequest) {
            Cache::put($aiRequestsKey, $currentAIRequests + 1, 60);
        }
        
        $response = $next($request);
        
        // Add rate limit headers
        $response->headers->set('X-RateLimit-Limit', $maxRequests);
        $response->headers->set('X-RateLimit-Remaining', max(0, $maxRequests - $currentRequests - 1));
        
        if ($isAIRequest) {
            $response->headers->set('X-RateLimit-AI-Limit', $maxAIRequests);
            $response->headers->set('X-RateLimit-AI-Remaining', max(0, $maxAIRequests - $currentAIRequests - 1));
        }
        
        return $response;
    }
    
    /**
     * Get unique identifier for rate limiting
     */
    private function getIdentifier(Request $request): string
    {
        if (Auth::check()) {
            return 'user:' . Auth::id();
        }
        
        // For anonymous users, use IP address
        return 'ip:' . $request->ip();
    }
}