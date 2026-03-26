<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            App::setLocale($locale);
            
            // Sync to user if logged in and record differs
            if (auth()->check() && auth()->user()->language !== $locale) {
                auth()->user()->update(['language' => $locale]);
            }
        } elseif (auth()->check() && auth()->user()->language) {
            $locale = auth()->user()->language;
            App::setLocale($locale);
            Session::put('locale', $locale);
        }

        return $next($request);
    }
}
