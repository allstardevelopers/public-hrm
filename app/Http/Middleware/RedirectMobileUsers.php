<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RedirectMobileUsers
{
    public function handle($request, Closure $next)
    {
        $agent = $request->header('User-Agent');
        if(preg_match('/(android|iphone|ipod|ipad)/i', $agent)) {
            // Request is coming from a mobile device
            // Redirect to mobile version of the site
            return redirect()->route('mobile.home');
        }
        return $next($request);
    }
}
