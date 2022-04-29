<div>
    <div class="grid grid-cols-12 gap-3">

        {{-- No 1 : Start --}}
        <div class="col-span-12 rounded-xl p-3 bg-red-100 grid grid-cols-12 items-center justify-center gap-3">
            <label class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center"
                for="country">{{ __('admin/sitePages.Number 1') }}</label>
            {{-- SelectBoxes --}}
            <div class="col-span-12 md:col-span-10 grid grid-cols-12 gap-3">
                {{-- Supercategory --}}
                <div class="col-span-12 sm:col-span-6 md:col-span-4 items-center w-full">
                    <select
                        class="rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('items.0.supercategory_id') border-red-900 border-2 @enderror"
                        wire:model.lazy="items.0.supercategory_id" wire:change="supercategoryUpdated(0)" required>
                        @if ($items[0]['supercategories'])
                            <option value="0">
                                {{ __('admin/sitePages.Choose a supercategory') }}
                            </option>
                            @foreach ($items[0]['supercategories'] as $supercategory)
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

                    @error('items.0.supercategory_id')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
                {{-- Supercategory --}}

                {{-- Category --}}
                @if ($items[0]['supercategory_id'])
                    <div class="col-span-12 sm:col-span-6 md:col-span-4 items-center w-full">
                        <select
                            class="rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('items.0.category_id') border-red-900 border-2 @enderror"
                            wire:model.lazy="items.0.category_id" wire:change="categoryUpdated(0)" required>
                            @if ($items[0]['categories'])
                                <option value="0">
                                    {{ __('admin/sitePages.Choose a category') }}
                                </option>
                                @foreach ($items[0]['categories'] as $category)
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

                        @error('items.0.category_id')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                @endif
                {{-- Category --}}

                {{-- Subcategory --}}
                @if ($items[0]['category_id'])
                    <div
                        class="col-span-12 sm:col-span-6 md:col-span-4 sm:col-start-4 md:col-start-0 items-center w-full">
                        <select
                            class="rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('items.0.subcategory_id') border-red-900 border-2 @enderror"
                            wire:model.lazy="items.0.subcategory_id" required>
                            @if ($items[0]['subcategories'])
                                <option value="0">
                                    {{ __('admin/sitePages.Choose a subcategory') }}
                                </option>
                                @foreach ($items[0]['subcategories'] as $subcategory)
                                    <option value="{{ $subcategory['id'] }}">
                                        {{ $subcategory['name'][session('locale')] }}
                                    </option>
                                @endforeach
                            @else
                                <option value="0">
                                    {{ __('admin/sitePages.No Subcategories in the database') }}
                                </option>
                            @endif
                        </select>

                        @error('items.0.subcategory_id')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                @endif
                {{-- Subcategory --}}

                {{-- Edit Button : Start --}}
                @if ($items[0]['subcategory_id'])
                    <div class="col-span-12 text-center">
                        <a href="{{ route('admin.subcategories.edit', ['subcategory' => $items[0]['category_id']]) }}" target="_blank"
                            class="bg-edit hover:bg-editHover text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Edit Subcategory') }}</a>
                    </div>
                @endif
                {{-- Edit Button : End --}}


            </div>
            {{-- SelectBoxes --}}
        </div>
        {{-- No 1 : End --}}

        {{-- No 2 : Start --}}
        <div class="col-span-12 rounded-xl p-3 bg-gray-100 grid grid-cols-12 items-center justify-center gap-3">
            <label class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center"
                for="country">{{ __('admin/sitePages.Number 2') }}</label>
            {{-- SelectBoxes --}}
            <div class="col-span-12 md:col-span-10 grid grid-cols-12 gap-3">
                {{-- Supercategory --}}
                <div class="col-span-12 sm:col-span-6 md:col-span-4 items-center w-full">
                    <select
                        class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('items.1.supercategory_id') border-red-900 border-2 @enderror"
                        wire:model.lazy="items.1.supercategory_id" wire:change="supercategoryUpdated(1)" required>
                        @if ($items[1]['supercategories'])
                            <option value="0">
                                {{ __('admin/sitePages.Choose a supercategory') }}
                            </option>
                            @foreach ($items[1]['supercategories'] as $supercategory)
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

                    @error('items.1.supercategory_id')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
                {{-- Supercategory --}}

                {{-- Category --}}
                @if ($items[1]['supercategory_id'])
                    <div class="col-span-12 sm:col-span-6 md:col-span-4 items-center w-full">
                        <select
                            class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('items.1.category_id') border-red-900 border-2 @enderror"
                            wire:model.lazy="items.1.category_id" wire:change="categoryUpdated(1)" required>
                            @if ($items[1]['categories'])
                                <option value="0">
                                    {{ __('admin/sitePages.Choose a category') }}
                                </option>
                                @foreach ($items[1]['categories'] as $category)
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

                        @error('items.1.category_id')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                @endif
                {{-- Category --}}

                {{-- Subcategory --}}
                @if ($items[1]['category_id'])
                    <div
                        class="col-span-12 sm:col-span-6 md:col-span-4 sm:col-start-4 md:col-start-0 items-center w-full">
                        <select
                            class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('items.1.subcategory_id') border-red-900 border-2 @enderror"
                            wire:model.lazy="items.1.subcategory_id" required>
                            @if ($items[1]['subcategories'])
                                <option value="0">
                                    {{ __('admin/sitePages.Choose a subcategory') }}
                                </option>
                                @foreach ($items[1]['subcategories'] as $subcategory)
                                    <option value="{{ $subcategory['id'] }}">
                                        {{ $subcategory['name'][session('locale')] }}
                                    </option>
                                @endforeach
                            @else
                                <option value="0">
                                    {{ __('admin/sitePages.No Subcategories in the database') }}
                                </option>
                            @endif
                        </select>

                        @error('items.1.subcategory_id')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                @endif
                {{-- Subcategory --}}

                {{-- Edit Button : Start --}}
                @if ($items[1]['subcategory_id'])
                    <div class="col-span-12 text-center">
                        <a href="{{ route('admin.subcategories.edit', ['subcategory' => $items[1]['category_id']]) }}" target="_blank"
                            class="bg-edit hover:bg-editHover text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Edit Subcategory') }}</a>
                    </div>
                @endif
                {{-- Edit Button : End --}}

            </div>
            {{-- SelectBoxes --}}

        </div>
        {{-- No 2 : End --}}

        {{-- No 3 : Start --}}
        <div class="col-span-12 rounded-xl p-3 bg-red-100 grid grid-cols-12 items-center justify-center gap-3">
            <label class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center"
                for="country">{{ __('admin/sitePages.Number 3') }}</label>
            {{-- SelectBoxes --}}
            <div class="col-span-12 md:col-span-10 grid grid-cols-12 gap-3">
                {{-- Supercategory --}}
                <div class="col-span-12 sm:col-span-6 md:col-span-4 items-center w-full">
                    <select
                        class="rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('items.2.supercategory_id') border-red-900 border-2 @enderror"
                        wire:model.lazy="items.2.supercategory_id" wire:change="supercategoryUpdated(2)" required>
                        @if ($items[2]['supercategories'])
                            <option value="0">
                                {{ __('admin/sitePages.Choose a supercategory') }}
                            </option>
                            @foreach ($items[2]['supercategories'] as $supercategory)
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

                    @error('items.2.supercategory_id')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
                {{-- Supercategory --}}

                {{-- Category --}}
                @if ($items[2]['supercategory_id'])
                    <div class="col-span-12 sm:col-span-6 md:col-span-4 items-center w-full">
                        <select
                            class="rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('items.2.category_id') border-red-900 border-2 @enderror"
                            wire:model.lazy="items.2.category_id" wire:change="categoryUpdated(2)" required>
                            @if ($items[2]['categories'])
                                <option value="0">
                                    {{ __('admin/sitePages.Choose a category') }}
                                </option>
                                @foreach ($items[2]['categories'] as $category)
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

                        @error('items.2.category_id')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                @endif
                {{-- Category --}}

                {{-- Subcategory --}}
                @if ($items[2]['category_id'])
                    <div
                        class="col-span-12 sm:col-span-6 md:col-span-4 sm:col-start-4 md:col-start-0 items-center w-full">
                        <select
                            class="rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('items.2.subcategory_id') border-red-900 border-2 @enderror"
                            wire:model.lazy="items.2.subcategory_id" required>
                            @if ($items[2]['subcategories'])
                                <option value="0">
                                    {{ __('admin/sitePages.Choose a subcategory') }}
                                </option>
                                @foreach ($items[2]['subcategories'] as $subcategory)
                                    <option value="{{ $subcategory['id'] }}">
                                        {{ $subcategory['name'][session('locale')] }}
                                    </option>
                                @endforeach
                            @else
                                <option value="0">
                                    {{ __('admin/sitePages.No Subcategories in the database') }}
                                </option>
                            @endif
                        </select>

                        @error('items.2.subcategory_id')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                @endif
                {{-- Subcategory --}}

                {{-- Edit Button : Start --}}
                @if ($items[2]['subcategory_id'])
                    <div class="col-span-12 text-center">
                        <a href="{{ route('admin.subcategories.edit', ['subcategory' => $items[2]['category_id']]) }}" target="_blank"
                            class="bg-edit hover:bg-editHover text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Edit Subcategory') }}</a>
                    </div>
                @endif
                {{-- Edit Button : End --}}

            </div>
            {{-- SelectBoxes --}}

        </div>
        {{-- No 3 : End --}}

        {{-- No 4 : Start --}}
        <div class="col-span-12 rounded-xl p-3 bg-gray-100 grid grid-cols-12 items-center justify-center gap-3">
            <label class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center"
                for="country">{{ __('admin/sitePages.Number 4') }}</label>
            {{-- SelectBoxes --}}
            <div class="col-span-12 md:col-span-10 grid grid-cols-12 gap-3">
                {{-- Supercategory --}}
                <div class="col-span-12 sm:col-span-6 md:col-span-4 items-center w-full">
                    <select
                        class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('items.3.supercategory_id') border-red-900 border-2 @enderror"
                        wire:model.lazy="items.3.supercategory_id" wire:change="supercategoryUpdated(3)" required>
                        @if ($items[3]['supercategories'])
                            <option value="0">
                                {{ __('admin/sitePages.Choose a supercategory') }}
                            </option>
                            @foreach ($items[3]['supercategories'] as $supercategory)
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

                    @error('items.3.supercategory_id')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
                {{-- Supercategory --}}

                {{-- Category --}}
                @if ($items[3]['supercategory_id'])
                    <div class="col-span-12 sm:col-span-6 md:col-span-4 items-center w-full">
                        <select
                            class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('items.3.category_id') border-red-900 border-2 @enderror"
                            wire:model.lazy="items.3.category_id" wire:change="categoryUpdated(3)" required>
                            @if ($items[3]['categories'])
                                <option value="0">
                                    {{ __('admin/sitePages.Choose a category') }}
                                </option>
                                @foreach ($items[3]['categories'] as $category)
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

                        @error('items.3.category_id')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                @endif
                {{-- Category --}}

                {{-- Subcategory --}}
                @if ($items[3]['category_id'])
                    <div
                        class="col-span-12 sm:col-span-6 md:col-span-4 sm:col-start-4 md:col-start-0 items-center w-full">
                        <select
                            class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('items.3.subcategory_id') border-red-900 border-2 @enderror"
                            wire:model.lazy="items.3.subcategory_id" required>
                            @if ($items[3]['subcategories'])
                                <option value="0">
                                    {{ __('admin/sitePages.Choose a subcategory') }}
                                </option>
                                @foreach ($items[3]['subcategories'] as $subcategory)
                                    <option value="{{ $subcategory['id'] }}">
                                        {{ $subcategory['name'][session('locale')] }}
                                    </option>
                                @endforeach
                            @else
                                <option value="0">
                                    {{ __('admin/sitePages.No Subcategories in the database') }}
                                </option>
                            @endif
                        </select>

                        @error('items.3.subcategory_id')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                @endif
                {{-- Subcategory --}}

                {{-- Edit Button : Start --}}
                @if ($items[3]['subcategory_id'])
                    <div class="col-span-12 text-center">
                        <a href="{{ route('admin.subcategories.edit', ['subcategory' => $items[3]['category_id']]) }}" target="_blank"
                            class="bg-edit hover:bg-editHover text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Edit Subcategory') }}</a>
                    </div>
                @endif
                {{-- Edit Button : End --}}

            </div>
            {{-- SelectBoxes --}}

        </div>
        {{-- No 4 : End --}}

        {{-- No 5 : Start --}}
        <div class="col-span-12 rounded-xl p-3 bg-red-100 grid grid-cols-12 items-center justify-center gap-3">
            <label class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center"
                for="country">{{ __('admin/sitePages.Number 5') }}</label>
            {{-- SelectBoxes --}}
            <div class="col-span-12 md:col-span-10 grid grid-cols-12 gap-3">
                {{-- Supercategory --}}
                <div class="col-span-12 sm:col-span-6 md:col-span-4 items-center w-full">
                    <select
                        class="rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('items.4.supercategory_id') border-red-900 border-2 @enderror"
                        wire:model.lazy="items.4.supercategory_id" wire:change="supercategoryUpdated(4)" required>
                        @if ($items[4]['supercategories'])
                            <option value="0">
                                {{ __('admin/sitePages.Choose a supercategory') }}
                            </option>
                            @foreach ($items[4]['supercategories'] as $supercategory)
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

                    @error('items.4.supercategory_id')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
                {{-- Supercategory --}}

                {{-- Category --}}
                @if ($items[4]['supercategory_id'])
                    <div class="col-span-12 sm:col-span-6 md:col-span-4 items-center w-full">
                        <select
                            class="rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('items.4.category_id') border-red-900 border-2 @enderror"
                            wire:model.lazy="items.4.category_id" wire:change="categoryUpdated(4)" required>
                            @if ($items[4]['categories'])
                                <option value="0">
                                    {{ __('admin/sitePages.Choose a category') }}
                                </option>
                                @foreach ($items[4]['categories'] as $category)
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

                        @error('items.4.category_id')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                @endif
                {{-- Category --}}

                {{-- Subcategory --}}
                @if ($items[4]['category_id'])
                    <div
                        class="col-span-12 sm:col-span-6 md:col-span-4 sm:col-start-4 md:col-start-0 items-center w-full">
                        <select
                            class="rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('items.4.subcategory_id') border-red-900 border-2 @enderror"
                            wire:model.lazy="items.4.subcategory_id" required>
                            @if ($items[4]['subcategories'])
                                <option value="0">
                                    {{ __('admin/sitePages.Choose a subcategory') }}
                                </option>
                                @foreach ($items[4]['subcategories'] as $subcategory)
                                    <option value="{{ $subcategory['id'] }}">
                                        {{ $subcategory['name'][session('locale')] }}
                                    </option>
                                @endforeach
                            @else
                                <option value="0">
                                    {{ __('admin/sitePages.No Subcategories in the database') }}
                                </option>
                            @endif
                        </select>

                        @error('items.4.subcategory_id')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                @endif
                {{-- Subcategory --}}

                {{-- Edit Button : Start --}}
                @if ($items[4]['subcategory_id'])
                    <div class="col-span-12 text-center">
                        <a href="{{ route('admin.subcategories.edit', ['subcategory' => $items[4]['category_id']]) }}" target="_blank"
                            class="bg-edit hover:bg-editHover text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Edit Subcategory') }}</a>
                    </div>
                @endif
                {{-- Edit Button : End --}}

            </div>
            {{-- SelectBoxes --}}

        </div>
        {{-- No 5 : End --}}

    </div>

    <div class="flex flex-wrap gap-3 justify-around mt-4">
        {{-- Save --}}
        <button wire:click.prevent="save"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Update') }}</button>
        {{-- Back --}}
        <a href="{{ route('admin.homepage') }}"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Back') }}</a>
    </div>

</div>
