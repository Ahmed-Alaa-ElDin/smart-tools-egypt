<?php

namespace App\Http\Middleware;

use App\Models\Zone;
use Closure;
use Illuminate\Http\Request;

class CanDeliver
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
        try {
            $address = auth()->user()->addresses->where('default', 1)->firstOrFail();

            // Get City Id
            $city_id = $address->city->id;

            // Get Destinations and Zones for the city
            Zone::with(['destinations'])
                ->where('is_active', 1)
                ->whereHas('destinations', fn ($q) => $q->where('city_id', $city_id))
                ->whereHas('delivery', fn ($q) => $q->where('is_active', 1))
                ->firstOrFail();

            return $next($request);
        } catch (\Throwable $th) {
            return redirect()->route('front.order.shipping');
        }
    }
}
