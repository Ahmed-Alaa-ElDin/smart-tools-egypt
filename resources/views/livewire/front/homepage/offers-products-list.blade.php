<section class="offer-bar mb-3">

    @php
        $date = Carbon\Carbon::parse($section->offer->expire_at, 'Africa/Cairo');
        $now = Carbon\Carbon::now('Africa/Cairo');

        $diff = $now->diffInSeconds($date, false);
        $diffDays = floor($diff / (60 * 60 * 24));
        $diffHours = floor(($diff % (60 * 60 * 24)) / (60 * 60));
        $diffMinutes = floor(($diff % (60 * 60)) / 60);
        $diffSeconds = floor($diff % 60);
    @endphp

    <div class="container">
        <div class="px-2 py-4 md:px-4 md:py-3 bg-white shadow rounded" wire:ignore>

            {{-- Header : Start --}}
            <div class="flex flex-wrap mb-3 gap-2 justify-between items-baseline border-b text-center">
                {{-- Title : Start --}}
                <h3 class="h5 font-bold mb-0 w-full text-center md:w-auto">
                    <span class="border-b-2 border-primary pb-3 inline-block">{{ $section->title }}</span>
                </h3>
                {{-- Title : End --}}

                {{-- Timer : Start --}}
                @if ($flash_sale)
                    @if ($diff > 0)
                        <div class="timer flex items-center justify-center content-end gap-2 mt-2 w-full md:w-auto"
                            data-date="{{ $section->offer->expire_at }}">
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
                    <div class="expired bg-primary flex justify-center items-center p-1 rounded shadow hidden m-auto">
                        <span
                            class="inline-block text-white text-center font-bold px-1">{{ __('front/homePage.Expired') }}</span>
                    </div>
                @endif
                {{-- Timer : End --}}

                {{-- View More Button : Start --}}
                <div class="w-full md:w-auto">
                    <a href="{{ env('APP_URL') . session('locale') . '/offers/' . $section->offer_id }}"
                        class="btn bg-secondary btn-sm shadow-md font-bold mb-3 md:mb-auto m-auto">{{ __('front/homePage.View More') }}</a>
                </div>
                {{-- View More Button : End --}}
            </div>
            {{-- Header : End --}}

            {{-- Slider : Start --}}
            <div class="product_list splide h-full w-full row-span-2 rounded overflow-hidden">
                <div class="splide__track">
                    {{-- List of Products : Start --}}
                    <ul class="splide__list">

                        @foreach ($items as $item)
                            {{-- Product : Start --}}
                            <x-front.product-box-small :item="$item" wire:key="item-{{ rand() }}" />
                            {{-- Product : End --}}
                        @endforeach
                    </ul>
                    {{-- List of Products : End --}}

                </div>
            </div>
            {{-- Slider : End --}}

        </div>
    </div>

</section>
