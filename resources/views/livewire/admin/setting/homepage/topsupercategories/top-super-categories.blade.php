<div>
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <div class="grid grid-cols-12 gap-3">
        {{-- No 1 : Start --}}
        <div
            class="col-span-12 md:col-span-6 rounded-xl p-3 bg-red-100 grid grid-cols-12 items-center justify-center gap-3">
            <label class="col-span-12 md:col-span-3 text-black font-bold m-0 text-center"
                for="country">{{ __('admin/sitePages.Number 1') }}</label>
            <select wire:model.live="selectedSupercategories.0" wire:key="selectedSupercategories.0"
                class="col-span-12 md:col-span-9 rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('selectedSupercategories.0') border-red-900 border-2 @enderror">
                <option value="0">
                    {{ __('admin/sitePages.Choose a supercategory') }}
                </option>
                @forelse ($supercategories as $supercategory)
                    <option value="{{ $supercategory->id }}">
                        {{ $supercategory->name }}
                    </option>
                @empty
                    <option value="0">
                        {{ __('admin/sitePages.No supercategories in Database') }}
                    </option>
                @endforelse
            </select>

            @error('selectedSupercategories.0')
                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}</div>
            @enderror
        </div>
        {{-- No 1 : End --}}

        {{-- No 2 : Start --}}
        <div
            class="col-span-12 md:col-span-6 rounded-xl p-3 bg-gray-100 grid grid-cols-12 items-center justify-center gap-3">
            <label class="col-span-12 md:col-span-3 text-black font-bold m-0 text-center"
                for="country">{{ __('admin/sitePages.Number 2') }}</label>
            <select wire:model.live="selectedSupercategories.1" wire:key="selectedSupercategories.1"
                class="col-span-12 md:col-span-9 rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('selectedSupercategories.1') border-red-900 border-2 @enderror">
                <option value="0">
                    {{ __('admin/sitePages.Choose a supercategory') }}
                </option>
                @forelse ($supercategories as $supercategory)
                    <option value="{{ $supercategory->id }}">
                        {{ $supercategory->name }}
                    </option>
                @empty
                    <option value="0">
                        {{ __('admin/sitePages.No supercategories in Database') }}
                    </option>
                @endforelse
            </select>

            @error('selectedSupercategories.1')
                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}</div>
            @enderror
        </div>
        {{-- No 2 : End --}}

        {{-- No 3 : Start --}}
        <div
            class="col-span-12 md:col-span-6 rounded-xl p-3 bg-red-100 grid grid-cols-12 items-center justify-center gap-3">
            <label class="col-span-12 md:col-span-3 text-black font-bold m-0 text-center"
                for="country">{{ __('admin/sitePages.Number 3') }}</label>
            <select wire:model.live="selectedSupercategories.2" wire:key="selectedSupercategories.2"
                class="col-span-12 md:col-span-9 rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('selectedSupercategories.2') border-red-900 border-2 @enderror">
                <option value="0">
                    {{ __('admin/sitePages.Choose a supercategory') }}
                </option>
                @forelse ($supercategories as $supercategory)
                    <option value="{{ $supercategory->id }}">
                        {{ $supercategory->name }}
                    </option>
                @empty
                    <option value="0">
                        {{ __('admin/sitePages.No supercategories in Database') }}
                    </option>
                @endforelse
            </select>

            @error('selectedSupercategories.2')
                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}</div>
            @enderror
        </div>
        {{-- No 3 : End --}}

        {{-- No 4 : Start --}}
        <div
            class="col-span-12 md:col-span-6 rounded-xl p-3 bg-gray-100 grid grid-cols-12 items-center justify-center gap-3">
            <label class="col-span-12 md:col-span-3 text-black font-bold m-0 text-center"
                for="country">{{ __('admin/sitePages.Number 4') }}</label>
            <select wire:model.live="selectedSupercategories.3" wire:key="selectedSupercategories.3"
                class="col-span-12 md:col-span-9 rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('selectedSupercategories.3') border-red-900 border-2 @enderror">
                <option value="0">
                    {{ __('admin/sitePages.Choose a supercategory') }}
                </option>
                @forelse ($supercategories as $supercategory)
                    <option value="{{ $supercategory->id }}">
                        {{ $supercategory->name }}
                    </option>
                @empty
                    <option value="0">
                        {{ __('admin/sitePages.No supercategories in Database') }}
                    </option>
                @endforelse
            </select>

            @error('selectedSupercategories.3')
                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}</div>
            @enderror
        </div>
        {{-- No 4 : End --}}

        {{-- No 5 : Start --}}
        <div
            class="col-span-12 md:col-span-6 rounded-xl p-3 bg-red-100 grid grid-cols-12 items-center justify-center gap-3">
            <label class="col-span-12 md:col-span-3 text-black font-bold m-0 text-center"
                for="country">{{ __('admin/sitePages.Number 5') }}</label>
            <select wire:model.live="selectedSupercategories.4" wire:key="selectedSupercategories.4"
                class="col-span-12 md:col-span-9 rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('selectedSupercategories.4') border-red-900 border-2 @enderror">
                <option value="0">
                    {{ __('admin/sitePages.Choose a supercategory') }}
                </option>
                @forelse ($supercategories as $supercategory)
                    <option value="{{ $supercategory->id }}">
                        {{ $supercategory->name }}
                    </option>
                @empty
                    <option value="0">
                        {{ __('admin/sitePages.No supercategories in Database') }}
                    </option>
                @endforelse
            </select>

            @error('selectedSupercategories.4')
                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}</div>
            @enderror
        </div>
        {{-- No 5 : End --}}

        {{-- No 6 : Start --}}
        <div
            class="col-span-12 md:col-span-6 rounded-xl p-3 bg-gray-100 grid grid-cols-12 items-center justify-center gap-3">
            <label class="col-span-12 md:col-span-3 text-black font-bold m-0 text-center"
                for="country">{{ __('admin/sitePages.Number 6') }}</label>
            <select wire:model.live="selectedSupercategories.5" wire:key="selectedSupercategories.5"
                class="col-span-12 md:col-span-9 rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('selectedSupercategories.5') border-red-900 border-2 @enderror">
                <option value="0">
                    {{ __('admin/sitePages.Choose a supercategory') }}
                </option>
                @forelse ($supercategories as $supercategory)
                    <option value="{{ $supercategory->id }}">
                        {{ $supercategory->name }}
                    </option>
                @empty
                    <option value="0">
                        {{ __('admin/sitePages.No supercategories in Database') }}
                    </option>
                @endforelse
            </select>

            @error('selectedSupercategories.5')
                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}</div>
            @enderror
        </div>
        {{-- No 6 : End --}}

        {{-- No 7 : Start --}}
        <div
            class="col-span-12 md:col-span-6 rounded-xl p-3 bg-red-100 grid grid-cols-12 items-center justify-center gap-3">
            <label class="col-span-12 md:col-span-3 text-black font-bold m-0 text-center"
                for="country">{{ __('admin/sitePages.Number 7') }}</label>
            <select wire:model.live="selectedSupercategories.6" wire:key="selectedSupercategories.6"
                class="col-span-12 md:col-span-9 rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('selectedSupercategories.6') border-red-900 border-2 @enderror">
                <option value="0">
                    {{ __('admin/sitePages.Choose a supercategory') }}
                </option>
                @forelse ($supercategories as $supercategory)
                    <option value="{{ $supercategory->id }}">
                        {{ $supercategory->name }}
                    </option>
                @empty
                    <option value="0">
                        {{ __('admin/sitePages.No supercategories in Database') }}
                    </option>
                @endforelse
            </select>

            @error('selectedSupercategories.6')
                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}</div>
            @enderror
        </div>
        {{-- No 7 : End --}}

        {{-- No 8 : Start --}}
        <div
            class="col-span-12 md:col-span-6 rounded-xl p-3 bg-gray-100 grid grid-cols-12 items-center justify-center gap-3">
            <label class="col-span-12 md:col-span-3 text-black font-bold m-0 text-center"
                for="country">{{ __('admin/sitePages.Number 4') }}</label>
            <select wire:model.live="selectedSupercategories.7" wire:key="selectedSupercategories.7"
                class="col-span-12 md:col-span-9 rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('selectedSupercategories.7') border-red-900 border-2 @enderror">
                <option value="0">
                    {{ __('admin/sitePages.Choose a supercategory') }}
                </option>
                @forelse ($supercategories as $supercategory)
                    <option value="{{ $supercategory->id }}">
                        {{ $supercategory->name }}
                    </option>
                @empty
                    <option value="0">
                        {{ __('admin/sitePages.No supercategories in Database') }}
                    </option>
                @endforelse
            </select>

            @error('selectedSupercategories.7')
                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}</div>
            @enderror
        </div>
        {{-- No 8 : End --}}

        {{-- No 9 : Start --}}
        <div
            class="col-span-12 md:col-span-6 rounded-xl p-3 bg-red-100 grid grid-cols-12 items-center justify-center gap-3">
            <label class="col-span-12 md:col-span-3 text-black font-bold m-0 text-center"
                for="country">{{ __('admin/sitePages.Number 9') }}</label>
            <select wire:model.live="selectedSupercategories.8" wire:key="selectedSupercategories.8"
                class="col-span-12 md:col-span-9 rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('selectedSupercategories.8') border-red-900 border-2 @enderror">
                <option value="0">
                    {{ __('admin/sitePages.Choose a supercategory') }}
                </option>
                @forelse ($supercategories as $supercategory)
                    <option value="{{ $supercategory->id }}">
                        {{ $supercategory->name }}
                    </option>
                @empty
                    <option value="0">
                        {{ __('admin/sitePages.No supercategories in Database') }}
                    </option>
                @endforelse
            </select>

            @error('selectedSupercategories.8')
                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}</div>
            @enderror
        </div>
        {{-- No 9 : End --}}

        {{-- No 10 : Start --}}
        <div
            class="col-span-12 md:col-span-6 rounded-xl p-3 bg-gray-100 grid grid-cols-12 items-center justify-center gap-3">
            <label class="col-span-12 md:col-span-3 text-black font-bold m-0 text-center"
                for="country">{{ __('admin/sitePages.Number 10') }}</label>
            <select wire:model.live="selectedSupercategories.9" wire:key="selectedSupercategories.9"
                class="col-span-12 md:col-span-9 rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('selectedSupercategories.9') border-red-900 border-2 @enderror">
                <option value="0">
                    {{ __('admin/sitePages.Choose a supercategory') }}
                </option>
                @forelse ($supercategories as $supercategory)
                    <option value="{{ $supercategory->id }}">
                        {{ $supercategory->name }}
                    </option>
                @empty
                    <option value="0">
                        {{ __('admin/sitePages.No supercategories in Database') }}
                    </option>
                @endforelse
            </select>

            @error('selectedSupercategories.9')
                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}</div>
            @enderror
        </div>
        {{-- No 10 : End --}}
    </div>

    <div class="flex flex-wrap gap-3 justify-around mt-4">
        {{-- Save --}}
        <button wire:click.prevent="save"
            class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Update') }}</button>
        {{-- Back --}}
        <a href="{{ route('admin.setting.homepage') }}"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Back') }}</a>
    </div>

</div>
