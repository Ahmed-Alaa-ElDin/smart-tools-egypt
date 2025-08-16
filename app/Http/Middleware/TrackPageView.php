<?php

namespace App\Http\Middleware;

use Closure;
use App\Facades\MetaPixel;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->isMethod('get') &&
            !$request->is('api/*') &&
            !$request->is('*admin*') &&
            !$request->ajax()
        ) {
            MetaPixel::sendEvent('PageView');
        }

        return $next($request);
    }
}
