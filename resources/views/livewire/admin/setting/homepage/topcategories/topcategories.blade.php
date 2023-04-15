<div>
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <div class="grid grid-cols-12 gap-3">

        @foreach ($items as $item)
            <div
                class="col-span-12 rounded-xl p-3 @if ($loop->odd) bg-red-100 @else bg-gray-100 @endif  grid grid-cols-12 items-center justify-center gap-3">
                <label class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">
                    {{ __('admin/sitePages.Number ' . $loop->iteration) }}
                </label>
                {{-- SelectBoxes --}}
                <div class="col-span-12 md:col-span-10 grid grid-cols-12 gap-3">
                    {{-- Supercategory --}}
                    <div class="col-span-12 sm:col-span-6 items-center w-full">
                        <select
                            class="rounded w-full cursor-pointer py-1 text-center
                            @if ($loop->odd) border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @else border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @endif  @error('items.' . $loop->index . ' .supercategory_id') border-red-900 border-2 @enderror"
                            wire:model.lazy="items.{{ $loop->index }}.supercategory_id"
                            wire:change="supercategoryUpdated({{ $loop->index }})" required>
                            @if ($item['supercategories'])
                                <option value="0">
                                    {{ __('admin/sitePages.Choose a supercategory') }}
                                </option>
                                @foreach ($item['supercategories'] as $supercategory)
                                    <option value="{{ $supercategory['id'] }}">
                                        {{ $supercategory['name'][session('locale')] }}
                                    </option>
                                @endforeach
                            @else
                                <option value="0">
                                    {{ __('admin/sitePages.No Supercategories in the database') }}
                                </option>
                            @endif
                        </select>

                        @error('items.' . $loop->index . ' .supercategory_id')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Supercategory --}}

                    {{-- Category --}}
                    @if ($item['supercategory_id'])
                        <div class="col-span-12 sm:col-span-6 items-center w-full">
                            <select
                                class="rounded w-full cursor-pointer py-1 text-center @if ($loop->odd) border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @else border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @endif @error('items.' . $loop->index . ' .category_id') border-red-900 border-2 @enderror"
                                wire:model.lazy="items.{{ $loop->index }}.category_id" required>
                                @if ($item['categories'])
                                    <option value="0">
                                        {{ __('admin/sitePages.Choose a category') }}
                                    </option>
                                    @foreach ($item['categories'] as $category)
                                        <option value="{{ $category['id'] }}">
                                            {{ $category['name'][session('locale')] }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="0">
                                        {{ __('admin/sitePages.No Categories in the database') }}
                                    </option>
                                @endif
                            </select>

                            @error('items.' . $loop->index . ' .category_id')
                                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                    {{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                    {{-- Category --}}

                    {{-- Edit Button : Start --}}
                    @if ($item['category_id'])
                        <div class="col-span-12 text-center">
                            <a href="{{ route('admin.categories.edit', ['category' => $item['category_id']]) }}"
                                target="_blank"
                                class="bg-edit hover:bg-editHover text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Edit Category') }}</a>
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
