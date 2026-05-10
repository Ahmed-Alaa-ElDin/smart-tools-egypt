@extends('layouts.admin.admin', [
    'activeSection' => 'Orders',
    'activePage' => 'Edit Order',
    'titlePage' => __('admin/ordersPages.Edit Order') . ' #' . $order->id,
])

@section('content')
    <div class="content">
        <div class="container-fluid">

            {{-- Hero Header --}}
            <div class="relative overflow-hidden rounded-2xl mb-6"
                style="background: linear-gradient(135deg, #92400e 0%, #b45309 50%, #d97706 100%);">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full"
                        style="background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);"></div>
                    <div class="absolute -bottom-10 -left-10 w-56 h-56 rounded-full"
                        style="background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);"></div>
                </div>
                <div class="relative px-6 py-5">
                    {{-- Breadcrumb --}}
                    <nav aria-label="breadcrumb" role="navigation" class="mb-3">
                        <ol class="flex items-center gap-2 text-sm m-0 p-0" style="list-style: none;">
                            <li>
                                <a href="{{ route('admin.dashboard') }}"
                                    class="text-amber-100 hover:text-white transition-colors duration-200 flex items-center gap-1">
                                    <span class="material-icons text-sm">dashboard</span>
                                    {{ __('admin/ordersPages.Dashboard') }}
                                </a>
                            </li>
                            <li class="text-amber-300/50">
                                <span class="material-icons text-xs">chevron_right</span>
                            </li>
                            <li>
                                <a href="{{ route('admin.orders.index') }}"
                                    class="text-amber-100 hover:text-white transition-colors duration-200 flex items-center gap-1">
                                    <span class="material-icons text-sm">shopping_cart</span>
                                    {{ __('admin/ordersPages.All Orders') }}
                                </a>
                            </li>
                            <li class="text-amber-300/50">
                                <span class="material-icons text-xs">chevron_right</span>
                            </li>
                            <li class="text-white font-semibold">
                                {{ __('admin/ordersPages.Edit Order') }} #{{ $order->id }}
                            </li>
                        </ol>
                    </nav>

                    <div class="flex flex-wrap justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-white m-0 flex items-center gap-3">
                                <span class="material-icons text-3xl p-2 rounded-xl"
                                    style="background: rgba(255,255,255,0.15);">edit_note</span>
                                {{ __('admin/ordersPages.Edit Order') }} #{{ $order->id }}
                            </h1>
                            <p class="text-amber-100 text-sm mt-1 m-0">
                                {{ __('admin/ordersPages.Modify order items, payment, and delivery details') }}
                            </p>
                        </div>
                        {{-- Order Status Badge --}}
                        <div class="mt-2 md:mt-0">
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-sm font-bold"
                                style="background: rgba(255,255,255,0.2); color: #fff;">
                                <span class="material-icons text-sm">info_outline</span>
                                {{ $order->status?->name ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <section class="row">
                <div class="col-md-12 static">
                    {{-- Card Body --}}
                    <div class="static rounded-2xl bg-white shadow-lg border border-gray-100 p-4 md:p-6">
                        {{-- Edit Order Form :: Start --}}
                        @livewire('admin.orders.edit-order-form', ['order' => $order])
                        {{-- Edit Order Form :: End --}}
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('js')
    <script>
        var searchInputs = document.getElementsByClassName('searchInput');
        var displayModal = document.getElementById('displayOrderSummary');
        const modal = new Modal(displayModal);

        window.addEventListener('displayOrderSummary', function() {
            modal.show();
        })
    </script>
@endpush
