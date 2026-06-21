<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogTraffic
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            // Only log GET requests that are HTML page views, excluding AJAX, API, and Livewire update routes
            if ($request->isMethod('GET') 
                && !$request->ajax() 
                && !$request->hasHeader('X-Livewire')
                && !str_contains($request->path(), 'livewire')
                && !str_contains($request->path(), 'api')
                && !str_contains($request->path(), '_debugbar')
            ) {
                \DB::table('traffic_logs')->insert([
                    'url' => $request->path() === '/' ? 'home' : $request->path(),
                    'user_id' => \Auth::id(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Silently log and continue to prevent blocking the request
            \Log::error('Traffic Logging error: ' . $e->getMessage());
        }

        return $response;
    }
}
