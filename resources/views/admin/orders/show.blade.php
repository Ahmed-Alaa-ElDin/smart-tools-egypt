@extends('layouts.admin.admin', [
    'activeSection' => 'Orders',
    'activePage' => 'All Orders',
    'titlePage' => __("admin/ordersPages.Order's Details"),
])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/ordersPages.Dashboard') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.orders.index') }}">{{ __('admin/ordersPages.All Orders') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __("admin/ordersPages.Order's Details") }}</li>
                </ol>
            </nav>

            <section class="row">
                <div class="col-md-12">

                    {{-- Card --}}
                    <div class="card">

                        {{-- Card Head --}}
                        <div class="card-header card-header-primary">
                            <div class="flex flex-wrap justify-between">
                                <div class=" ltr:text-left rtl:text-right font-bold self-center text-gray-100">
                                    <p class=""> {{ __("admin/ordersPages.Here you can see order's details") }}</p>
                                </div>

                                {{-- Create New Order --}}
                                {{-- @if (!$order->trashed())
                                    <div class="ltr:text-right rtl:text-left">
                                        <a href="{{ route('admin.orders.edit', $order->id) }}"
                                            class="btn btn-sm bg-yellow-400 hover:bg-yellow-500 focus:bg-yellow-500 active:bg-yellow-500 font-bold">
                                            <span class="material-icons rtl:ml-1 ltr:mr-1">
                                                edit
                                            </span>
                                            {{ __("admin/ordersPages.Edit Order's Details") }}
                                        </a>
                                    </div>
                                @endif --}}
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            {{-- Order's Summary :: Start --}}
                            <div class="overflow-auto">
                                <x-admin.orders.order-summary :order="$order" />
                            </div>
                            {{-- Order's Summary :: End --}}

                            <hr class="my-2">

                            {{-- Order's Products :: Start --}}
                            {{-- @dd($order->transactions) --}}
                            <x-admin.orders.order-products-list :items="$order->items" />
                            {{-- Order's Products :: End --}}

                            <hr class="my-2">

                            <div class="grid md:grid-cols-3 gap-3 justify-between items-start">
                                {{-- Order's Statuses :: Start --}}
                                <div class="h-full md:col-span-1 md:border-r md:rtl:border-r-0 md:rtl:border-l">
                                    <x-admin.orders.order-track-view :statuses="$order->statuses" />
                                </div>
                                {{-- Order's Statuses :: End --}}

                                {{-- Order's Transactions :: Start --}}
                                <div class="overflow-auto md:col-span-2">
                                    <x-admin.orders.order-payment-history-view :transactions="$order->transactions" />
                                    {{-- Order's Transactions :: End --}}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
