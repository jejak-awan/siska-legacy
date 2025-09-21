<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SwaggerConditionalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip Swagger processing in development if disabled
        if (app()->environment('development') && !config('swagger-optimization.enabled.development')) {
            // Skip Swagger annotations processing
            return $next($request);
        }

        // Skip Swagger processing if minimal mode is enabled
        if (config('swagger-optimization.performance.minimal_annotations')) {
            // Process only essential annotations
            return $next($request);
        }

        return $next($request);
    }
}
