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

                    <div class="p-4">
                        <ol class="relative border-l rtl:border-r rtl:border-l-0 border-gray-200">
                            @forelse ($statuses as $status)
                                <li class="mb-10 ml-4 rtl:mr-4 rtl:ml-0">
                                    <div
                                        class="absolute w-3 h-3 rounded-full mt-1.5 -left-1.5 rtl:-right-1.5 border border-white {{ in_array($status->id, [1, 2, 14, 15, 16])
                                            ? 'bg-yellow-500'
                                            : (in_array($status->id, [3, 45, 12])
                                                ? 'bg-green-500'
                                                : (in_array($status->id, [4, 5, 6])
                                                    ? 'bg-blue-500'
                                                    : (in_array($status->id, [8, 9, 13])
                                                        ? 'bg-red-500'
                                                        : 'bg-blue-500'))) }}">
                                    </div>
                                    <time dir="ltr" class="mb-1 text-sm font-normal leading-none {{ in_array($status->id, [1, 2, 14, 15, 16])
                                        ? 'text-yellow-500'
                                        : (in_array($status->id, [3, 45, 12])
                                            ? 'text-green-500'
                                            : (in_array($status->id, [4, 5, 6])
                                                ? 'text-blue-500'
                                                : (in_array($status->id, [8, 9, 13])
                                                    ? 'text-red-500'
                                                    : 'text-blue-500'))) }}">
                                        {{ $status->pivot->created_at->format('Y-m-d h:i:s a') }}
                                    </time>
                                    <h3
                                        class="text-lg font-semibold {{ in_array($status->id, [1, 2, 14, 15, 16])
                                            ? 'text-yellow-600'
                                            : (in_array($status->id, [3, 45, 12])
                                                ? 'text-green-600'
                                                : (in_array($status->id, [4, 5, 6])
                                                    ? 'text-blue-600'
                                                    : (in_array($status->id, [8, 9, 13])
                                                        ? 'text-red-600'
                                                        : 'text-blue-600'))) }}">
                                        {{ $status->name }}
                                    </h3>
                                    @if ($status->pivot->notes)
                                        <p class="mb-4 text-base font-normal text-gray-500">
                                            {{ $status->pivot->notes }}
                                        </p>
                                    @endif
                                </li>
                            @empty
                                {{-- todo --}}
                                {{ 'No Statuses' }}
                            @endforelse
                        </ol>
                    </div>

                </div>
                {{-- ############## Track Order :: End ############## --}}
            </div>
        </div>
    </div>
@endsection

{{-- Extra Scripts --}}
@push('js')
    <script>
        window.addEventListener('swalNotification', function(e) {
            Swal.fire({
                text: e.detail.text,
                icon: e.detail.icon,
                position: 'top-right',
                showConfirmButton: false,
                toast: true,
                timer: 3000,
                timerProgressBar: true,
            })
        });
    </script>
@endpush
