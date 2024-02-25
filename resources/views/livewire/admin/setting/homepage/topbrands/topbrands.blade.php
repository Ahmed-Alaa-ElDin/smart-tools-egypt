<div>
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <div class="grid grid-cols-12 gap-3">

        @foreach ($items as $item)
            <div
                class="col-span-6 rounded-xl p-3 @if ($loop->odd) bg-red-100 @else bg-gray-100 @endif  grid grid-cols-12 items-center justify-center gap-3">
                <label class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">
                    {{ __('admin/sitePages.Number ' . $loop->iteration) }}
                </label>
                {{-- SelectBoxes --}}
                <div class="col-span-12 md:col-span-10 grid grid-cols-12 gap-3">
                    {{-- Brand --}}
                    <div class="col-span-12 items-center w-full">
                        <select
                            class="rounded w-full cursor-pointer py-1 text-center
                            @if ($loop->odd) border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @else border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @endif  @error('items.' . $loop->index . 'brand_id') border-red-900 border-2 @enderror"
                            wire:model.live.blur="items.{{ $loop->index }}.brand_id" required>
                            @if ($item['brands'])
                                <option value="0">
                                    {{ __('admin/sitePages.Choose a brand') }}
                                </option>
                                @foreach ($item['brands'] as $brand)
                                    <option value="{{ $brand['id'] }}">
                                        {{ $brand['name'] }}
                                    </option>
                                @endforeach
                            @else
                                <option value="0">
                                    {{ __('admin/sitePages.No Brands in the database') }}
                                </option>
                            @endif
                        </select>

                        @error('items.' . $loop->index . 'brand_id')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Brand --}}

                    {{-- Edit Button : Start --}}
                    @if ($item['brand_id'])
                        <div class="col-span-12 text-center">
                            <a href="{{ route('admin.brands.edit', ['brand' => $item['brand_id']]) }}"
                                target="_blank"
                                class="bg-edit hover:bg-editHover text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Edit Brand') }}</a>
                        </div>
                    @endif
                    {{-- Edit Button : End --}}


                </div>
                {{-- SelectBoxes --}}
            </div>
        @endforeach
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
