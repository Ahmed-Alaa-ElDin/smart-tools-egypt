@extends('layouts.admin.admin', ['activeSection' => 'Orders', 'activePage' => 'All Orders', 'titlePage' => __('admin/ordersPages.Payment History')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary">
                        <a href="{{ route('admin.dashboard') }}">
                            {{ __('admin/ordersPages.Dashboard') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item hover:text-primary">
                        <a href="{{ route('admin.orders.index') }}">
                            {{ __('admin/ordersPages.All Orders') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('admin/ordersPages.Payment History') }}</li>
                </ol>
            </nav>

            <section class="row">
                <div class="col-md-12">

                    {{-- Card --}}
                    <div class="card">

                        {{-- Card Head --}}
                        <div class="card-header card-header-primary">
                            <div class="flex justify-between">
                                <div class=" ltr:text-left rtl:text-right font-bold self-center text-gray-100">
                                    <p class="">
                                        {{ __('admin/ordersPages.Here you can view the payment details for order #', ['order_id' => $order_id]) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            @livewire('admin.orders.payment-history', ['order_id' => $order_id])
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection


