<div class="flex flex-col gap-5">
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <form enctype="multipart/form-data" class="flex flex-col gap-5">

        {{-- Image Section --}}
        <div class="w-full rounded-2xl shadow-sm border border-gray-200" style="background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center gap-2">
                <span class="material-icons text-lg" style="color: #3b82f6;">account_circle</span>
                <h2 class="text-sm font-bold text-gray-800 m-0">{{ __('admin/usersPages.Profile Image') }}</h2>
            </div>
            <div class="p-4 flex flex-col items-center gap-4">
                {{-- Loading Spinner --}}
                <div wire:loading wire:target="photo" class="w-full text-center mb-4">
                    <div class="text-blue-500 inline-flex items-center justify-center gap-2 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
                            preserveAspectRatio="xMidYMid meet" viewBox="0 0 50 50" class="animate-spin inline-block text-xl">
                            <path fill="currentColor"
                                d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                        </svg>
                        <span>{{ __('admin/usersPages.Uploading ...') }}</span>
                    </div>
                </div>

                {{-- preview --}}
                @if ($temp_path || $oldImage)
                    <div class="text-center w-full">
                        <img src="{{ $temp_path ?? asset('storage/images/profiles/original/' . $oldImage) }}"
                            class="rounded-xl w-32 h-32 object-cover m-auto shadow-sm border border-gray-200">
                    </div>
                    <div class="text-center mt-2">
                        <button class="bg-red-50 hover:bg-red-100 text-red-600 font-bold py-1.5 px-4 rounded-xl text-xs transition-colors border border-red-200"
                            wire:click.prevent='removePhoto'>
                            <span class="material-icons text-sm align-middle mr-1">delete</span>
                            {{ __('admin/usersPages.Remove / Replace Profile Image') }}
                        </button>
                    </div>
                @else
                    {{-- Upload New Image --}}
                    <div class="w-full md:w-1/2">
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-300 file:mr-4 file:py-2 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            id="photo" type="file" wire:model="photo" accept="image/*">
                        @error('photo')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>
        </div>

        {{-- Basic Information Section --}}
        <div class="w-full rounded-2xl shadow-sm border border-gray-200" style="background: linear-gradient(180deg, #fef2f2 0%, #fff 100%);">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center gap-2">
                <span class="material-icons text-lg" style="color: #ef4444;">badge</span>
                <h2 class="text-sm font-bold text-gray-800 m-0">{{ __('admin/usersPages.Basic Information') }}</h2>
            </div>
            
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                {{-- First Name --}}
                <div class="flex flex-col gap-3">
                    <label class="text-xs font-bold text-gray-700 m-0">{{ __('admin/usersPages.First Name') }}</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <input class="w-full py-2.5 px-3 rounded-xl text-center text-sm border-gray-200 bg-white focus:outline-0 focus:ring-2 focus:ring-red-200 focus:border-red-300 transition-all duration-200 @error('f_name.ar') border-red-500 @enderror"
                                type="text" wire:model.live.blur="f_name.ar" placeholder="{{ __('admin/usersPages.in Arabic') }}" tabindex="1" required>
                            @error('f_name.ar') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <input class="w-full py-2.5 px-3 rounded-xl text-center text-sm border-gray-200 bg-white focus:outline-0 focus:ring-2 focus:ring-red-200 focus:border-red-300 transition-all duration-200 @error('f_name.en') border-red-500 @enderror"
                                type="text" wire:model.live.blur="f_name.en" placeholder="{{ __('admin/usersPages.in English') }}" tabindex="3">
                            @error('f_name.en') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Last Name --}}
                <div class="flex flex-col gap-3">
                    <label class="text-xs font-bold text-gray-700 m-0">{{ __('admin/usersPages.Last Name') }}</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <input class="w-full py-2.5 px-3 rounded-xl text-center text-sm border-gray-200 bg-white focus:outline-0 focus:ring-2 focus:ring-gray-200 focus:border-gray-300 transition-all duration-200 @error('l_name.ar') border-red-500 @enderror"
                                type="text" wire:model.live.blur="l_name.ar" placeholder="{{ __('admin/usersPages.in Arabic') }}" tabindex="2" required>
                            @error('l_name.ar') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <input class="w-full py-2.5 px-3 rounded-xl text-center text-sm border-gray-200 bg-white focus:outline-0 focus:ring-2 focus:ring-gray-200 focus:border-gray-300 transition-all duration-200 @error('l_name.en') border-red-500 @enderror"
                                type="text" wire:model.live.blur="l_name.en" placeholder="{{ __('admin/usersPages.in English') }}" tabindex="4">
                            @error('l_name.en') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Email --}}
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-700 m-0">{{ __('admin/usersPages.Email') }}</label>
                    <input class="w-full py-2.5 px-3 rounded-xl text-center text-sm border-gray-200 bg-white focus:outline-0 focus:ring-2 focus:ring-red-200 focus:border-red-300 transition-all duration-200 @error('email') border-red-500 @enderror"
                        type="email" wire:model.live.blur="email" placeholder="{{ __('admin/usersPages.Email') }}" dir="ltr" tabindex="5">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Other Info (Gender & Birth Date) --}}
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-700 m-0">{{ __('admin/usersPages.Other Information') }}</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <select class="w-full py-2.5 px-3 rounded-xl text-center text-sm border-gray-200 bg-white focus:outline-0 focus:ring-2 focus:ring-gray-200 focus:border-gray-300 transition-all duration-200 cursor-pointer @error('gender') border-red-500 @enderror"
                                wire:model.live.blur="gender" id="gender" tabindex="7">
                                <option value="0">{{ __('admin/usersPages.Male') }}</option>
                                <option value="1">{{ __('admin/usersPages.Female') }}</option>
                            </select>
                            @error('gender') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <input class="w-full py-2.5 px-3 rounded-xl text-center text-sm border-gray-200 bg-white focus:outline-0 focus:ring-2 focus:ring-gray-200 focus:border-gray-300 transition-all duration-200 cursor-pointer @error('birth_date') border-red-500 @enderror"
                                type="date" wire:model.live.blur="birth_date" id="birth_date" tabindex="9" required>
                            @error('birth_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Phones --}}
                <div class="col-span-1 md:col-span-2 mt-2">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-bold text-gray-700 m-0">{{ __('admin/usersPages.Phones') }}</label>
                        <button wire:click.prevent="addPhone" class="bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold py-1 px-3 rounded-lg text-xs transition-colors border border-rose-200 flex items-center gap-1">
                            <span class="material-icons text-sm">add</span> {{ __('admin/usersPages.Add') }}
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                        @foreach ($phones as $index => $phone)
                            <div class="relative bg-white rounded-xl border border-gray-100 shadow-sm p-3 flex flex-col gap-2">
                                <div class="flex justify-between items-center">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" wire:model.live.blur="defaultPhone" value="{{ $index }}" class="w-4 h-4 text-red-500 focus:ring-red-400 border-gray-300">
                                        <span class="text-xs font-semibold text-gray-600">{{ __('admin/usersPages.Default') }}</span>
                                    </label>
                                    @if (count($phones) > 1)
                                        <button wire:click.prevent='removePhone({{ $index }})' class="text-red-400 hover:text-red-600 p-0.5 rounded transition-colors">
                                            <span class="material-icons text-sm">close</span>
                                        </button>
                                    @endif
                                </div>
                                <div>
                                    <input class="w-full py-2 px-3 rounded-lg text-center text-sm border-gray-200 bg-gray-50 focus:outline-0 focus:ring-2 focus:ring-red-200 focus:border-red-300 transition-all duration-200 @error('phones.'.$index.'.phone') border-red-500 @enderror"
                                        type="text" wire:model.live.blur="phones.{{ $index }}.phone" placeholder="{{ __('admin/usersPages.Phone') }}" dir="ltr" tabindex="6">
                                    @error('phones.'.$index.'.phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('defaultPhone') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Address Section --}}
        <div class="w-full rounded-2xl shadow-sm border border-gray-200" style="background: linear-gradient(180deg, #f0fdf4 0%, #fff 100%);">
            <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="material-icons text-lg" style="color: #22c55e;">location_on</span>
                    <h2 class="text-sm font-bold text-gray-800 m-0">{{ __('admin/usersPages.Address') }}</h2>
                </div>
                <button wire:click.prevent="addAddress" class="bg-green-50 hover:bg-green-100 text-green-600 font-bold py-1 px-3 rounded-lg border border-green-200 shadow-sm text-xs flex items-center gap-1 transition-colors">
                    <span class="material-icons text-sm">add</span> {{ __('admin/usersPages.Add') }}
                </button>
            </div>
            
            <div class="p-4 flex flex-col gap-4">
                @foreach ($addresses as $index => $address)
                    <div class="relative bg-white rounded-xl border border-gray-100 shadow-sm p-4" wire:key="address-{{ $index }}">
                        {{-- Remove Button & Default Radio --}}
                        <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-50">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" wire:model.live.blur="defaultAddress" value="{{ $index }}" class="w-4 h-4 text-green-500 focus:ring-green-400 border-gray-300">
                                <span class="text-sm font-semibold text-gray-700">{{ __('admin/usersPages.Default') }}</span>
                            </label>
                            @if (count($addresses) > 1)
                                <button wire:click.prevent='removeAddress({{ $index }})' class="text-red-500 hover:text-red-700 p-1 rounded-lg hover:bg-red-50 transition-colors">
                                    <span class="material-icons text-sm">close</span>
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
                            {{-- Country --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('admin/usersPages.Country') }}</label>
                                <div x-data="{
                                    search: '',
                                    open: false,
                                    selectedId: $wire.entangle('addresses.{{ $index }}.country_id').live,
                                    options: {{ json_encode(collect($countries)->map(fn($c) => ['id' => $c['id'], 'name' => $c['name'][session('locale')]])->values()) }},
                                    get filteredOptions() {
                                        if (this.search === '') return this.options;
                                        return this.options.filter(i => i.name.toLowerCase().includes(this.search.toLowerCase()));
                                    },
                                    get selectedName() {
                                        let selected = this.options.find(i => i.id == this.selectedId);
                                        return selected ? selected.name : '{{ __('admin/usersPages.Select...') }}';
                                    }
                                }" class="relative w-full" @click.away="open = false">
                                    <div @click="open = !open" 
                                         class="w-full py-2 px-3 rounded-xl text-center text-sm border border-gray-200 bg-white cursor-pointer flex justify-between items-center transition-all duration-200 hover:border-green-300 @error('addresses.'.$index.'.country_id') border-red-500 @enderror">
                                        <span x-text="selectedName" :class="!selectedId ? 'text-gray-400' : 'text-gray-700'"></span>
                                        <span class="material-icons text-sm text-gray-400">expand_more</span>
                                    </div>
                                    
                                    <div x-show="open" style="display: none;"
                                         class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                                        <div class="p-2 border-b border-gray-100 bg-gray-50">
                                            <input x-model="search" type="text" class="w-full py-1.5 px-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-green-400 focus:ring-1 focus:ring-green-400" placeholder="{{ __('admin/usersPages.Search...') }}">
                                        </div>
                                        <div class="max-h-48 overflow-y-auto py-1">
                                            <template x-for="option in filteredOptions" :key="option.id">
                                                <div @click="selectedId = option.id; open = false; search = '';" 
                                                     class="px-3 py-2 text-sm cursor-pointer hover:bg-green-50 flex items-center justify-between transition-colors">
                                                    <span x-text="option.name" :class="selectedId == option.id ? 'font-bold text-green-600' : 'text-gray-700'"></span>
                                                    <span x-show="selectedId == option.id" class="material-icons text-sm text-green-600">check</span>
                                                </div>
                                            </template>
                                            <div x-show="filteredOptions.length === 0" class="px-3 py-2 text-sm text-gray-500 text-center">
                                                {{ __('admin/usersPages.No results found') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('addresses.'.$index.'.country_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Governorate --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('admin/usersPages.Governorate') }}</label>
                                <div x-data="{
                                    search: '',
                                    open: false,
                                    selectedId: $wire.entangle('addresses.{{ $index }}.governorate_id').live,
                                    get options() {
                                        let data = $wire.governorates[{{ $index }}] || [];
                                        return data.map(g => ({
                                            id: g.id,
                                            name: g.name && g.name['{{ session('locale') }}'] ? g.name['{{ session('locale') }}'] : ''
                                        }));
                                    },
                                    get filteredOptions() {
                                        if (this.search === '') return this.options;
                                        return this.options.filter(i => i.name.toLowerCase().includes(this.search.toLowerCase()));
                                    },
                                    get selectedName() {
                                        let selected = this.options.find(i => i.id == this.selectedId);
                                        return selected ? selected.name : '{{ __('admin/usersPages.Select...') }}';
                                    }
                                }" class="relative w-full" x-init="$watch('options', () => search = '')" @click.away="open = false">
                                    <div @click="open = !open" 
                                         class="w-full py-2 px-3 rounded-xl text-center text-sm border border-gray-200 bg-white cursor-pointer flex justify-between items-center transition-all duration-200 hover:border-green-300 @error('addresses.'.$index.'.governorate_id') border-red-500 @enderror">
                                        <span x-text="selectedName" :class="!selectedId ? 'text-gray-400' : 'text-gray-700'"></span>
                                        <span class="material-icons text-sm text-gray-400">expand_more</span>
                                    </div>
                                    
                                    <div x-show="open" style="display: none;"
                                         class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                                        <div class="p-2 border-b border-gray-100 bg-gray-50">
                                            <input x-model="search" type="text" class="w-full py-1.5 px-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-green-400 focus:ring-1 focus:ring-green-400" placeholder="{{ __('admin/usersPages.Search...') }}">
                                        </div>
                                        <div class="max-h-48 overflow-y-auto py-1">
                                            <template x-for="option in filteredOptions" :key="option.id">
                                                <div @click="selectedId = option.id; open = false; search = '';" 
                                                     class="px-3 py-2 text-sm cursor-pointer hover:bg-green-50 flex items-center justify-between transition-colors">
                                                    <span x-text="option.name" :class="selectedId == option.id ? 'font-bold text-green-600' : 'text-gray-700'"></span>
                                                    <span x-show="selectedId == option.id" class="material-icons text-sm text-green-600">check</span>
                                                </div>
                                            </template>
                                            <div x-show="filteredOptions.length === 0" class="px-3 py-2 text-sm text-gray-500 text-center">
                                                {{ __('admin/usersPages.No results found') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('addresses.'.$index.'.governorate_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- City --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('admin/usersPages.City') }}</label>
                                <div x-data="{
                                    search: '',
                                    open: false,
                                    selectedId: $wire.entangle('addresses.{{ $index }}.city_id').live,
                                    get options() {
                                        let data = $wire.cities[{{ $index }}] || [];
                                        return data.map(c => ({
                                            id: c.id,
                                            name: c.name && c.name['{{ session('locale') }}'] ? c.name['{{ session('locale') }}'] : ''
                                        }));
                                    },
                                    get filteredOptions() {
                                        if (this.search === '') return this.options;
                                        return this.options.filter(i => i.name.toLowerCase().includes(this.search.toLowerCase()));
                                    },
                                    get selectedName() {
                                        let selected = this.options.find(i => i.id == this.selectedId);
                                        return selected ? selected.name : '{{ __('admin/usersPages.Select...') }}';
                                    }
                                }" class="relative w-full" x-init="$watch('options', () => search = '')" @click.away="open = false">
                                    <div @click="open = !open" 
                                         class="w-full py-2 px-3 rounded-xl text-center text-sm border border-gray-200 bg-white cursor-pointer flex justify-between items-center transition-all duration-200 hover:border-green-300 @error('addresses.'.$index.'.city_id') border-red-500 @enderror">
                                        <span x-text="selectedName" :class="!selectedId ? 'text-gray-400' : 'text-gray-700'"></span>
                                        <span class="material-icons text-sm text-gray-400">expand_more</span>
                                    </div>
                                    
                                    <div x-show="open" style="display: none;"
                                         class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                                        <div class="p-2 border-b border-gray-100 bg-gray-50">
                                            <input x-model="search" type="text" class="w-full py-1.5 px-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-green-400 focus:ring-1 focus:ring-green-400" placeholder="{{ __('admin/usersPages.Search...') }}">
                                        </div>
                                        <div class="max-h-48 overflow-y-auto py-1">
                                            <template x-for="option in filteredOptions" :key="option.id">
                                                <div @click="selectedId = option.id; open = false; search = '';" 
                                                     class="px-3 py-2 text-sm cursor-pointer hover:bg-green-50 flex items-center justify-between transition-colors">
                                                    <span x-text="option.name" :class="selectedId == option.id ? 'font-bold text-green-600' : 'text-gray-700'"></span>
                                                    <span x-show="selectedId == option.id" class="material-icons text-sm text-green-600">check</span>
                                                </div>
                                            </template>
                                            <div x-show="filteredOptions.length === 0" class="px-3 py-2 text-sm text-gray-500 text-center">
                                                {{ __('admin/usersPages.No results found') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('addresses.'.$index.'.city_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Details --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('admin/usersPages.Address Details') }}</label>
                                <textarea rows="2" wire:model.live.blur="addresses.{{ $index }}.details" dir="rtl"
                                    placeholder="{{ __('admin/usersPages.Please mention the details of the address such as street name, building number, ... etc.') }}"
                                    class="w-full py-2.5 px-3 rounded-xl text-center text-sm border-gray-200 bg-white focus:outline-0 focus:ring-2 focus:ring-green-200 focus:border-green-300 transition-all duration-200 resize-none @error('addresses.'.$index.'.details') border-red-500 @enderror"></textarea>
                                @error('addresses.'.$index.'.details') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Landmarks --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('admin/usersPages.Landmarks') }}</label>
                                <textarea rows="2" wire:model.live.blur="addresses.{{ $index }}.landmarks" dir="rtl"
                                    placeholder="{{ __('admin/usersPages.Please mention any landmarks such as mosque, grocery, ... etc.') }}"
                                    class="w-full py-2.5 px-3 rounded-xl text-center text-sm border-gray-200 bg-white focus:outline-0 focus:ring-2 focus:ring-green-200 focus:border-green-300 transition-all duration-200 resize-none @error('addresses.'.$index.'.landmarks') border-red-500 @enderror"></textarea>
                                @error('addresses.'.$index.'.landmarks') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                @endforeach

                @error('addresses.*')
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-red-600 text-sm font-semibold flex items-center gap-2 mt-2">
                        <span class="material-icons text-lg">error_outline</span>
                        {{ $message }}
                    </div>
                @enderror
                @error('defaultAddress')
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-red-600 text-sm font-semibold flex items-center gap-2 mt-2">
                        <span class="material-icons text-lg">error_outline</span>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        {{-- Buttons Section --}}
        <div class="flex flex-wrap items-center justify-center gap-3 w-full mt-2">
            @if ($customer_id != null)
                <button type="button" wire:click.prevent="update"
                    class="inline-flex items-center gap-2 text-white font-bold rounded-xl px-6 py-2.5 text-sm shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                    style="background: linear-gradient(135deg, #10b981, #059669);">
                    <span class="material-icons text-lg">save</span>
                    {{ __('admin/usersPages.Update') }}
                </button>
            @else
                <button type="button" wire:click.prevent="save"
                    class="inline-flex items-center gap-2 text-white font-bold rounded-xl px-6 py-2.5 text-sm shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                    style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                    <span class="material-icons text-lg">person_add</span>
                    {{ __('admin/usersPages.Save') }}
                </button>
                
                <button type="button" wire:click.prevent="save('true')"
                    class="inline-flex items-center gap-2 text-white font-bold rounded-xl px-6 py-2.5 text-sm shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                    style="background: linear-gradient(135deg, #14b8a6, #0d9488);">
                    <span class="material-icons text-lg">group_add</span>
                    {{ __('admin/usersPages.Save and Add New Customer') }}
                </button>
            @endif
            
            <a href="{{ route('admin.customers.index') }}"
                class="inline-flex items-center gap-2 font-bold rounded-xl px-6 py-2.5 text-sm border border-gray-300 text-gray-700 bg-white transition-all duration-200 hover:bg-gray-50 hover:shadow-sm">
                <span class="material-icons text-lg">arrow_back</span>
                {{ __('admin/usersPages.Back') }}
            </a>
        </div>
        
    </form>
</div>