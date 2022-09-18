@extends('layouts.front.user_control_layout', ['titlePage' => __('front/homePage.Track My Order'), 'page' => 'orders'])

@section('breadcrumb')
    <li class="breadcrumb-item hover:text-primary">
        <a href="{{ route('front.homepage') }}">
            {{ __('front/homePage.Homepage') }}
        </a>
    </li>
    <li class="breadcrumb-item hover:text-primary">
        <a href="{{ route('front.orders.index') }}">
            {{ __('front/homePage.My Orders') }}
        </a>
    </li>
    <li class="breadcrumb-item text-gray-700 font-bold" aria-current="page">
        {{ __('front/homePage.Track My Order') }}
    </li>
@endsection

@section('sub-content')
    <div class="container col-span-12">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 flex flex-col gap-5 self-start">

                {{-- ############## Track Order :: Start ############## --}}
                <div class="bg-white rounded-xl overflow-hidden">
                    {{-- ############## Title :: Start ############## --}}
                    <div class="flex justify-between items-center">
                        <h3 class="h5 text-center font-bold p-4 m-0">
                            {{ __('front/homePage.Track My Order') }}
                        </h3>
                    </div>
                    {{-- ############## Title :: End ############## --}}

                    <hr>

                    <x-admin.orders.order-track-view :statuses="$statuses"/>

                </div>
                {{-- ############## Track Order :: End ############## --}}
            </div>
        </div>
    </div>
@endsection

{{-- Extra Scripts --}}
