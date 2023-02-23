<div class="flex flex-col gap-3">
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    {{-- Add Product Search : Start --}}
    <div class="grid grid-cols-12 justify-center gap-3 items-center">
        <div class="relative col-span-12 md:col-span-6 md:col-start-4">
            <div class="flex rounded-md shadow-sm">
                <span
                    class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-gray-700 bg-gray-700 text-center text-white text-sm">
                    <span class="material-icons">
                        search
                    </span>
                </span>
                <input type="text" wire:model="search"
                    onblur="setTimeout(() => {
                    window.livewire.emit('clearSearch');
                }, 100)"
                    wire:keydown.escape="$emit('clearSearch')"
                    class="searchInput focus:ring-0 flex-1 block rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-200"
                    placeholder="{{ __('admin/offersPages.Search ...') }}">
            </div>
            {{-- Search Collection Input :: End --}}
            @if (count($list))
                <div
                    class="absolute button-0 left-0 w-full z-10 bg-white border border-t-0 border-gray-200 max-h-36 overflow-x-hidden rounded-b-xl p-2 scrollbar scrollbar-thin scrollbar-thumb-primary">
                    {{-- Loading :: Start --}}
                    <div wire:loading.delay wire:target="list" class="w-full">
                        <div class="flex gap-2 justify-center items-center p-4">
                            <span class="text-primary text-xs font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                    class="animate-spin text-9xl" height="1em" preserveAspectRatio="xMidYMid meet"
                                    viewBox="0 0 50 50">
                                    <path fill="currentColor"
                                        d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    {{-- Products List :: Start --}}
                    @forelse ($list as $product)
                        <div class="group flex justify-center items-center gap-1 cursor-pointer rounded transition-all ease-in-out hover:bg-red-100 p-2"
                            wire:click.stop="addProduct({{ $product['id'] }},'{{ $product['product_collection'] }}')"
                            wire:key="product-{{ $product['id'] }}-{{ rand() }}">
                            {{-- Product's Name --}}
                            <div class="flex flex-col justify-start ltr:text-left rtl:text-right gap-2 grow">
                                <span class="font-bold text-black">{{ $product['name'][session('locale')] }}</span>
                                @if (isset($product['brand']))
                                    <span
                                        class="text-xs font-bold text-gray-500">{{ $product['brand'] ? $product['brand']['name'] : '' }}</span>
                                @endif
                            </div>

                            {{-- Price --}}
                            <div class="flex flex-wrap gap-2 justify-around items-center">
                                @if ($product['under_reviewing'])
                                    <span class="bg-yellow-600 px-2 py-1 rounded text-white">
                                        {{ __('admin/productsPages.Under Reviewing') }}
                                    </span>
                                @elseif ($product['final_price'] == $product['base_price'])
                                    <span class="bg-success px-2 py-1 rounded text-white" dir="ltr">
                                        {{ number_format($product['final_price'], 2, '.', '\'') }}
                                        <span class="">
                                            {{ __('admin/productsPages. EGP') }}
                                        </span>
                                    </span>
                                @else
                                    <span class="line-through bg-red-600 px-2 py-1 rounded text-white" dir="ltr">
                                        {{ number_format($product['base_price'], 2, '.', '\'') }}
                                        <span class="">
                                            {{ __('admin/productsPages. EGP') }}
                                        </span>
                                    </span>
                                    <span class="bg-success px-2 py-1 rounded text-white ltr:ml-1 rtl:mr-1"
                                        dir="ltr">
                                        {{ number_format($product['final_price'], 2, '.', '\'') }}
                                        <span class="">
                                            {{ __('admin/productsPages. EGP') }}
                                        </span>
                                    </span>
                                @endif

                                {{-- Points --}}
                                <span class="bg-yellow-600 px-2 py-1 rounded text-white" dir="ltr">
                                    {{ number_format($product['points'], 2, '.', '\'') ?? 0 }}
                                </span>
                            </div>
                        </div>

                        @if (!$loop->last)
                            <hr class="my-1">
                        @endif
                    @empty
                        <div class="text-center font-bold">
                            {{ __('admin/offersPages.No Products or Collections Found') }}
                        </div>
                    @endforelse
                    {{-- Products List :: End --}}
                </div>
            @endif
        </div>

    </div>
    {{-- Add Product Search : End --}}

    {{-- List :: Start --}}
    @foreach ($items as $key => $product)
        <div class="flex flex-wrap gap-2 w-100 justify-between items-center @if ($key % 2 == 0) bg-red-100 @else bg-gray-100 @endif rounded-xl"
            wire:key='product-{{ $key }}-{{ $product['id'] }}'>

            {{-- Rank --}}
            <div class="p-2 text-center">
                <div class="text-sm text-gray-900">
                    @if ($product['rank'] && $product['rank'] != 0 && $product['rank'] <= 11)
                        <div class="flex gap-2 items-center min-w-max">

                            <div>
                                {{-- down : Start --}}
                                <span
                                    class="material-icons rounded text-white text-lg @if ($product['rank'] < 12) @if ($key % 2 == 0) bg-primary @else bg-secondary @endif cursor-pointer
@else
bg-gray-200 @endif select-none"
                                    wire:click="rankDown({{ $product['id'] }},'{{ $product['type'] }}')">
                                    expand_more
                                </span>
                                {{-- down : End --}}

                                {{-- up : Start --}}
                                <span
                                    class="material-icons rounded text-white text-lg @if ($product['rank'] > 1) @if ($key % 2 == 0) bg-primary @else bg-secondary @endif cursor-pointer
@else
bg-gray-200 @endif select-none"
                                    wire:click="rankUp({{ $product['id'] }},'{{ $product['type'] }}')">
                                    expand_less
                                </span>
                                {{-- up : Start --}}
                            </div>

                            <span class="font-bold">
                                {{ $product['rank'] }}
                            </span>
                        </div>
                    @else
                        <div class="flex gap-2 items-center min-w-max">

                            <div>
                                {{-- down : Start --}}
                                <span
                                    class="material-icons rounded text-white text-lg @if ($product['rank'] < 11) @if ($key % 2 == 0) bg-primary @else bg-secondary @endif cursor-pointer
@else
bg-gray-200 @endif select-none"
                                    wire:click="rankDown({{ $product['id'] }},'{{ $product['type'] }}')">
                                    expand_more
                                </span>
                                {{-- down : End --}}

                                {{-- up : Start --}}
                                <span
                                    class="material-icons rounded text-white text-lg @if ($product['rank'] > 1) @if ($key % 2 == 0) bg-primary @else bg-secondary @endif cursor-pointer
@else
bg-gray-200 @endif select-none"
                                    wire:click="rankUp({{ $product['id'] }},'{{ $product['type'] }}')">
                                    expand_less
                                </span>
                                {{-- up : Start --}}
                            </div>

                            <span class="font-bold">
                                0
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Image & Name --}}
            <div class="grow p-2 text-center flex items-center gap-2 max-w-50">
                <div class="flex-shrink-0 h-10 w-10">
                    @if ($product['thumbnail'])
                        <img class="h-10 w-10 rounded-full"
                            @if ($product['type'] == 'Product') src="{{ asset('storage/images/products/cropped100/' . $product['thumbnail']['file_name']) }}"
                        @elseif ($product['type'] == 'Collection') src="{{ asset('storage/images/collections/cropped100/' . $product['thumbnail']['file_name']) }}" @endif
                            alt="{{ $product['name'][session('locale')] . 'image' }}">
                    @else
                        <div
                            class="h-10 w-10 rounded-full text-white @if ($key % 2 == 0) bg-primary @else bg-secondary @endif flex justify-center items-center">
                            <span class="material-icons">
                                construction
                            </span>
                        </div>
                    @endif
                </div>
                <span class="truncate">
                    {{ $product['name'][session('locale')] }}
                </span>
            </div>


            {{-- Price : Start --}}
            <div class="p-2 text-center">
                <div class="text-sm flex gap-2 ">
                    @if ($product['under_reviewing'])
                        <span class="bg-yellow-600 px-2 p-1 rounded text-white text-xs">
                            {{ __('admin/sitePages.Under Reviewing') }}
                        </span>
                    @elseif ($product['final_price'] == $product['base_price'])
                        <div
                            class="flex flex-col items-center content-center justify-center bg-secondary p-1 rounded shadow">
                            <span class="font-bold text-xs mb-1 text-white">
                                {{ __('admin/sitePages.Original Price') }}
                            </span>
                            <div class="text-sm font-medium text-gray-900 bg-white p-1 w-100 rounded shadow">
                                <span dir="ltr">
                                    {{ number_format($product['original_price'], 2, '.', '\'') ?? 0 }}
                                </span>
                                <span class="text-xs">
                                    {{ __('admin/sitePages. EGP') }}
                                </span>
                            </div>
                        </div>
                        <div
                            class="flex flex-col items-center content-center justify-center bg-success p-1 rounded shadow">
                            <span class="font-bold text-xs mb-1 text-white">
                                {{ __('admin/sitePages.Final Price') }}
                            </span>
                            <div class="text-sm font-medium text-gray-900 bg-white p-1 w-100 rounded shadow">
                                <span dir="ltr">
                                    {{ number_format($product['final_price'], 2, '.', '\'') ?? 0 }}
                                </span>
                                <span class="text-xs">
                                    {{ __('admin/sitePages. EGP') }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div
                            class="flex flex-col items-center content-center justify-center bg-secondary p-1 rounded shadow">
                            <span class="font-bold text-xs mb-1 text-white">
                                {{ __('admin/sitePages.Original Price') }}
                            </span>
                            <div class="text-sm font-medium text-gray-900 bg-white p-1 w-100 rounded shadow">
                                <span dir="ltr">
                                    {{ number_format($product['original_price'], 2, '.', '\'') ?? 0 }}
                                </span>
                                <span class="text-xs">
                                    {{ __('admin/sitePages. EGP') }}
                                </span>
                            </div>
                        </div>
                        <div
                            class="flex flex-col items-center content-center justify-center bg-red-600 p-1 rounded shadow">
                            <span class="font-bold text-xs mb-1 text-white">
                                {{ __('admin/sitePages.Base Price') }}
                            </span>
                            <div
                                class="line-through text-sm font-medium text-gray-900 bg-white p-1 w-100 rounded shadow">
                                <span dir="ltr">
                                    {{ number_format($product['base_price'], 2, '.', '\'') ?? 0 }}
                                </span>
                                <span class="text-xs">
                                    {{ __('admin/sitePages. EGP') }}
                                </span>
                            </div>
                        </div>
                        <div
                            class="flex flex-col items-center content-center justify-center bg-success p-1 rounded shadow">
                            <span class="font-bold text-xs mb-1 text-white">
                                {{ __('admin/sitePages.Final Price') }}
                            </span>
                            <div class="text-sm font-medium text-gray-900 bg-white p-1 w-100 rounded shadow">
                                <span dir="ltr">
                                    {{ number_format($product['final_price'], 2, '.', '\'') ?? 0 }}
                                </span>
                                <span class="text-xs">
                                    {{ __('admin/sitePages. EGP') }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            {{-- Price : End --}}

            {{-- Points : Start --}}
            <div class="p-2 text-center">
                <div class="flex flex-col items-center content-center justify-center bg-yellow-600 p-1 rounded shadow">
                    <span class="font-bold text-xs mb-1 text-white">
                        {{ __('admin/sitePages.Points') }}
                    </span>
                    <div class="text-sm font-medium text-gray-900 bg-white p-1 w-100 rounded shadow">
                        <span dir="ltr">
                            {{ number_format($product['points'], 0, '.', '\'') ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>
            {{-- Points : End --}}

            {{-- Buttons : Start --}}
            <div class="p-2 text-center text-sm font-medium flex gap-2">

                {{-- Edit Button --}}
                <a target="_blank" data-title="{{ __('admin/sitePages.Edit') }}" data-toggle="tooltip"
                    @if ($product['type'] == 'Product') href="{{ route('admin.products.edit', [$product['id']]) }}"
@elseif ($product['type'] == 'Collection') href="{{ route('admin.collections.edit', [$product['id']]) }}" @endif
                    data-placement="top" class="m-0">
                    <span class="material-icons p-1 text-lg w-9 h-9 text-white bg-edit hover:bg-editHover rounded">
                        edit
                    </span>
                </a>

                {{-- Delete Button --}}
                <a href="#" data-title="{{ __('admin/sitePages.Remove from list') }}" data-toggle="tooltip"
                    data-placement="top"
                    @if ($product['type'] == 'Product') wire:click.prevent="removeProduct({{ $product['id'] }})"
                    @elseif ($product['type'] == 'Collection')
                    wire:click.prevent="removeCollection({{ $product['id'] }})" @endif
                    class="m-0">
                    <span
                        class="material-icons p-1 text-lg w-9 h-9 text-white bg-delete hover:bg-deleteHover rounded-circle">
                        close
                    </span>
                </a>
            </div>
            {{-- Buttons : End --}}

        </div>
    @endforeach
    {{-- List :: End --}}

    {{-- Buttons Section Start --}}
    <div class="col-span-12 w-full flex flex-wrap justify-around">
        <button type="button" wire:click.prevent="save" wire:loading.attr="disabled"
            class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Update') }}</button>
        {{-- Back --}}
        <a href="{{ route('admin.setting.homepage') }}"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Back') }}</a>
    </div>
    {{-- Buttons Section End --}}
</div>
