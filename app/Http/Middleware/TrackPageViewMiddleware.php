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

            // Send event via Meta CAPI
            MetaPixel::sendEvent('PageView', [], [], $eventId);

            // Share event ID and user data with Blade views
            View::share('meta_event_id', $eventId);
            View::share('meta_user_data', MetaPixel::getUserData());
        }

        // Process request pipeline
        $response = $next($request);

        // Attach first-party cookies recommended by Meta Parameter Builder SDK
        if (method_exists($response, 'cookie')) {
            $cookiesToSet = MetaPixel::getCookiesToSet() ?? [];
            foreach ($cookiesToSet as $cookie) {
                $response->cookie(
                    $cookie->name,
                    $cookie->value,
                    $cookie->max_age / 60, // Laravel expects minutes
                    '/',
                    $cookie->domain,
                    $request->isSecure(), // Secure
                    false // HttpOnly (must be false to allow standard JS scripts to access it)
                );
            }
        }

        return $response;
    }
}
