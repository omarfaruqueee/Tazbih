<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = 'bn'; // Default language

        if (Auth::check()) {
            $locale = Auth::user()->language_preference ?? 'bn';
        } elseif (session()->has('locale')) {
            $locale = session()->get('locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
