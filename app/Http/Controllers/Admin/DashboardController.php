<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Carbon::setLocale(app()->getLocale());

        // 1. Key Statistics
        $totalRevenue = (float) Invoice::sum('total');
        $deliveredRevenue = (float) Order::where('status_id', OrderStatus::Delivered->value)
            ->join('invoices', 'orders.id', '=', 'invoices.order_id')
            ->sum('invoices.total');

        $totalOrders = Order::count();
        $pendingOrderStatuses = array_values(array_unique(array_merge(
            config('constants.order_status_type.new_orders', []),
            config('constants.order_status_type.suspended_orders', []),
            [
                OrderStatus::WaitingForApproval->value,
                OrderStatus::WaitingForContact->value,
                OrderStatus::WaitingForPayment->value,
            ]
        )));
        $pendingOrdersCount = Order::whereIn('status_id', $pendingOrderStatuses)->count();

        $totalCustomers = User::whereDoesntHave('roles')
            ->orWhereHas('roles', fn($q) => $q->where('name', 'Customer'))
            ->count();

        $newCustomersThisMonth = User::where(function ($q) {
            $q->whereDoesntHave('roles')
                ->orWhereHas('roles', fn($r) => $r->where('name', 'Customer'));
        })
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        $totalProductsCount = Product::count();
        $activeProductsCount = Product::where('publish', 1)->count();
        $outOfStockCount = Product::where('quantity', '<=', 0)->count();
        $lowStockCount = Product::where('quantity', '>', 0)
            ->whereColumn('quantity', '<=', 'low_stock')
            ->count();

        // 2. Charts Data
        // Daily Sales Chart (Last 7 Days)
        $hasRecentOrders = Order::where('created_at', '>=', now()->subDays(6)->startOfDay())->exists();
        $dailyEndDate = $hasRecentOrders
            ? Carbon::today()
            : Carbon::parse(Order::max('created_at') ?? now())->startOfDay();
        $dailyStartDate = $dailyEndDate->copy()->subDays(6);

        $dailySalesRaw = Order::selectRaw('DATE(orders.created_at) as order_date, COALESCE(SUM(invoices.total), 0) as total_sales, COUNT(orders.id) as orders_count')
            ->leftJoin('invoices', 'orders.id', '=', 'invoices.order_id')
            ->where('orders.created_at', '>=', $dailyStartDate->copy()->startOfDay())
            ->where('orders.created_at', '<=', $dailyEndDate->copy()->endOfDay())
            ->whereNull('orders.deleted_at')
            ->groupBy('order_date')
            ->get()
            ->keyBy('order_date');

        $dailyLabels = [];
        $dailySalesSeries = [];
        $dailyOrdersSeries = [];
        $dailySalesSum = 0;

        for ($date = $dailyStartDate->copy(); $date->lte($dailyEndDate); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            $dailyLabels[] = __('admin/ordersPages.' . $date->format('D'));
            $record = $dailySalesRaw->get($formattedDate);
            $salesVal = $record ? round((float) $record->total_sales, 2) : 0;
            $ordersVal = $record ? (int) $record->orders_count : 0;
            $dailySalesSeries[] = $salesVal;
            $dailyOrdersSeries[] = $ordersVal;
            $dailySalesSum += $salesVal;
        }

        // Monthly Orders Chart (Last 12 Months)
        $monthlyEndDate = Carbon::parse(Order::max('created_at') ?? now())->startOfMonth();
        $monthlyStartDate = $monthlyEndDate->copy()->subMonths(11);

        $monthlyRaw = Order::selectRaw('DATE_FORMAT(orders.created_at, "%Y-%m") as month_str, COUNT(orders.id) as orders_count, COALESCE(SUM(invoices.total), 0) as total_sales')
            ->leftJoin('invoices', 'orders.id', '=', 'invoices.order_id')
            ->where('orders.created_at', '>=', $monthlyStartDate->copy()->startOfMonth())
            ->where('orders.created_at', '<=', $monthlyEndDate->copy()->endOfMonth())
            ->whereNull('orders.deleted_at')
            ->groupBy('month_str')
            ->get()
            ->keyBy('month_str');

        $monthlyLabels = [];
        $monthlyOrdersSeries = [];
        $annualOrdersCount = 0;

        for ($m = $monthlyStartDate->copy(); $m->lte($monthlyEndDate); $m->addMonth()) {
            $key = $m->format('Y-m');
            $monthlyLabels[] = __('admin/dashboard.months_short.' . $m->format('M'));
            $rec = $monthlyRaw->get($key);
            $count = $rec ? (int) $rec->orders_count : 0;
            $monthlyOrdersSeries[] = $count;
            $annualOrdersCount += $count;
        }

        // Orders by Status Distribution Chart
        $deliveredOrdersCount = Order::where('status_id', OrderStatus::Delivered->value)->count();
        $processingOrdersCount = Order::where('status_id', OrderStatus::UnderProcessing->value)->count();
        $waitingApprovalOrdersCount = Order::where('status_id', OrderStatus::WaitingForApproval->value)->count();
        $shippedOrdersCount = Order::where('status_id', OrderStatus::Shipped->value)->count();
        $editingOrdersCount = Order::where('status_id', OrderStatus::UnderEditing->value)->count();
        $returnedCancelledOrdersCount = Order::whereIn('status_id', [
            OrderStatus::ReturnedToBusiness->value,
            OrderStatus::Rejected->value,
            OrderStatus::CancellationRequested->value,
            OrderStatus::CancellationApproved->value,
            OrderStatus::CancellationRejected->value,
        ])->count();

        $statusChartLabels = [
            __('admin/dashboard.Status Delivered'),
            __('admin/dashboard.Status Processing'),
            __('admin/dashboard.Status Waiting Approval'),
            __('admin/dashboard.Status Shipped'),
            __('admin/dashboard.Status Editing'),
            __('admin/dashboard.Status Returned / Cancelled'),
        ];
        $statusChartSeries = [
            $deliveredOrdersCount,
            $processingOrdersCount,
            $waitingApprovalOrdersCount,
            $shippedOrdersCount,
            $editingOrdersCount,
            $returnedCancelledOrdersCount,
        ];

        // 3. Lists and Tables
        // Recent Orders
        $recentOrders = Order::with(['user.phones', 'status', 'invoice'])
            ->latest('id')
            ->limit(7)
            ->get();

        // Pending Orders needing action
        $pendingOrders = Order::with(['user.phones', 'status', 'invoice'])
            ->whereIn('status_id', $pendingOrderStatuses)
            ->latest('id')
            ->limit(5)
            ->get();

        // Low stock products
        $lowStockProducts = Product::with('brand')
            ->where('publish', 1)
            ->where(function ($q) {
                $q->where('quantity', '<=', 0)
                    ->orWhereColumn('quantity', '<=', 'low_stock');
            })
            ->orderBy('quantity', 'asc')
            ->limit(5)
            ->get();

        // Recent customer reviews
        $recentReviews = Review::with(['user', 'reviewable'])
            ->latest('id')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'deliveredRevenue',
            'totalOrders',
            'pendingOrdersCount',
            'totalCustomers',
            'newCustomersThisMonth',
            'totalProductsCount',
            'activeProductsCount',
            'outOfStockCount',
            'lowStockCount',
            'dailyLabels',
            'dailySalesSeries',
            'dailyOrdersSeries',
            'dailySalesSum',
            'monthlyLabels',
            'monthlyOrdersSeries',
            'annualOrdersCount',
            'statusChartLabels',
            'statusChartSeries',
            'deliveredOrdersCount',
            'recentOrders',
            'pendingOrders',
            'lowStockProducts',
            'recentReviews'
        ));
    }
}
