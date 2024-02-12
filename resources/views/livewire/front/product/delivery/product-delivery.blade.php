<div>
    <div class="flex flex-col gap-3 shadow-inner p-3 items-center rounded-lg bg-gray-100 mb-3">
        {{-- Title :: Start --}}
        {{-- <h4 class="text-lg font-bold text-center w-full text-gray-800">{{ __('front/homePage.Delivery Cost') }}</h4> --}}
        {{-- Title :: End --}}

        @if ($free_shipping)
            {{-- Free Shipping:: Start --}}
            <div class="text-xl font-bold text-center text-success">
                {{ __('front/homePage.Free Shipping') }}
            </div>
            {{-- Free Shipping:: End --}}
        @else
            <div class="flex justify-around items-center gap-3">
                {{-- Country :: Start --}}
                <div class="w-full">
                    <label for="counries" class="text-sm font-bold text-center w-full text-gray-700">
                        {{ __('front/homePage.Country') }} </label>
                    <select id="counries" wire:model='selected_country_id'
                        class="w-full cursor-pointer py-1 text-center rounded-xl shadow border border-gray-400 focus:outline-none active:outline-none focus:border-2 focus:border-gray-600 active:border-2 active:border-gray-600 focus:ring-0 active:ring-0">
                        <option value="0">{{ __('front/homePage.Please Choose the Country') }}</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Country :: End --}}

                {{-- Governorate :: Start --}}
                @if ($governorates->count())
                    <div class="w-full">
                        <label for="governorates" class="text-sm font-bold text-center w-full text-gray-700">
                            {{ __('front/homePage.Governorate') }} </label>
                        <select id="governorates" wire:model='selected_governorate_id'
                            class="w-full cursor-pointer py-1 text-center rounded-xl shadow border border-gray-400 focus:outline-none active:outline-none focus:border-2 focus:border-gray-600 active:border-2 active:border-gray-600 focus:ring-0 active:ring-0">
                            <option value="0">{{ __('front/homePage.Please Choose the Governorate') }}</option>
                            @foreach ($governorates as $governorate)
                                <option value="{{ $governorate->id }}">{{ $governorate->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                {{-- Governorate :: End --}}

                {{-- City :: Start --}}
                @if ($cities->count())
                    <div class="w-full">
                        <label for="cities" class="text-sm font-bold text-center w-full text-gray-700">
                            {{ __('front/homePage.City') }} </label>
                        <select id="cities" wire:model='selected_city_id'
                            class="w-full cursor-pointer py-1 text-center rounded-xl shadow border border-gray-400 focus:outline-none active:outline-none focus:border-2 focus:border-gray-600 active:border-2 active:border-gray-600 focus:ring-0 active:ring-0">
                            <option value="0">{{ __('front/homePage.Please Choose the City') }}</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                {{-- City :: End --}}
            </div>

            {{-- Calculate :: Start --}}
            @if ($selected_city_id)
                <div>
                    <button wire:click="calculate"
                        class="btn bg-gray-700 font-bold py-2 px-4 hover:bg-gray-800">{{ __('front/homePage.Calculate') }}</button>
                </div>
            @endif
            {{-- Calculate :: End --}}

            {{-- Delivery Cost :: Start --}}
            @if (!is_null($delivery_cost))
                @if ($delivery_cost == 0)
                    <div class="text-xl font-bold text-center text-success">
                        {{ __('front/homePage.Free Shipping') }}
                    </div>
                @elseif ($delivery_cost == 'no delivery')
                    <div class="text-sm font-bold text-center text-danger">
                        {!! __('front/homePage.No Delivery', [
                            'city' => $selected_city->name,
                            'icon' =>
                                '<a href="https://wa.me/+2' .
                                config('settings.whatsapp_number') .
                                '" target="_blank" class="inline-flex items-center justify-center gap-1 bg-whatsapp text-white rounded-full px-2 py-1 m-1">
                                                                        <span class="text-sm">' .
                                config('settings.whatsapp_number') .
                                '</span> <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"> <path fill="currentColor" d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" /> <path fill="currentColor" d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" /> </svg> </a>',
                        ]) !!}
                    </div>
                @else
                    <div class="font-bold text-center">
                        {{ trans_choice('front/homePage.Delivery final cost', $delivery_cost, ['city' => $selected_city->name, 'cost' => $delivery_cost]) }}
                    </div>
                @endif
            @endif
            {{-- Delivery Cost :: End --}}
        @endif
    </div>

    {{-- Note :: Start --}}
    <div class="flex flex-col gap-3 p-3 items-center rounded-lg shadow-inner bg-red-100">
        <span class="text-black text-xs font-bold">
            {{ __('front/homePage.Delivery Note') }}
        </span>
    </div>
    {{-- Note :: End --}}
</div>
