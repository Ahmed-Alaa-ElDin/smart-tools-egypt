<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UnderConstruction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array(auth()->id(), [501, 502, 544])) {
            return $next($request);
        }

        return redirect()->route('front.homepage')->with(['warning' => __('front/homePage.Opens soon')]);
    }
}
