<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = optional($request->user())->id ?? 'guest';
        $endpoint = $request->method() . ' ' . $request->path();
        $timestamp = now()->toDateTimeString();

        Log::info("API Request: user_id={$userId}, endpoint={$endpoint}, timestamp={$timestamp}");

        return $next($request);
    }
}