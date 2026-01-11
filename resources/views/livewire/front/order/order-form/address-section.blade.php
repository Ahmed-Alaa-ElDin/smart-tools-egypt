<div>
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <span class="material-icons text-primary">location_on</span>
            {{ __('front/homePage.Shipping Address') }}
        </h3>
        <button wire:click="toggleAddForm"
            class="text-sm text-primary hover:text-primary/80 font-bold flex items-center gap-1">
            @if ($show_add_form)
                {{ __('front/homePage.Cancel') }}
                <span class="material-icons text-xs">close</span>
            @else
                {{ __('front/homePage.Add New Address') }}
                <span class="material-icons text-xs">add</span>
            @endif
        </button>
    </div>

    <div class="p-6">
        @if ($show_add_form)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 animate-fadeIn">
                <div class="space-y-1">
                    <label
                        class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('front/homePage.Country') }}</label>
                    <select wire:model.live="address.country_id"
                        class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary text-sm @error('address.country_id') border-red-500 @enderror">
                        <option value="">{{ __('front/homePage.Select Country') }}</option>
                        @foreach ($countries as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('address.country_id')
                        <span class="text-xs text-red-500 font-bold px-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="space-y-1">
                    <label
                        class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('front/homePage.Governorate') }}</label>
                    <select wire:model.live="address.governorate_id"
                        class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary text-sm @error('address.governorate_id') border-red-500 @enderror">
                        <option value="">{{ __('front/homePage.Select Governorate') }}</option>
                        @foreach ($governorates as $g)
                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                        @endforeach
                    </select>
                    @error('address.governorate_id')
                        <span class="text-xs text-red-500 font-bold px-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="space-y-1">
                    <label
                        class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('front/homePage.City') }}</label>
                    <select wire:model.live="address.city_id"
                        class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary text-sm @error('address.city_id') border-red-500 @enderror">
                        <option value="">{{ __('front/homePage.Select City') }}</option>
                        @foreach ($cities as $ct)
                            <option value="{{ $ct->id }}">{{ $ct->name }}</option>
                        @endforeach
                    </select>
                    @error('address.city_id')
                        <span class="text-xs text-red-500 font-bold px-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="space-y-1 md:col-span-2">
                    <label
                        class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('front/homePage.Address Details') }}</label>
                    <textarea wire:model="address.details" rows="2"
                        class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary text-sm @error('address.details') border-red-500 @enderror"
                        placeholder="{{ __('front/homePage.Street, Building, Apartment...') }}"></textarea>
                    @error('address.details')
                        <span class="text-xs text-red-500 font-bold px-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="space-y-1 md:col-span-2">
                    <label
                        class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('front/homePage.Landmarks') }}
                        ({{ __('front/homePage.Optional') }})</label>
                    <input type="text" wire:model="address.landmarks"
                        class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary text-sm">
                </div>
                <div class="md:col-span-2 pt-2">
                    <button wire:click="saveAddress"
                        class="w-full py-3 bg-gray-800 text-white rounded-xl font-bold hover:bg-black transition-colors">
                        {{ __('front/homePage.Save Address') }}
                    </button>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($addresses as $addr)
                    <div wire:click="selectAddress({{ $addr->id }})"
                        class="relative p-4 rounded-2xl border-2 cursor-pointer transition-all duration-300 group {{ $selected_address_id == $addr->id ? 'border-red-600 bg-red-50/30' : 'border-gray-100 hover:border-gray-200' }}">

                        <div class="flex gap-3 items-center">
                            <div
                                class="mt-1 w-8 h-8 rounded-full flex items-center justify-center {{ $selected_address_id == $addr->id ? 'bg-primary text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-gray-200' }}">
                                <span class="material-icons text-sm">place</span>
                            </div>
                            <div class="flex-grow">
                                <div class="flex gap-2 items-center">
                                    <h4 class="font-bold text-gray-800 text-sm">
                                        {{ $addr->city->name }}, {{ $addr->governorate->name }}
                                    </h4>
                                    @if ($addr->default)
                                        <span class="text-[10px] text-success font-bold uppercase">
                                            {{ __('front/homePage.Default') }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed">
                                    {{ $addr->details }}
                                </p>
                                @if ($addr->landmarks)
                                    <p class="text-[10px] text-gray-400 mt-1 italic">
                                        {{ __('front/homePage.Landmark') }}: {{ $addr->landmarks }}
                                    </p>
                                @endif
                            </div>
                            @if ($selected_address_id == $addr->id)
                                <div class="animate-bounce">
                                    <span class="material-icons text-success text-sm font-bold">check_circle</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div
                        class="md:col-span-2 py-8 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-100">
                        <span class="material-icons text-4xl text-gray-200 mb-2">add_location_alt</span>
                        <p class="text-gray-500 text-sm font-medium">
                            {{ __('front/homePage.No addresses found. Please add one.') }}</p>
                    </div>
                @endforelse
            </div>
        @endif
    </div>
</div>
