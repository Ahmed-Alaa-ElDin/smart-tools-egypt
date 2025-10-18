<?php

namespace App\Http\Middleware;

use Closure;
use App\Facades\MetaPixel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class TrackPageViewMiddleware
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
            $eventId = Str::uuid();

            MetaPixel::sendEvent('PageView', [], [], $eventId);

            View::share('meta_event_id', $eventId);
        }

        return $next($request);
    }
}
