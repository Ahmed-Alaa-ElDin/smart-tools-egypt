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
                    <li class="breadcrumb-item active" aria-current="page">{{ __('admin/ordersPages.Create New Order') }}</li>
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
                        <div class="card-body overflow-hidden">
                            {{-- User Part :: Start --}}
                            @livewire('admin.orders.new-order-user-part')
                            {{-- User Part :: End --}}

                            <hr class="my-2">

                            {{-- Products Part :: Start --}}
                            <div>
                                Products Part
                            </div>
                            {{-- Products Part :: End --}}

                            <hr class="my-2">

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
                window.livewire.emit('clearSearch');
            })
        }
    </script>
@endpush
