@extends('layouts.admin.admin', ['activeSection' => 'Offers', 'activePage' => '', 'titlePage' =>
__('admin/offersPages.Edit Coupon')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary">
                        <a href="{{ route('admin.dashboard') }}">{{ __('admin/offersPages.Dashboard') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item hover:text-primary">
                        <a href="{{ route('admin.coupons.index') }}">{{ __('admin/offersPages.All Coupons') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('admin/offersPages.Edit Coupon') }}
                    </li>
                </ol>
            </nav>

            <section class="row">
                <div class="col-md-12">

                    {{-- Card --}}
                    <div class="card">

                        {{-- Card Head --}}
                        <div class="card-header card-header-primary">
                            <div class="row">
                                <div class="col-12 ltr:text-left rtl:text-right font-bold self-center text-gray-100">
                                    <p class="">
                                        {{ __("admin/offersPages.Through this form you can edit coupon's data") }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">

                            {{-- Form Start --}}
                            @livewire('admin.coupons.coupon-form', ['coupon_id' => $coupon])

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
