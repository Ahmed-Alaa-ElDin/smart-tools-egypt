<div class="w-full rounded-2xl shadow-sm border border-gray-200"
    style="background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);">
    <x-admin.waiting />

    <div class="px-4 py-3 border-b border-gray-100 flex items-center gap-2">
        <span class="material-icons text-lg" style="color: #475569;">person_search</span>
        <span class="text-sm font-bold text-gray-800 m-0">
            {{ __('admin/ordersPages.Customer Choosing') }}
        </span>
    </div>
    <div class="p-4">
        <div class="flex flex-wrap-reverse justify-around items-center gap-3">
            <div class="relative w-full md:w-[70%]">

                {{-- Search Customer Input :: Start --}}
                <div class="flex rounded-xl shadow-sm overflow-hidden">
                    <span class="inline-flex items-center px-3 border border-r-0 text-center text-white text-sm"
                        style="background: linear-gradient(135deg, #475569, #334155); border-color: #475569;">
                        <span class="material-icons text-base">
                            search
                        </span>
                    </span>
                    <input type="text" wire:model.live.debounce.500ms='search'
                        wire:keydown.Escape="$set('search','')" wire:blur.debounce.200ms="$set('search','')"
                        data-name="new-order-user-part"
                        class="searchInput focus:ring-2 focus:ring-slate-200 focus:border-slate-300 flex-1 block w-full rounded-none ltr:rounded-r-xl rtl:rounded-l-xl sm:text-sm border-gray-200 transition-all duration-200"
                        placeholder="{{ __('admin/ordersPages.Search ...') }}">
                </div>
                {{-- Search Customer Input :: End --}}

                @if ($search != null)
                    <div
                        class="absolute top-full left-0 w-full z-[100] bg-white border border-gray-200 max-h-80 overflow-y-auto rounded-b-2xl p-2 shadow-2xl scrollbar scrollbar-thin scrollbar-thumb-slate-200">
                        {{-- Loading :: Start --}}
                        <div wire:loading.delay wire:target="search" class="w-full">
                            <div class="flex flex-col gap-2 justify-center items-center p-8 text-slate-400">
                                <span class="animate-spin text-slate-600">
                                    <span class="material-icons text-4xl">sync</span>
                                </span>
                                <span class="text-xs font-black uppercase tracking-widest">{{ __('admin/ordersPages.Searching ...') }}</span>
                            </div>
                        </div>

                        {{-- Customers List :: Start --}}
                        <div wire:loading.remove wire:target="search" class="space-y-1">
                            @forelse ($customers as $customer)
                                <div class="group flex items-center gap-4 cursor-pointer rounded-xl transition-all duration-300 hover:bg-slate-50 p-3 border border-transparent hover:border-slate-100"
                                    wire:click="$set('customerId',{{ $customer->id }})"
                                    wire:key="customer-result-{{ $customer->id }}">
                                    
                                    {{-- Avatar --}}
                                    <div class="w-10 h-10 flex-shrink-0 bg-slate-100 rounded-full overflow-hidden flex items-center justify-center text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600 transition-colors">
                                        @if ($customer->profile_photo_path)
                                            <img src="{{ asset('storage/images/profiles/cropped100/' . $customer->profile_photo_path) }}"
                                                alt="{{ $customer->f_name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="material-icons text-xl">person</span>
                                        @endif
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-grow min-w-0">
                                        <h5 class="text-sm font-black text-slate-800 truncate group-hover:text-slate-900 transition-colors">
                                            {{ $customer->f_name . ' ' . $customer->l_name }}
                                        </h5>
                                        @if ($customer->phones->where('default', 1)->count() > 0)
                                            <div class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 mt-0.5">
                                                <span class="material-icons text-[12px]">phone</span>
                                                {{ $customer->phones->where('default', 1)->first()->phone }}
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Action Hint --}}
                                    <span class="material-icons text-slate-200 group-hover:text-slate-400 transition-colors text-lg">chevron_right</span>
                                </div>
                            @empty
                                <div class="py-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                    <span class="material-icons text-slate-200 text-3xl mb-1">person_off</span>
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ __('admin/ordersPages.No Customers Found') }}</p>
                                </div>
                            @endforelse
                        </div>
                        {{-- Customers List :: End --}}
                    </div>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if ($customerId)
                    {{-- Change Customer --}}
                    <button wire:click="clearCustomer"
                        class="inline-flex items-center gap-2 text-white font-black text-[10px] uppercase tracking-widest rounded-xl px-4 py-2 shadow-lg shadow-rose-500/20 transition-all duration-200 hover:scale-105 active:scale-95"
                        style="background: linear-gradient(135deg, #f43f5e, #e11d48);">
                        <span class="material-icons text-sm">swap_horiz</span>
                        {{ __('admin/ordersPages.Choose another customer') }}
                    </button>
                    {{-- Edit Customer --}}
                    <a href="{{ route('admin.customers.edit', $selectedCustomer->id) }}" target="_blank"
                        class="inline-flex items-center gap-2 text-white font-black text-[10px] uppercase tracking-widest rounded-xl px-4 py-2 shadow-lg shadow-amber-500/20 transition-all duration-200 hover:scale-105 active:scale-95"
                        style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <span class="material-icons text-sm">edit</span>
                        {{ __('admin/ordersPages.Edit Customer') }}
                    </a>
                @endif

                {{-- Create New Customer :: Start --}}
                <a href="{{ route('admin.customers.create') }}" target="_blank"
                    class="inline-flex items-center gap-2 text-white font-black text-[10px] uppercase tracking-widest rounded-xl px-4 py-2 shadow-lg shadow-emerald-500/20 transition-all duration-200 hover:scale-105 active:scale-95"
                    style="background: linear-gradient(135deg, #10b981, #059669);">
                    <span class="material-icons text-sm">person_add</span>
                    {{ __('admin/ordersPages.Add Customer') }}
                </a>
            </div>
            {{-- Create New Customer :: End --}}
        </div>

        {{-- Customer Selected --}}
        @if ($customerId)
            <div class="mt-8">
                {{-- Customer Profile Header --}}
                <div class="relative bg-slate-50 rounded-2xl border border-slate-100 p-6 overflow-hidden">
                    {{-- Decorative Background --}}
                    <div class="absolute top-0 right-0 w-32 h-32 bg-slate-100 rounded-full -mr-16 -mt-16 opacity-50"></div>
                    
                    <div class="relative flex flex-col md:flex-row items-center gap-8">
                        {{-- Avatar --}}
                        <div class="w-20 h-20 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex items-center justify-center text-slate-300">
                            @if ($selectedCustomer->profile_photo_path)
                                <img src="{{ asset('storage/images/profiles/cropped100/' . $selectedCustomer->profile_photo_path) }}"
                                    alt="{{ $selectedCustomer->f_name }}" class="w-full h-full object-cover">
                            @else
                                <span class="material-icons text-5xl">account_circle</span>
                            @endif
                        </div>

                        {{-- Main Info --}}
                        <div class="flex-grow text-center md:text-left rtl:md:text-right">
                            <h3 class="text-2xl font-black text-slate-800 leading-tight">
                                {{ $selectedCustomer->f_name . ' ' . $selectedCustomer->l_name }}
                            </h3>
                            <div class="flex flex-wrap justify-center md:justify-start items-center gap-4 mt-2">
                                <div class="flex items-center gap-1.5 text-sm font-bold text-slate-400">
                                    <span class="material-icons text-base">email</span>
                                    {{ $selectedCustomer->email }}
                                </div>
                                <div class="px-2 py-0.5 rounded-lg bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest">
                                    {{ __('admin/ordersPages.Customer ID') }}: #{{ $selectedCustomer->id }}
                                </div>
                            </div>
                        </div>

                        {{-- Stats --}}
                        <div class="flex items-center gap-3">
                            {{-- Balance --}}
                            <div class="flex flex-col items-center p-3 rounded-2xl bg-white border border-slate-100 shadow-sm min-w-[100px]">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ __('admin/ordersPages.Balance') }}</span>
                                <div class="text-lg font-black text-emerald-600" dir="ltr">
                                    {{ number_format($selectedCustomer->balance, 2) }}
                                    <small class="text-[10px] opacity-70">EGP</small>
                                </div>
                            </div>
                            {{-- Points --}}
                            <div class="flex flex-col items-center p-3 rounded-2xl bg-white border border-slate-100 shadow-sm min-w-[100px]">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ __('admin/ordersPages.Points') }}</span>
                                <div class="text-lg font-black text-amber-600" dir="ltr">
                                    {{ number_format($selectedCustomer->validPoints, 0) }}
                                    <small class="text-[10px] opacity-70">PTS</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Addresses & Phones Grid --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                    {{-- Addresses Section --}}
                    <div class="lg:col-span-2 space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="material-icons text-sm">location_on</span>
                                {{ __('admin/ordersPages.Shipping Addresses') }}
                            </h4>
                            @if (!$addAddress)
                                <button wire:click="$set('addAddress',true)"
                                    class="text-[10px] font-black text-slate-600 hover:text-slate-900 uppercase tracking-widest flex items-center gap-1 transition-colors">
                                    <span class="material-icons text-sm">add_circle</span>
                                    {{ __('admin/ordersPages.New Address') }}
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse ($selectedCustomer->addresses as $address)
                                <div wire:click="selectAddress({{ $address->id }})"
                                    wire:key="address-card-{{ $address->id }}"
                                    class="group relative cursor-pointer rounded-2xl border-2 transition-all duration-300 p-4 {{ $address->default ? 'border-emerald-500 bg-emerald-50/30 shadow-md shadow-emerald-500/5' : 'border-slate-100 bg-white hover:border-slate-200 hover:shadow-md' }}">
                                    
                                    @if ($address->default)
                                        <div class="absolute -top-2.5 ltr:left-4 rtl:right-4 px-2 py-0.5 rounded-full bg-emerald-500 text-white text-[8px] font-black uppercase tracking-widest shadow-sm">
                                            {{ __('admin/ordersPages.Default') }}
                                        </div>
                                    @else
                                        <button wire:click.stop="removeAddress({{ $address->id }})"
                                            class="absolute top-2 ltr:right-2 rtl:left-2 w-6 h-6 rounded-lg flex items-center justify-center text-slate-300 hover:bg-rose-50 hover:text-rose-600 transition-all opacity-0 group-hover:opacity-100">
                                            <span class="material-icons text-sm">delete</span>
                                        </button>
                                    @endif

                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 {{ $address->default ? 'bg-emerald-100 text-emerald-600' : '' }}">
                                            <span class="material-icons text-base">home</span>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-black text-slate-800 leading-tight">
                                                {{ $address->governorate ? $address->governorate->name : '' }}, {{ $address->city ? $address->city->name : '' }}
                                            </div>
                                            <div class="text-[11px] font-bold text-slate-400 mt-1 line-clamp-2">
                                                {{ $address->details }}
                                            </div>
                                            @if($address->landmarks)
                                                <div class="text-[10px] font-medium text-slate-300 mt-0.5 truncate italic">
                                                    {{ $address->landmarks }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full py-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('admin/ordersPages.No Addresses Saved') }}</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- New Address Form --}}
                        @if ($addAddress)
                            <div class="bg-slate-50 rounded-2xl border border-slate-100 p-6 animate-in fade-in slide-in-from-top-4 duration-300">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">{{ __('admin/ordersPages.Country') }}</label>
                                        <div x-data="{
                                            search: '',
                                            open: false,
                                            selectedId: $wire.entangle('newAddress.country_id').live,
                                            options: {{ json_encode(collect($countries)->map(fn($c) => ['id' => $c['id'], 'name' => $c['name'][session('locale')]])->values()) }},
                                            get filteredOptions() {
                                                if (this.search === '') return this.options;
                                                return this.options.filter(i => i.name.toLowerCase().includes(this.search.toLowerCase()));
                                            },
                                            get selectedName() {
                                                let selected = this.options.find(i => i.id == this.selectedId);
                                                return selected ? selected.name : '{{ __('admin/ordersPages.Select...') }}';
                                            }
                                        }" class="relative w-full" @click.away="open = false">
                                            <div @click="open = !open" 
                                                 class="w-full py-2 px-3 rounded-xl bg-white border border-slate-200 text-sm font-bold cursor-pointer flex justify-between items-center transition-all duration-200 focus:ring-slate-900 focus:border-slate-900">
                                                <span x-text="selectedName" :class="!selectedId ? 'text-slate-400' : 'text-slate-800'"></span>
                                                <span class="material-icons text-sm text-slate-400">expand_more</span>
                                            </div>
                                            
                                            <div x-show="open" style="display: none;"
                                                 class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden">
                                                <div class="p-2 border-b border-slate-100 bg-slate-50">
                                                    <input x-model="search" type="text" class="w-full py-1.5 px-3 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="{{ __('admin/ordersPages.Search ...') }}">
                                                </div>
                                                <div class="max-h-48 overflow-y-auto py-1">
                                                    <template x-for="option in filteredOptions" :key="option.id">
                                                        <div @click="selectedId = option.id; open = false; search = '';" 
                                                             class="px-3 py-2 text-sm font-bold cursor-pointer hover:bg-slate-50 flex items-center justify-between transition-colors">
                                                            <span x-text="option.name" :class="selectedId == option.id ? 'text-slate-900' : 'text-slate-700'"></span>
                                                            <span x-show="selectedId == option.id" class="material-icons text-sm text-slate-900">check</span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">{{ __('admin/ordersPages.Governorate') }}</label>
                                        <div x-data="{
                                            search: '',
                                            open: false,
                                            selectedId: $wire.entangle('newAddress.governorate_id').live,
                                            get options() {
                                                let data = $wire.governorates || [];
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
                                                return selected ? selected.name : '{{ __('admin/ordersPages.Select...') }}';
                                            }
                                        }" class="relative w-full" x-init="$watch('options', () => search = '')" @click.away="open = false">
                                            <div @click="open = !open" 
                                                 class="w-full py-2 px-3 rounded-xl bg-white border border-slate-200 text-sm font-bold cursor-pointer flex justify-between items-center transition-all duration-200 focus:ring-slate-900 focus:border-slate-900">
                                                <span x-text="selectedName" :class="!selectedId ? 'text-slate-400' : 'text-slate-800'"></span>
                                                <span class="material-icons text-sm text-slate-400">expand_more</span>
                                            </div>
                                            
                                            <div x-show="open" style="display: none;"
                                                 class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden">
                                                <div class="p-2 border-b border-slate-100 bg-slate-50">
                                                    <input x-model="search" type="text" class="w-full py-1.5 px-3 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="{{ __('admin/ordersPages.Search ...') }}">
                                                </div>
                                                <div class="max-h-48 overflow-y-auto py-1">
                                                    <template x-for="option in filteredOptions" :key="option.id">
                                                        <div @click="selectedId = option.id; open = false; search = '';" 
                                                             class="px-3 py-2 text-sm font-bold cursor-pointer hover:bg-slate-50 flex items-center justify-between transition-colors">
                                                            <span x-text="option.name" :class="selectedId == option.id ? 'text-slate-900' : 'text-slate-700'"></span>
                                                            <span x-show="selectedId == option.id" class="material-icons text-sm text-slate-900">check</span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">{{ __('admin/ordersPages.City') }}</label>
                                        <div x-data="{
                                            search: '',
                                            open: false,
                                            selectedId: $wire.entangle('newAddress.city_id').live,
                                            get options() {
                                                let data = $wire.cities || [];
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
                                                return selected ? selected.name : '{{ __('admin/ordersPages.Select...') }}';
                                            }
                                        }" class="relative w-full" x-init="$watch('options', () => search = '')" @click.away="open = false">
                                            <div @click="open = !open" 
                                                 class="w-full py-2 px-3 rounded-xl bg-white border border-slate-200 text-sm font-bold cursor-pointer flex justify-between items-center transition-all duration-200 focus:ring-slate-900 focus:border-slate-900">
                                                <span x-text="selectedName" :class="!selectedId ? 'text-slate-400' : 'text-slate-800'"></span>
                                                <span class="material-icons text-sm text-slate-400">expand_more</span>
                                            </div>
                                            
                                            <div x-show="open" style="display: none;"
                                                 class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden">
                                                <div class="p-2 border-b border-slate-100 bg-slate-50">
                                                    <input x-model="search" type="text" class="w-full py-1.5 px-3 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="{{ __('admin/ordersPages.Search ...') }}">
                                                </div>
                                                <div class="max-h-48 overflow-y-auto py-1">
                                                    <template x-for="option in filteredOptions" :key="option.id">
                                                        <div @click="selectedId = option.id; open = false; search = '';" 
                                                             class="px-3 py-2 text-sm font-bold cursor-pointer hover:bg-slate-50 flex items-center justify-between transition-colors">
                                                            <span x-text="option.name" :class="selectedId == option.id ? 'text-slate-900' : 'text-slate-700'"></span>
                                                            <span x-show="selectedId == option.id" class="material-icons text-sm text-slate-900">check</span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sm:col-span-3 space-y-1">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">{{ __('admin/ordersPages.Address Details') }}</label>
                                        <textarea wire:model.live.blur="newAddress.details" rows="2" class="w-full rounded-xl border-slate-200 text-sm font-bold focus:ring-slate-900 focus:border-slate-900" placeholder="{{ __('admin/ordersPages.Street, Building, etc...') }}"></textarea>
                                    </div>
                                    <div class="sm:col-span-3 space-y-1">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">{{ __('admin/ordersPages.Landmarks') }}</label>
                                        <input type="text" wire:model.live.blur="newAddress.landmarks" class="w-full rounded-xl border-slate-200 text-sm font-bold focus:ring-slate-900 focus:border-slate-900" placeholder="{{ __('admin/ordersPages.Near mosque, mall, etc...') }}">
                                    </div>
                                </div>
                                <div class="flex justify-end gap-3 mt-6">
                                    <button wire:click="$set('addAddress',false)" class="px-4 py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">{{ __('admin/ordersPages.Cancel') }}</button>
                                    <button wire:click="saveAddress" class="px-6 py-2 rounded-xl bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-slate-900/20 hover:scale-105 active:scale-95 transition-all">{{ __('admin/ordersPages.Save Address') }}</button>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Phones Section --}}
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="material-icons text-sm">phone</span>
                                {{ __('admin/ordersPages.Phone Numbers') }}
                            </h4>
                            @if (!$addPhone)
                                <button wire:click="$set('addPhone',true)"
                                    class="text-[10px] font-black text-slate-600 hover:text-slate-900 uppercase tracking-widest flex items-center gap-1 transition-colors">
                                    <span class="material-icons text-sm">add_circle</span>
                                    {{ __('admin/ordersPages.New Phone') }}
                                </button>
                            @endif
                        </div>

                        <div class="space-y-3">
                            @forelse ($selectedCustomer->phones as $phone)
                                <div wire:click="selectPhone({{ $phone->id }})"
                                    wire:key="phone-card-{{ $phone->id }}"
                                    class="group relative cursor-pointer rounded-2xl border-2 transition-all duration-300 p-4 {{ $phone->default ? 'border-emerald-500 bg-emerald-50/30 shadow-md shadow-emerald-500/5' : 'border-slate-100 bg-white hover:border-slate-200 hover:shadow-md' }}">
                                    
                                    @if ($phone->default)
                                        <div class="absolute -top-2.5 ltr:left-4 rtl:right-4 px-2 py-0.5 rounded-full bg-emerald-500 text-white text-[8px] font-black uppercase tracking-widest shadow-sm">
                                            {{ __('admin/ordersPages.Primary') }}
                                        </div>
                                    @else
                                        <button wire:click.stop="removePhone({{ $phone->id }})"
                                            class="absolute top-2 ltr:right-2 rtl:left-2 w-6 h-6 rounded-lg flex items-center justify-center text-slate-300 hover:bg-rose-50 hover:text-rose-600 transition-all opacity-0 group-hover:opacity-100">
                                            <span class="material-icons text-sm">delete</span>
                                        </button>
                                    @endif

                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 {{ $phone->default ? 'bg-emerald-100 text-emerald-600' : '' }}">
                                            <span class="material-icons text-base">call</span>
                                        </div>
                                        <div class="text-sm font-black text-slate-800 tracking-wider" dir="ltr">
                                            {{ $phone->phone }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('admin/ordersPages.No Phones Saved') }}</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- New Phone Form --}}
                        @if ($addPhone)
                            <div class="bg-slate-50 rounded-2xl border border-slate-100 p-6 animate-in fade-in slide-in-from-top-4 duration-300">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">{{ __('admin/ordersPages.Phone Number') }}</label>
                                    <input type="text" wire:model.live.blur="newPhone" class="w-full rounded-xl border-slate-200 text-sm font-bold focus:ring-slate-900 focus:border-slate-900" placeholder="01xxxxxxxxx">
                                    @error('newPhone') <span class="text-[10px] font-bold text-rose-500 px-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex justify-end gap-3 mt-4">
                                    <button wire:click="$set('addPhone',false)" class="px-4 py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">{{ __('admin/ordersPages.Cancel') }}</button>
                                    <button wire:click="savePhone" class="px-6 py-2 rounded-xl bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-slate-900/20 hover:scale-105 active:scale-95 transition-all">{{ __('admin/ordersPages.Save Phone') }}</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
