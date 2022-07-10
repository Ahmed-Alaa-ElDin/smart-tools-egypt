<div>
    <div class="flex flex-col gap-3 shadow-inner p-3 items-center rounded-lg bg-gray-100 mb-3">
        {{-- Title :: Start --}}
        <h4 class="text-lg font-bold text-center w-full text-gray-800">{{ __('front/homePage.Delivery Cost') }}</h4>
        {{-- Title :: End --}}

        @if ($free_shipping)
            {{-- Free Shipping :: Start --}}
            <div class="text-xl font-bold text-center text-success">
                {{ __('front/homePage.Free Shipping') }}
            </div>
            {{-- Free Shipping :: End --}}
        @else
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
                        {{ __('front/homePage.No Delivery') }}
                        "{{ $selected_city->name }}"
                        {{ __('front/homePage.contact us') }}
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
