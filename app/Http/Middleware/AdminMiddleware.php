<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!\Auth::check() || \Auth::user()->email !== 'omarfaruuuk@gmail.com') {
            abort(403, 'Unauthorized access. Only configured administrator can access this panel.');
        }

        return $next($request);
    }
}
