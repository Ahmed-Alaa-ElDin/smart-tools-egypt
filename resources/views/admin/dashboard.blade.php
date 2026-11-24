@extends('layouts.admin.admin', ['activeSection' => 'dashboard', 'activePage' => '', 'titlePage' => __('admin/master.dashboard')])

@push('css')
    <style>
        /* Scoped styling for bidirectional (ar & en) support and proper number rendering */
        html[dir="rtl"] .card-chart .card-body,
        html[dir="rtl"] .card-chart .card-footer {
            direction: rtl;
            text-align: right;
        }
        html[dir="rtl"] .card-chart .card-footer .stats {
            text-align: right;
        }
        html[dir="rtl"] .card-stats .card-header.card-header-icon .card-icon {
            float: right;
            margin-left: 15px;
            margin-right: 0;
        }
        html[dir="rtl"] .card-stats .card-header.card-header-icon {
            text-align: left;
        }
        html[dir="rtl"] .card-stats .card-footer .stats {
            text-align: right;
        }
        html[dir="rtl"] .card-stats .card-footer .stats i {
            margin-left: 4px;
            margin-right: 0;
        }
        html[dir="rtl"] .ct-chart .ct-label {
            font-family: 'Cairo', sans-serif !important;
            font-size: 11px;
            font-weight: 600;
        }
        html[dir="ltr"] .ct-chart .ct-label {
            font-family: 'Lato', sans-serif !important;
            font-size: 11px;
            font-weight: 600;
        }
        .ct-chart .ct-grid {
            stroke: rgba(255, 255, 255, 0.25);
        }
        .ct-chart .ct-label {
            color: rgba(255, 255, 255, 0.9);
            fill: rgba(255, 255, 255, 0.9);
        }
        .ct-chart .ct-bar {
            stroke-width: 12px;
        }
        /* Ensure numbers and currencies render without BiDi inversion */
        .num-val {
            display: inline-block;
            direction: ltr;
            unicode-bidi: isolate;
        }
    </style>
@endpush

