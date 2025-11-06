<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\ActivityHubClient;

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

        // Define rate limits by type - LEBIH LONGGAR untuk production
        $limits = [
            'default' => 100,      // Default: 100 requests per minute
            'read' => 200,         // Read operations: 200 requests per minute
            'create' => 100,       // Create operations: 100 requests per minute (DINAIKKAN)
            'update' => 100,       // Update operations: 100 requests per minute
            'delete' => 50,        // Delete operations: 50 requests per minute
            'download' => 50,      // Downloads: 50 requests per minute
            'dashboard' => 200,    // Dashboard access: 200 requests per minute
            'api' => 300,          // API calls: 300 requests per minute
            'profile' => 100,      // Profile operations: 100 requests per minute
            'export' => 20,        // Export operations: 20 requests per minute
            'admin' => 200,        // Admin operations: 200 requests per minute
            'general' => 300       // General throttling: 300 requests per minute
        ];

        // Get the appropriate limit or use default
        $maxAttempts = $limits[$type] ?? $limits['default'];

        // Exempt admin and power users from rate limiting
        if (auth()->check()) {
            $user = auth()->user();

            // Check if user is admin or has special roles that should bypass throttling
            if (method_exists($user, 'hasRole') &&
                ($user->hasRole('admin') || $user->hasRole('power_user') || $user->hasRole('manager'))) {
                return $next($request);
            }

            // For authenticated users, we can include user ID in the key
            // This makes rate limiting per-user rather than just per-IP
            $key = 'user:' . $user->id . ':' . $type;
        } else {
            // Create a unique key based on IP and route
            $key = $request->ip() . ':' . $type;
        }

        // Add diagnostic logging to track request rates (can be disabled in production)
        if (config('app.debug') || config('logging.throttle_debug', false)) {
            Log::info('Throttle request tracking', [
                'ip' => $request->ip(),
                'path' => $request->path(),
                'method' => $request->method(),
                'type' => $type,
                'key' => $key,
                'current_attempts' => $this->limiter->attempts($key),
                'max_attempts' => $maxAttempts
            ]);
        }

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            // Log throttle event, but only log if significantly over the limit
            // to avoid excessive logging
            if ($this->limiter->attempts($key) >= $maxAttempts * 1.2) {
                Log::warning('Rate limit exceeded', [
                    'ip' => $request->ip(),
                    'path' => $request->path(),
                    'method' => $request->method(),
                    'attempts' => $this->limiter->attempts($key),
                    'type' => $type,
                    'user_id' => auth()->id()
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

        // Track this request
        $this->limiter->hit($key, $decayMinutes * 60);

        return $next($request);
    }

    protected function buildResponse(Request $request, $key)
    {
        $retryAfter = $this->limiter->availableIn($key);

        // Improved error message with details for debugging
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => $retryAfter,
                'retry_after_minutes' => ceil($retryAfter / 60),
                'error_code' => 'rate_limit_exceeded'
            ], 429);
        }

        // Simplified error message for normal web requests
        return redirect()->route('security.error', [
            'type' => 'throttle',
            'retryAfter' => $retryAfter,
            'debug' => config('app.debug') ? '1' : '0'
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