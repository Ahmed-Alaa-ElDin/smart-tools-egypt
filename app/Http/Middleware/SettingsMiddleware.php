<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class SettingsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $settings = Setting::first();

        if ($settings) {
            Config::set('settings', $settings->toArray());
        }

        return $next($request);
    }
}
