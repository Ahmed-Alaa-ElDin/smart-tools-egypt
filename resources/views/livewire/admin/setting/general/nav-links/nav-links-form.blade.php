<div>
    <div class="flex flex-col gap-3 items-center justify-center">
        @for ($i = 0; $i < 10; $i++)
            <div
                class="grid grid-cols-12 gap-3 justify-center items-center  rounded-lg shadow p-2 {{ $i % 2 == 0 ? 'bg-red-100' : 'bg-gray-100' }}">
                {{-- Name --}}
                <div class="col-span-12 lg:col-span-6 grid grid-cols-12 gap-x-3 gap-y-2 items-center text-center">
                    <label
                        class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/sitePages.Name') }}</label>
                    {{-- Name Ar --}}
                    <div class="col-span-6 md:col-span-5">
                        <input
                            class="py-1 w-full rounded text-center {{ $i % 2 == 0 ? 'border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300' : 'border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300' }} @error('f_name.ar') border-red-900 border-2 @enderror"
                            type="text" wire:model.lazy="nav_links.{{ $i }}.name.ar"
                            placeholder="{{ __('admin/sitePages.in Arabic') }}" tabindex="{{ $i + 1 }}" required>
                        @error("nav_links.$i.name.ar")
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Name En --}}
                    <div class="col-span-6 md:col-span-5">
                        <input
                            class="py-1 w-full rounded text-center {{ $i % 2 == 0 ? 'border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300' : 'border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300' }} @error('f_name.en') border-red-900 border-2 @enderror"
                            type="text" wire:model.lazy="nav_links.{{ $i }}.name.en"
                            placeholder="{{ __('admin/sitePages.in English') }}" tabindex="{{ $i + 1 }}">
                        @error("nav_links.$i.name.en")
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Link --}}
                <div
                    class="col-span-8 md:col-span-10 lg:col-span-5 grid grid-cols-12 gap-x-3 gap-y-2 items-center text-center">
                    <label
                        class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/sitePages.Link') }}</label>
                    {{-- Link --}}
                    <div class="col-span-12 md:col-span-10">
                        <input
                            class="py-1 w-full rounded text-center {{ $i % 2 == 0 ? 'border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300' : 'border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300' }} @error('f_name.ar') border-red-900 border-2 @enderror"
                            type="text" wire:model.lazy="nav_links.{{ $i }}.url"
                            placeholder="{{ __('admin/sitePages.Enter URL') }}" tabindex="{{ $i + 1 }}"
                            required>
                            @error("nav_links.$i.url")
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                    {{ $message }}</div>
                            @enderror
                    </div>
                </div>

                {{-- Free Shipping Start --}}
                <div
                    class="col-span-4 md:col-span-2 lg:col-span-1 w-full grid grid-cols-3 gap-x-6 gap-y-1 items-center rounded text-center">
                    <label for="active({{ $i }})" wire:click="active({{ $i }})"
                        class="col-span-3 font-bold m-0 text-center font-bold text-xs text-black cursor-pointer select-none">{{ __('admin/sitePages.Active') }}</label>

                    <div class="col-span-3">
                        <div class="col-span-2 md:col-span-1">
                            {!! isset($nav_links[$i]['active']) && $nav_links[$i]['active']
                                ? "<span class='block cursor-pointer material-icons text-success select-none' wire:click='active( $i )'>toggle_on</span>"
                                : "<span class='block cursor-pointer material-icons text-red-600 select-none' wire:click='active( $i )'>toggle_off</span>" !!}

                            @error('nav_links.{{ $i }}.active')
                                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                    {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                {{-- Free Shipping End --}}
            </div>
        @endfor
        {{-- Buttons Section :: Start --}}
        <div class="col-span-12 w-full flex flex-wrap justify-around">
            {{-- Save --}}
            <button type="button" wire:click.prevent="save"
                class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Save') }}</button>
            {{-- Back --}}
            <a href="{{ route('admin.setting.general') }}"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Back') }}</a>
        </div>
        {{-- Buttons Section :: End --}}
    </div>
</div>
