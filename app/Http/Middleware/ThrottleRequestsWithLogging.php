<?php
// file: app/Http/Middleware/ThrottleRequestsWithLogging.php

namespace App\Http\Middleware;

use App\Services\ActivityHubClient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ThrottleRequestsWithLogging
{
    protected $limiter;
    protected $activityHub;

    public function __construct(RateLimiter $limiter, ActivityHubClient $activityHub)
    {
        $this->limiter = $limiter;
        $this->activityHub = $activityHub;
    }

    public function handle(Request $request, Closure $next, $type = 'default', $decayMinutes = 1): Response
    {
        // Skip rate limiting for static resources
        if ($this->isStaticResource($request)) {
            return $next($request);
        }

        // Define rate limits by type
        $limits = [
            'default' => 60,      // Default: 60 requests per minute
            'read' => 120,        // Read operations: 120 requests per minute
            'create' => 30,       // Create operations: 30 requests per minute
            'update' => 30,       // Update operations: 30 requests per minute
            'delete' => 20,       // Delete operations: 20 requests per minute
            'download' => 15,     // Downloads: 15 requests per minute
            'dashboard' => 100,   // Dashboard access: 100 requests per minute
            'api' => 180,         // API calls: 180 requests per minute
            'profile' => 40,      // Profile operations: 40 requests per minute
            'export' => 5,        // Export operations: 5 requests per minute
            'admin' => 100,       // Admin operations: 100 requests per minute
            'general' => 200      // General throttling: 200 requests per minute
        ];

        // Get the appropriate limit or use default
        $maxAttempts = $limits[$type] ?? $limits['default'];

        // Create a unique key based on IP and route
        $key = $request->ip() . ':' . $type;

        // Check authenticated user role for potential exemptions
        if (auth()->check()) {
            $user = auth()->user();

            // Exempt admin users from rate limiting
            if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                return $next($request);
            }

            // For authenticated users, we can include user ID in the key
            // This makes rate limiting per-user rather than just per-IP
            $key = 'user:' . $user->id . ':' . $type;
        }

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            // Log throttle event, but only log if significantly over the limit
            // to avoid excessive logging
            if ($this->limiter->attempts($key) >= $maxAttempts * 1.5) {
                Log::warning('Rate limit exceeded', [
                    'ip' => $request->ip(),
                    'path' => $request->path(),
                    'method' => $request->method(),
                    'attempts' => $this->limiter->attempts($key),
                    'type' => $type
                ]);

                // Log throttle event to Activity Hub only for significant breaches
                try {
                    $this->activityHub->logSecurityEvent('throttle_limit', 'medium', [
                        'ip_address' => $request->ip(),
                        'endpoint' => $request->path(),
                        'request_count' => $this->limiter->attempts($key),
                        'user_id' => auth()->id(),
                        'user_email' => auth()->user()->email ?? null,
                        'type' => $type
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to log throttle event to Activity Hub: ' . $e->getMessage());
                }
            }

            return $this->buildResponse($request, $key);
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        return $next($request);
    }

    protected function buildResponse(Request $request, $key)
    {
        $retryAfter = $this->limiter->availableIn($key);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => $retryAfter
            ], 429);
        }

        return redirect()->route('security.error', [
            'type' => 'throttle',
            'retryAfter' => $retryAfter
        ])->with('error', 'Terlalu banyak permintaan dalam waktu singkat. Silakan coba lagi setelah ' . ceil($retryAfter / 60) . ' menit.');
    }

    /**
     * Check if request is for a static resource
     */
    protected function isStaticResource(Request $request): bool
    {
        $path = $request->path();
        return preg_match('/(\.css|\.js|\.jpg|\.jpeg|\.png|\.gif|\.ico|\.svg|\.woff2?|\.ttf|\.eot|\.map)$/i', $path);
    }
}