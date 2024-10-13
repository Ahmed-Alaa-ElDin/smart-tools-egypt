<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SidebarDataMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $counts = Order::select('status_id', \DB::raw('count(*) as count'))
            ->whereIn('status_id', array_merge(
                config('constants.order_status_type.new_orders'),
                config('constants.order_status_type.approved_orders'),
                config('constants.order_status_type.edited_orders'),
                config('constants.order_status_type.ready_for_shipping_orders'),
                config('constants.order_status_type.shipped_orders'),
                config('constants.order_status_type.delivered_orders'),
                config('constants.order_status_type.suspended_orders'),
            ))
            ->groupBy('status_id')
            ->pluck('count', 'status_id')
            ->toArray();

        $ordersCounts = [
            'new_orders' => array_sum(array_intersect_key($counts, array_flip(config('constants.order_status_type.new_orders')))),
            'approved_orders' => array_sum(array_intersect_key($counts, array_flip(config('constants.order_status_type.approved_orders')))),
            'edited_orders' => array_sum(array_intersect_key($counts, array_flip(config('constants.order_status_type.edited_orders')))),
            'ready_for_shipping_orders' => array_sum(array_intersect_key($counts, array_flip(config('constants.order_status_type.ready_for_shipping_orders')))),
            'shipped_orders' => array_sum(array_intersect_key($counts, array_flip(config('constants.order_status_type.shipped_orders')))),
            'delivered_orders' => array_sum(array_intersect_key($counts, array_flip(config('constants.order_status_type.delivered_orders')))),
            'suspended_orders' => array_sum(array_intersect_key($counts, array_flip(config('constants.order_status_type.suspended_orders')))),
        ];

        View::share('ordersCounts', $ordersCounts);

        return $next($request);
    }
}
