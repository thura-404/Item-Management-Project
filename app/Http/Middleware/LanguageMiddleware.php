<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Session::get('locale') == null) {
            App::setLocale(Session::get('locale'));
        } else {
            Session::put('locale', 'en');
            App::setLocale('en');
        }
        return $next($request);
    }
}
