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
                <div class="col-md-12 static">

                    {{-- Card --}}
                    <div class="card static">

                        {{-- Card Head --}}
                        <div class="card-header card-header-primary">
                            <div class="flex flex-wrap justify-between">
                                <div class=" ltr:text-left rtl:text-right font-bold self-center text-gray-100">
                                    <p class=""> {{ __('admin/ordersPages.Here you can create new order') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body static">

                            {{-- Order Form :: Start --}}
                            @livewire('admin.orders.order-form')
                            {{-- Order Form :: End --}}
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
        var displayModal = document.getElementById('displayOrderSummary');
        const modal = new Modal(displayModal);

        for (let i = 0; i < searchInputs.length; i++) {
            const element = searchInputs[i];
            element.addEventListener('blur', function(event) {
                setTimeout(() => {
                    window.livewire.dispatchTo(`admin.orders.${element.dataset.name}`, 'clearSearch');
                }, 200);
            })
        }

        window.addEventListener('displayOrderSummary',function () {
            modal.show();
        })
    </script>
@endpush