@section('content')
    <div class="content">
        <div class="container-fluid">

            {{-- 1. Top Stat Cards --}}
            <div class="row">
                {{-- Total Revenue --}}
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-success card-header-icon">
                            <div class="card-icon">
                                <i class="material-icons">paid</i>
                            </div>
                            <p class="card-category">{{ __('admin/dashboard.Total Revenue') }}</p>
                            <h3 class="card-title">
                                <span class="num-val">{{ number_format($totalRevenue) }}</span>
                                <small>{{ __('admin/dashboard.EGP') }}</small>
                            </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats text-truncate">
                                <i class="material-icons text-success">check_circle</i>
                                {{ __('admin/dashboard.Delivered Revenue') }}:
                                <span class="num-val font-weight-bold">{{ number_format($deliveredRevenue) }}</span> {{ __('admin/dashboard.EGP') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Orders --}}
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-info card-header-icon">
                            <div class="card-icon">
                                <i class="material-icons">shopping_bag</i>
                            </div>
                            <p class="card-category">{{ __('admin/dashboard.Total Orders') }}</p>
                            <h3 class="card-title">
                                <span class="num-val">{{ number_format($totalOrders) }}</span>
                            </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats text-truncate">
                                @if ($pendingOrdersCount > 0)
                                    <i class="material-icons text-warning">warning</i>
                                    <a href="{{ route('admin.orders.new-orders') }}" class="text-warning font-weight-bold">
                                        <span class="num-val">{{ number_format($pendingOrdersCount) }}</span> {{ __('admin/dashboard.Pending Action') }}
                                    </a>
                                @else
                                    <i class="material-icons text-success">done_all</i>
                                    {{ __('admin/dashboard.All caught up') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Customers --}}
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-warning card-header-icon">
                            <div class="card-icon">
                                <i class="material-icons">people</i>
                            </div>
                            <p class="card-category">{{ __('admin/dashboard.Customers') }}</p>
                            <h3 class="card-title">
                                <span class="num-val">{{ number_format($totalCustomers) }}</span>
                            </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats text-truncate">
                                <i class="material-icons">person_add</i>
                                <a href="{{ route('admin.customers.index') }}">
                                    <span class="num-val">{{ number_format($newCustomersThisMonth) }}</span> {{ __('admin/dashboard.new this month') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Active Products & Stock --}}
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-danger card-header-icon">
                            <div class="card-icon">
                                <i class="material-icons">inventory_2</i>
                            </div>
                            <p class="card-category">{{ __('admin/dashboard.Active Products') }}</p>
                            <h3 class="card-title">
                                <span class="num-val">{{ number_format($activeProductsCount) }}</span>
                                <small class="text-muted">/ <span class="num-val">{{ number_format($totalProductsCount) }}</span></small>
                            </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats text-truncate">
                                @if ($outOfStockCount > 0 || $lowStockCount > 0)
                                    <i class="material-icons text-danger">warning</i>
                                    <a href="{{ route('admin.products.index') }}" class="text-danger font-weight-bold">
                                        <span class="num-val">{{ number_format($outOfStockCount) }}</span> {{ __('admin/dashboard.out of stock') }},
                                        <span class="num-val">{{ number_format($lowStockCount) }}</span> {{ __('admin/dashboard.low stock') }}
                                    </a>
                                @else
                                    <i class="material-icons text-success">check_circle</i>
                                    {{ __('admin/dashboard.Stock levels healthy') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Real Analytics Charts --}}
            <div class="row">
                {{-- Daily Sales Chart --}}
                <div class="col-md-4">
                    <div class="card card-chart">
                        <div class="card-header card-header-success">
                            <div class="ct-chart" id="dailySalesChart"></div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">{{ __('admin/dashboard.Daily Sales') }}</h4>
                            <p class="card-category text-muted">
                                {{ __('admin/dashboard.Sales over the last 7 days') }}
                            </p>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="material-icons">payments</i>
                                {{ __('admin/dashboard.Total') }}: <strong class="num-val">{{ number_format($dailySalesSum) }}</strong> {{ __('admin/dashboard.EGP') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Monthly Orders Chart --}}
                <div class="col-md-4">
                    <div class="card card-chart">
                        <div class="card-header card-header-warning">
                            <div class="ct-chart" id="monthlyOrdersChart"></div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">{{ __('admin/dashboard.Monthly Orders') }}</h4>
                            <p class="card-category text-muted">
                                {{ __('admin/dashboard.Order volume over the last 12 months') }}
                            </p>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="material-icons">calendar_today</i>
                                {{ __('admin/dashboard.Total Annual Orders') }}: <strong class="num-val">{{ number_format($annualOrdersCount) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Orders by Status Chart --}}
                <div class="col-md-4">
                    <div class="card card-chart">
                        <div class="card-header card-header-danger">
                            <div class="ct-chart" id="orderStatusChart"></div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">{{ __('admin/dashboard.Orders by Status') }}</h4>
                            <p class="card-category text-muted">
                                {{ __('admin/dashboard.Breakdown of orders across fulfillment stages') }}
                            </p>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="material-icons">local_shipping</i>
                                <span class="num-val font-weight-bold">{{ number_format($deliveredOrdersCount) }}</span> {{ __('admin/dashboard.delivered orders') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Detailed Data Sections --}}
            <div class="row">
                {{-- Recent Orders Table --}}
                <div class="col-lg-7 col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title">{{ __('admin/dashboard.Recent Orders') }}</h4>
                                <p class="card-category">{{ __('admin/dashboard.Latest customer orders placed') }}</p>
                            </div>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-white font-weight-bold">
                                {{ __('admin/dashboard.View All Orders') }}
                                <span class="inline-block rtl:rotate-180 font-bold">&rarr;</span>
                            </a>
                        </div>
                        <div class="card-body table-responsive">
                            @if ($recentOrders->count() > 0)
                                <table class="table table-hover align-middle">
                                    <thead class="text-primary">
                                        <th class="ltr:text-left rtl:text-right">{{ __('admin/dashboard.Order ID') }}</th>
                                        <th class="ltr:text-left rtl:text-right">{{ __('admin/dashboard.Customer') }}</th>
                                        <th class="ltr:text-left rtl:text-right">{{ __('admin/dashboard.Total') }}</th>
                                        <th class="ltr:text-left rtl:text-right">{{ __('admin/dashboard.Status') }}</th>
                                        <th class="ltr:text-left rtl:text-right">{{ __('admin/dashboard.Date') }}</th>
                                        <th class="text-center">{{ __('admin/dashboard.Actions') }}</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentOrders as $order)
                                            <tr>
                                                <td class="font-weight-bold ltr:text-left rtl:text-right">
                                                    <span class="num-val">#{{ $order->id }}</span>
                                                </td>
                                                <td class="ltr:text-left rtl:text-right">
                                                    @if ($order->user)
                                                        <div class="font-weight-bold">{{ $order->user->f_name }} {{ $order->user->l_name }}</div>
                                                        @if ($order->user->phones && $order->user->phones->count())
                                                            <small class="text-muted num-val">{{ $order->user->phones->first()->phone }}</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">{{ __('admin/dashboard.N/A') }}</span>
                                                    @endif
                                                </td>
                                                <td class="font-weight-bold text-nowrap ltr:text-left rtl:text-right">
                                                    <span class="num-val">{{ number_format($order->invoice?->total ?? 0) }}</span> {{ __('admin/dashboard.EGP') }}
                                                </td>
                                                <td class="ltr:text-left rtl:text-right">
                                                    @php
                                                        $statusClass = 'badge-secondary';
                                                        if (in_array($order->status_id, [3, 45, 12])) {
                                                            $statusClass = 'badge-success';
                                                        } elseif (in_array($order->status_id, [1, 2, 14, 15, 16, 201, 208, 212])) {
                                                            $statusClass = 'badge-warning';
                                                        } elseif (in_array($order->status_id, [4, 5, 6, 207, 209, 210])) {
                                                            $statusClass = 'badge-info';
                                                        } elseif (in_array($order->status_id, [8, 9, 13, 46, 211, 301, 302, 303])) {
                                                            $statusClass = 'badge-danger';
                                                        }
                                                    @endphp
                                                    <span class="badge {{ $statusClass }} px-2 py-1">
                                                        {{ $order->status ? $order->status->name : __('admin/dashboard.N/A') }}
                                                    </span>
                                                </td>
                                                <td class="text-nowrap text-muted text-sm ltr:text-left rtl:text-right">
                                                    {{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}
                                                </td>
                                                <td class="td-actions text-center">
                                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                                        class="btn btn-primary btn-link btn-sm"
                                                        rel="tooltip"
                                                        title="{{ __('admin/dashboard.View Details') }}">
                                                        <i class="material-icons">visibility</i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="py-4 text-center text-muted">
                                    <i class="material-icons mb-2" style="font-size: 48px;">inbox</i>
                                    <p>{{ __('admin/ordersPages.No data in this table') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Actionable Alerts Tabs (Pending, Low Stock, Reviews) --}}
                <div class="col-lg-5 col-md-12">
                    <div class="card">
                        <div class="card-header card-header-tabs card-header-warning">
                            <div class="nav-tabs-navigation">
                                <div class="nav-tabs-wrapper">
                                    <ul class="nav nav-tabs" data-tabs="tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#tab-pending-orders" data-toggle="tab">
                                                <i class="material-icons">hourglass_top</i>
                                                {{ __('admin/dashboard.Pending Orders') }}
                                                @if ($pendingOrders->count() > 0)
                                                    <span class="badge badge-light ltr:ml-1 rtl:mr-1 num-val">{{ $pendingOrders->count() }}</span>
                                                @endif
                                                <div class="ripple-container"></div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#tab-low-stock" data-toggle="tab">
                                                <i class="material-icons">warning</i>
                                                {{ __('admin/dashboard.Low Stock Alerts') }}
                                                @if ($lowStockProducts->count() > 0)
                                                    <span class="badge badge-light ltr:ml-1 rtl:mr-1 num-val">{{ $lowStockProducts->count() }}</span>
                                                @endif
                                                <div class="ripple-container"></div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#tab-recent-reviews" data-toggle="tab">
                                                <i class="material-icons">rate_review</i>
                                                {{ __('admin/dashboard.Latest Reviews') }}
                                                @if ($recentReviews->count() > 0)
                                                    <span class="badge badge-light ltr:ml-1 rtl:mr-1 num-val">{{ $recentReviews->count() }}</span>
                                                @endif
                                                <div class="ripple-container"></div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                {{-- Tab 1: Pending Orders --}}
                                <div class="tab-pane active" id="tab-pending-orders">
                                    @if ($pendingOrders->count() > 0)
                                        <table class="table table-hover">
                                            <tbody>
                                                @foreach ($pendingOrders as $order)
                                                    <tr>
                                                        <td class="ltr:text-left rtl:text-right">
                                                            <div class="font-weight-bold">
                                                                <a href="{{ route('admin.orders.show', $order->id) }}">
                                                                    <span class="num-val">#{{ $order->id }}</span> - {{ $order->user ? $order->user->f_name . ' ' . $order->user->l_name : __('admin/dashboard.N/A') }}
                                                                </a>
                                                            </div>
                                                            <small class="text-muted">
                                                                <span class="num-val font-weight-bold">{{ number_format($order->invoice?->total ?? 0) }}</span> {{ __('admin/dashboard.EGP') }} &bull; {{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}
                                                            </small>
                                                        </td>
                                                        <td class="ltr:text-right rtl:text-left">
                                                            <span class="badge badge-warning px-2 py-1">
                                                                {{ $order->status ? $order->status->name : __('admin/dashboard.N/A') }}
                                                            </span>
                                                        </td>
                                                        <td class="td-actions text-center">
                                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                                                class="btn btn-warning btn-link btn-sm"
                                                                rel="tooltip"
                                                                title="{{ __('admin/dashboard.View Details') }}">
                                                                <i class="material-icons">visibility</i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="text-center pt-2">
                                            <a href="{{ route('admin.orders.new-orders') }}" class="btn btn-sm btn-link text-warning font-weight-bold">
                                                {{ __('admin/dashboard.View All Orders') }}
                                                <span class="inline-block rtl:rotate-180 font-bold">&rarr;</span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="py-4 text-center text-muted">
                                            <i class="material-icons text-success mb-2" style="font-size: 48px;">check_circle</i>
                                            <p>{{ __('admin/dashboard.No pending orders') }}</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Tab 2: Low Stock Products --}}
                                <div class="tab-pane" id="tab-low-stock">
                                    @if ($lowStockProducts->count() > 0)
                                        <table class="table table-hover">
                                            <tbody>
                                                @foreach ($lowStockProducts as $product)
                                                    <tr>
                                                        <td class="ltr:text-left rtl:text-right">
                                                            <div class="font-weight-bold text-truncate" style="max-width: 200px;">
                                                                {{ $product->name }}
                                                            </div>
                                                            <small class="text-muted">
                                                                {{ $product->brand ? $product->brand->name : '' }}
                                                            </small>
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($product->quantity <= 0)
                                                                <span class="badge badge-danger">
                                                                    0 {{ __('admin/dashboard.unit') }}
                                                                </span>
                                                            @else
                                                                <span class="badge badge-warning">
                                                                    <span class="num-val">{{ number_format($product->quantity) }}</span> {{ __('admin/dashboard.unit') }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="td-actions ltr:text-right rtl:text-left">
                                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                                                class="btn btn-primary btn-link btn-sm"
                                                                rel="tooltip"
                                                                title="{{ __('admin/dashboard.Edit Product') }}">
                                                                <i class="material-icons">edit</i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="text-center pt-2">
                                            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-link text-danger font-weight-bold">
                                                {{ __('admin/productsPages.All Products') }}
                                                <span class="inline-block rtl:rotate-180 font-bold">&rarr;</span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="py-4 text-center text-muted">
                                            <i class="material-icons text-success mb-2" style="font-size: 48px;">check_circle</i>
                                            <p>{{ __('admin/dashboard.No low stock products') }}</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Tab 3: Recent Reviews --}}
                                <div class="tab-pane" id="tab-recent-reviews">
                                    @if ($recentReviews->count() > 0)
                                        <table class="table table-hover">
                                            <tbody>
                                                @foreach ($recentReviews as $review)
                                                    <tr>
                                                        <td class="ltr:text-left rtl:text-right">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="font-weight-bold">
                                                                    {{ $review->user ? $review->user->f_name . ' ' . $review->user->l_name : __('admin/dashboard.N/A') }}
                                                                </span>
                                                                <span class="text-warning">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        @if ($i <= $review->rating)
                                                                            <i class="material-icons" style="font-size: 14px;">star</i>
                                                                        @else
                                                                            <i class="material-icons" style="font-size: 14px;">star_border</i>
                                                                        @endif
                                                                    @endfor
                                                                </span>
                                                            </div>
                                                            <p class="text-muted text-sm mb-1 text-truncate" style="max-width: 250px;">
                                                                @if ($review->reviewable)
                                                                    <span class="font-weight-bold text-dark">{{ $review->reviewable->name }}:</span>
                                                                @endif
                                                                {{ $review->comment ?: __('admin/dashboard.Review') }}
                                                            </p>
                                                            <small class="text-muted">
                                                                {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
                                                            </small>
                                                        </td>
                                                        <td class="ltr:text-right rtl:text-left">
                                                            @if ($review->status == 1)
                                                                <span class="badge badge-success">{{ __('admin/dashboard.Status Approved') }}</span>
                                                            @elseif ($review->status == 0)
                                                                <span class="badge badge-warning">{{ __('admin/dashboard.Status Pending') }}</span>
                                                            @else
                                                                <span class="badge badge-danger">{{ __('admin/dashboard.Status Rejected') }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="text-center pt-2">
                                            <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-link text-primary font-weight-bold">
                                                {{ __('admin/dashboard.View All Reviews') }}
                                                <span class="inline-block rtl:rotate-180 font-bold">&rarr;</span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="py-4 text-center text-muted">
                                            <i class="material-icons text-muted mb-2" style="font-size: 48px;">rate_review</i>
                                            <p>{{ __('admin/dashboard.No recent reviews') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            function formatAxisNumber(val) {
                if (val >= 1000000) {
                    return (val / 1000000).toFixed(1) + 'M';
                }
                if (val >= 1000) {
                    return (val / 1000).toFixed(0) + 'k';
                }
                return val;
            }

            function initRealDashboardCharts() {
                // 1. Daily Sales Chart
                if ($('#dailySalesChart').length !== 0) {
                    var dataDailySalesChart = {
                        labels: {!! json_encode($dailyLabels) !!},
                        series: [
                            {!! json_encode($dailySalesSeries) !!}
                        ]
                    };

                    var maxDailySales = Math.max.apply(Math, {!! json_encode($dailySalesSeries) !!});
                    var optionsDailySalesChart = {
                        lineSmooth: Chartist.Interpolation.cardinal({
                            tension: 0.2
                        }),
                        low: 0,
                        high: maxDailySales > 0 ? Math.ceil(maxDailySales * 1.15) : 100,
                        axisY: {
                            labelInterpolationFnc: function(value) {
                                return formatAxisNumber(value);
                            }
                        },
                        chartPadding: {
                            top: 10,
                            right: 10,
                            bottom: 0,
                            left: 10
                        }
                    };

                    var dailySalesChart = new Chartist.Line('#dailySalesChart', dataDailySalesChart, optionsDailySalesChart);
                    if (typeof md !== 'undefined' && md.startAnimationForLineChart) {
                        md.startAnimationForLineChart(dailySalesChart);
                    }
                }

                // 2. Monthly Orders Chart
                if ($('#monthlyOrdersChart').length !== 0) {
                    var dataMonthlyOrdersChart = {
                        labels: {!! json_encode($monthlyLabels) !!},
                        series: [
                            {!! json_encode($monthlyOrdersSeries) !!}
                        ]
                    };

                    var maxMonthlyOrders = Math.max.apply(Math, {!! json_encode($monthlyOrdersSeries) !!});
                    var optionsMonthlyOrdersChart = {
                        axisX: {
                            showGrid: false
                        },
                        axisY: {
                            labelInterpolationFnc: function(value) {
                                return formatAxisNumber(value);
                            }
                        },
                        low: 0,
                        high: maxMonthlyOrders > 0 ? Math.ceil(maxMonthlyOrders * 1.2) : 50,
                        chartPadding: {
                            top: 10,
                            right: 5,
                            bottom: 0,
                            left: 0
                        }
                    };

                    var responsiveOptionsMonthly = [
                        ['screen and (max-width: 640px)', {
                            seriesBarDistance: 4
                        }]
                    ];

                    var monthlyOrdersChart = Chartist.Bar('#monthlyOrdersChart', dataMonthlyOrdersChart, optionsMonthlyOrdersChart, responsiveOptionsMonthly);
                    if (typeof md !== 'undefined' && md.startAnimationForBarChart) {
                        md.startAnimationForBarChart(monthlyOrdersChart);
                    }
                }

                // 3. Orders by Status Chart
                if ($('#orderStatusChart').length !== 0) {
                    var dataOrderStatusChart = {
                        labels: {!! json_encode($statusChartLabels) !!},
                        series: [
                            {!! json_encode($statusChartSeries) !!}
                        ]
                    };

                    var maxStatusOrders = Math.max.apply(Math, {!! json_encode($statusChartSeries) !!});
                    var optionsOrderStatusChart = {
                        axisX: {
                            showGrid: false
                        },
                        axisY: {
                            labelInterpolationFnc: function(value) {
                                return formatAxisNumber(value);
                            }
                        },
                        low: 0,
                        high: maxStatusOrders > 0 ? Math.ceil(maxStatusOrders * 1.15) : 50,
                        chartPadding: {
                            top: 10,
                            right: 5,
                            bottom: 0,
                            left: 0
                        }
                    };

                    var responsiveOptionsStatus = [
                        ['screen and (max-width: 640px)', {
                            seriesBarDistance: 4
                        }]
                    ];

                    var orderStatusChart = Chartist.Bar('#orderStatusChart', dataOrderStatusChart, optionsOrderStatusChart, responsiveOptionsStatus);
                    if (typeof md !== 'undefined' && md.startAnimationForBarChart) {
                        md.startAnimationForBarChart(orderStatusChart);
                    }
                }
            }

            // Override md.initDashboardPageCharts so window resize preserves real charts
            if (typeof md !== 'undefined') {
                md.initDashboardPageCharts = function() {
                    initRealDashboardCharts();
                };
            }

            initRealDashboardCharts();
        });
    </script>
@endpush
