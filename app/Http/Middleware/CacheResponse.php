<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response =  $next($request);
        //return $response->header('Cache-Control', 'public, max-age=3600');
        if (
            $request->ajax() ||
            $request->header('X-Livewire') ||
            $request->isMethod('POST') ||
            auth()->check()
        ) {
            return $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        }

        return $response->header('Cache-Control', 'public, max-age=3600');
    }
}
