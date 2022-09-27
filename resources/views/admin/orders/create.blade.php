@extends('layouts.admin.admin', [
    'activeSection' => 'Orders',
    'activePage' => 'Add Order',
    'titlePage' => __('admin/ordersPages.Create New Order'),
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
                    <li class="breadcrumb-item active" aria-current="page">{{ __('admin/ordersPages.Create New Order') }}
                    </li>
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
                                    <p class=""> {{ __('admin/ordersPages.Here you can create new order') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body flex flex-col justify-center items-center gap-3">
                            {{-- User Part :: Start --}}
                            <div class="w-full">
                                @livewire('admin.orders.new-order-user-part', key('user-part'))
                            </div>
                            {{-- User Part :: End --}}

                            {{-- Products Part :: Start --}}
                            <div class="w-full">
                                @livewire('admin.orders.new-order-products-part', key('products-part'))
                            </div>
                            {{-- Products Part :: End --}}

                            {{-- Payment Part :: Start --}}
                            <div class="w-full">
                                @livewire('admin.orders.new-order-payment-part', key('payment-part'))
                            </div>
                            {{-- Payment Part :: End --}}

                            {{-- Summary Part :: Start --}}
                            <div>
                                Order Summary Part
                            </div>
                            {{-- Summary Part :: End --}}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('js')
    <script>
        var searchInputs = document.getElementsByClassName('searchInput');

        for (let i = 0; i < searchInputs.length; i++) {
            const element = searchInputs[i];
            element.addEventListener('blur', function(event) {
                setTimeout(() => {
                    window.livewire.emitTo(`admin.orders.${element.dataset.name}`, 'clearSearch');
                }, 200);
            })
        }
    </script>
@endpush
