<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;

class ExcludeFromAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    
    {
        $excludedUris = ['/attendance/portal'];

        if (in_array($request->path(), $excludedUris)) {
            return $next($request);
        }

        return auth()->check() ? $next($request) : redirect()->route('/');
    }
}
