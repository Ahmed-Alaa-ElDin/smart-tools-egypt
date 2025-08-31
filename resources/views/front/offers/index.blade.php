@extends('layouts.front.site', [
    'titlePage' => __('front/homePage.All Offers'),
    'url' => route('front.offers.index'),
    'title' => __('front/homePage.All Offers'),
    'description' => '',
])

@section('content')
    <div class="container px-4 py-2 ">
        {{-- Breadcrumb :: Start --}}
        <nav aria-label="breadcrumb" role="navigation" class="mb-2">
            <ol class="breadcrumb text-sm">
                <li class="breadcrumb-item hover:text-primary">
                    <a href="{{ route('front.homepage') }}">
                        {{ __('front/homePage.Homepage') }}
                    </a>
                </li>
                <li class="breadcrumb-item text-gray-700 font-bold" aria-current="page">
                    {{ __('front/homePage.All Offers') }}
                </li>
            </ol>
        </nav>
        {{-- Breadcrumb :: End --}}

        {{-- Offers :: Start --}}
        <section class="grid grid-cols-12 justify-center items-start align-top gap-3 bg-white rounded shadow-lg p-4">
            @php
                $now = Carbon\Carbon::now('Africa/Cairo');
            @endphp

            @forelse ($offers as $offer)
                @php
                    $date = Carbon\Carbon::parse($offer->expire_at, 'Africa/Cairo');

                    $diff = $now->diffInSeconds($date, false);
                    $diffDays = floor($diff / (60 * 60 * 24));
                    $diffHours = floor(($diff % (60 * 60 * 24)) / (60 * 60));
                    $diffMinutes = floor(($diff % (60 * 60)) / 60);
                    $diffSeconds = floor($diff % 60);
                @endphp

                {{-- Offer :: Start --}}
                <div
                    class="col-span-12 md:col-span-6 p-2 group shadow border border-light rounded-lg hover:shadow-md hover:scale-105 transition overflow-hidden relative">
                    <a href="{{ route('front.offers.show', $offer->id) }}">
                        @if ($offer->banner)
                            {{-- Image : Start --}}
                            <div class="flex justify-center items-center w-full max-w-full">
                                <img class="rounded-lg"
                                    src="{{ asset('storage/images/banners/original/' . $offer->banner) }}"
                                    alt="{{ $offer->title }}">
                            </div>
                            {{-- Image : End --}}
                        @else
                            {{-- Image : Start --}}
                            <div class="w-100 flex justify-center items-center">
                                <img class="rounded-lg"
                                    src="{{ asset('storage/images/banners/original/offer_placeholder.jpg') }}"
                                    alt="{{ $offer->title }}">
                            </div>
                            {{-- Image : End --}}
                        @endif
                    </a>

                    <div class="flex flex-col gap-2 my-2 items-center justify-center">
                        {{-- Offer Name :: Start --}}
                        <a
                            href="{{ route('front.offers.show', $offer->id) }}"class="text-center font-bold select-none text-xl max-w-max">
                            {{ $offer->title }}
                        </a>
                        {{-- Offer Name :: End --}}

                        {{-- Remaining Time :: Start --}}
                        @if ($diff > 0)
                            <div class="timer flex items-center justify-center content-end gap-2 mt-2 w-full md:w-auto"
                                data-date="{{ $offer->expire_at }}">
                                {{-- Day : Start --}}
                                <div class="countdown-item bg-primary flex justify-center items-center p-1 rounded shadow ">
                                    <span
                                        class="days inline-block text-black bg-white px-1 text-xs md:text-sm rounded">{{ $diffDays }}</span>
                                    <span
                                        class="inline-block text-white text-center text-xs font-bold px-1">{{ trans_choice('front/homePage.Day', $diffDays) }}</span>
                                </div>
                                {{-- Day : End --}}

                                <span class="countdown-separator">:</span>
                                {{-- Hour : Start --}}
                                <div class="countdown-item bg-primary flex justify-center items-center p-1 rounded shadow ">
                                    <span
                                        class="hours inline-block text-black bg-white px-1 text-xs md:text-sm rounded">{{ $diffHours }}</span>
                                    <span
                                        class="inline-block text-white text-center text-xs font-bold px-1">{{ trans_choice('front/homePage.Hour', $diffHours) }}</span>
                                </div>
                                {{-- Hour : End --}}

                                <span class="countdown-separator">:</span>
                                {{-- Minute : Start --}}
                                <div class="countdown-item bg-primary flex justify-center items-center p-1 rounded shadow ">
                                    <span
                                        class="minutes inline-block text-black bg-white px-1 text-xs md:text-sm rounded">{{ $diffMinutes }}</span>
                                    <span
                                        class="inline-block text-white text-center text-xs font-bold px-1">{{ trans_choice('front/homePage.Minute', $diffMinutes) }}</span>
                                </div>
                                {{-- Minute : End --}}

                                <span class="countdown-separator">:</span>
                                {{-- Second : Start --}}
                                <div class="countdown-item bg-primary flex justify-center items-center p-1 rounded shadow ">
                                    <span
                                        class="seconds inline-block text-black bg-white px-1 text-xs md:text-sm rounded">{{ $diffSeconds }}</span>
                                    <span
                                        class="inline-block text-white text-center text-xs font-bold px-1">{{ trans_choice('front/homePage.Second', $diffSeconds) }}</span>
                                </div>
                                {{-- Second : End --}}
                            </div>
                        @else
                            <div class="expired bg-primary flex justify-center items-center mb-2 rounded shadow mx-auto">
                                <span
                                    class="inline-block text-white text-center text-xs font-bold py-2 px-3">{{ __('front/homePage.Expired') }}</span>
                            </div>
                        @endif
                        {{-- Remaining Time :: End --}}

                    </div>
                </div>
                {{-- Offer :: End --}}

                {{-- Pagination :: Start --}}
                @if ($loop->last)
                    <div class="col-span-12">
                        {{ $offers->links() }}
                    </div>
                @endif
                {{-- Pagination :: End --}}
            @empty
                <div class="col-span-12 text-center font-bold p-3">
                    {{ __('front/homePage.No Offers have been Found') }}
                </div>
            @endforelse
        </section>
        {{-- Offers :: Start --}}
    </div>
@endsection

{{-- Extra Scripts --}}
@push('js')
    <script>
        $(document).ready(function() {
            // ####### Timer :: Start #######
            $('.timer').each(function() {
                var countDownDate = new Date($(this).data('date')).getTime();

                var x = setInterval(() => {
                    var now = new Date().getTime();

                    var distance = countDownDate - now;

                    // Time calculations for days, hours, minutes and seconds
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    $(this).find('.days').text(days);
                    $(this).find('.hours').text(hours);
                    $(this).find('.minutes').text(minutes);
                    $(this).find('.seconds').text(seconds);

                    // If the count down is finished, write some text
                    if (distance < 0) {
                        clearInterval(x);
                        $('.timer').addClass('hidden');
                        $('.expired').removeClass('hidden');
                    }
                }, 1000);
            });
            // ####### Timer :: End #######

        });
    </script>
@endpush
