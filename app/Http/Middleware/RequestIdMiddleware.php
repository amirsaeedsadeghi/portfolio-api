<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $rid = $request->headers->get('X-Request-Id') ?: Str::uuid()->toString();
        $request->headers->set('X-Request-Id', $rid);
        app()->instance('request_id', $rid);

        $response = $next($request);
        $response->headers->set('X-Request-Id', $rid);
        return $response;
    }
}
