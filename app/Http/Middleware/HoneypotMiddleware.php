<?php

namespace App\Http\Middleware;

use App\Traits\HandleHoneypot;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HoneypotMiddleware
{
    use HandleHoneypot;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethodSafe() === false) {
            $this->assertHoneypot($request);
        }
        return $next($request);
    }
}
