<div class="flex flex-col gap-3">

    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    {{-- Add Product Button : Start --}}
    <div class="flex justify-center gap-3 items-center">

        {{-- Add Product to list :: Start --}}
        <button wire:click.stop.prevent="$set('addProduct',1)"
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">
            <span class="material-icons rtl:ml-1 ltr:mr-1">
                add
            </span>
            {{ __('admin/sitePages.Add Products to Section') }}
        </button>
        {{-- Add Product to list :: End --}}

    </div>
    {{-- Add Product Button : End --}}

    {{-- List :: Start --}}
    @foreach ($products as $key => $product)
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
                                    wire:click="rankDown({{ $product['id'] }})">
                                    expand_more
                                </span>
                                {{-- down : End --}}

                                {{-- up : Start --}}
                                <span
                                    class="material-icons rounded text-white text-lg @if ($product['rank'] > 1) @if ($key % 2 == 0) bg-primary @else bg-secondary @endif cursor-pointer
@else
bg-gray-200 @endif select-none"
                                    wire:click="rankUp({{ $product['id'] }})">
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
                                    wire:click="rankDown({{ $product['id'] }})">
                                    expand_more
                                </span>
                                {{-- down : End --}}

                                {{-- up : Start --}}
                                <span
                                    class="material-icons rounded text-white text-lg @if ($product['rank'] > 1) @if ($key % 2 == 0) bg-primary @else bg-secondary @endif cursor-pointer
@else
bg-gray-200 @endif select-none"
                                    wire:click="rankUp({{ $product['id'] }})">
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
            <div class="grow p-2 text-center flex items-center gap-2 w-50">
                <div class="flex-shrink-0 h-10 w-10">
                    @if ($product['thumbnail'])
                        <img class="h-10 w-10 rounded-full"
                            src="{{ asset('storage/images/products/cropped100/' . $product['thumbnail']['file_name']) }}"
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
                            class="flex flex-col items-center content-center justify-center bg-success p-1 rounded shadow">
                            <span class="font-bold text-xs mb-1 text-white">
                                {{ __('admin/sitePages.Final Price') }}
                            </span>
                            <div class="text-sm font-medium text-gray-900 bg-white p-1 w-100 rounded shadow">
                                {{ $product['final_price'] ?? 0 }}
                                <span class="text-xs">
                                    {{ __('admin/sitePages. EGP') }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div
                            class="flex flex-col items-center content-center justify-center bg-red-600 p-1 rounded shadow">
                            <span class="font-bold text-xs mb-1 text-white">
                                {{ __('admin/sitePages.Base Price') }}
                            </span>
                            <div
                                class="line-through text-sm font-medium text-gray-900 bg-white p-1 w-100 rounded shadow">
                                {{ $product['base_price'] ?? 0 }}
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
                                {{ $product['final_price'] ?? 0 }}
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
                        {{ $product['points'] ?? 0 }}
                    </div>
                </div>
            </div>
            {{-- Points : End --}}

            {{-- Buttons : Start --}}
            <div class="p-2 text-center text-sm font-medium flex gap-2">

                {{-- Edit Button --}}
                <a href="{{ route('admin.products.edit', [$product['id']]) }}" target="_blank"
                    data-title="{{ __('admin/sitePages.Edit') }}" data-toggle="tooltip" data-placement="top"
                    class="m-0">
                    <span class="material-icons p-1 text-lg w-9 h-9 text-white bg-edit hover:bg-editHover rounded">
                        edit
                    </span>
                </a>

                {{-- Delete Button --}}
                <a href="#" data-title="{{ __('admin/sitePages.Remove from list') }}" data-toggle="tooltip"
                    data-placement="top" wire:click.prevent="removeProduct({{ $product['id'] }})"
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


    {{-- Add Product Modal : Start --}}
    <div wire:click="$set('addProduct',0)"
        class="backdrop-blur-sm cursor-pointer @if ($addProduct) flex
        @else
        hidden @endif fixed top-0 left-0 z-50 flex justify-center items-center gap-4 w-100 h-100 bg-gray-500/[.4]">
        <div wire:click.stop="$set('addProduct',1)"
            class="cursor-default rounded-xl bg-white w-3/4 md:w-1/2 border-4 border-primary p-3 flex flex-col gap-2">

            <h4 class="h5 md:h4 font-bold mb-2 text-center m-0 event-none">
                {{ __('admin/sitePages.Add Products to Section') }}
            </h4>

            <div class="col-span-12 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center rounded text-center">
                <label for="product_name"
                    class="col-span-12 md:col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __("admin/sitePages.Product's Name") }}</label>
                <div class="col-span-12 md:col-span-9">
                    <input
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                        type="text" wire:model.debounce.300ms="searchProduct" onfocus="Livewire.emit('showResults',1);"
                        id="product_name" placeholder="{{ __("admin/sitePages.Enter Product's Name") }}"
                        maxlength="100" autocomplete="off" required>
                    @if ($searchProduct != '' && $showResult)
                        <div class="relative h-0" wire:key="add-product-321231">
                            <div class="absolute top-0 w-full flex flex-col justify-center items-center">
                                <ul
                                    class="bg-white w-100 z-10 rounded-b-xl overflow-auto border-x border-b border-primary px-1 max-h-48 scrollbar scrollbar-hidden-y">
                                    @forelse ($products_list as $key => $product)
                                        <li wire:click.stop.prevent="productSelected({{ $product['id'] }},'{{ $product->name }}')"
                                            wire:key="add-product-{{ $key }}-{{ $product['id'] }}"
                                            class="btn bg-white border-b py-3 flex flex-wrap justify-center items-center gap-3 rounded-xl">

                                            {{-- Product's Name --}}
                                            <div
                                                class="flex flex-col justify-start ltr:text-left rtl:text-right gap-2 grow">
                                                <span class="font-bold text-black">{{ $product->name }}</span>
                                                <span
                                                    class="text-xs font-bold text-gray-500">{{ $product->brand->name }}</span>
                                            </div>

                                            {{-- Price --}}
                                            <span class="text-xs">
                                                @if ($product->under_reviewing)
                                                    <span class="bg-yellow-600 px-2 py-1 rounded text-white">
                                                        {{ __('admin/productsPages.Under Reviewing') }}
                                                    </span>
                                                @elseif ($product->final_price == $product->base_price)
                                                    <span class="bg-success px-2 py-1 rounded text-white">
                                                        {{ $product->final_price }}
                                                        <span class="">
                                                            {{ __('admin/productsPages. EGP') }}
                                                        </span>
                                                    </span>
                                                @else
                                                    <span class="line-through bg-red-600 px-2 py-1 rounded text-white">
                                                        {{ $product->base_price }}
                                                        <span class="">
                                                            {{ __('admin/productsPages. EGP') }}
                                                        </span>
                                                    </span>
                                                    <span
                                                        class="bg-success px-2 py-1 rounded text-white ltr:ml-1 rtl:mr-1">
                                                        {{ $product->final_price }}
                                                        <span class="">
                                                            {{ __('admin/productsPages. EGP') }}
                                                        </span>
                                                    </span>
                                                @endif
                                            </span>

                                            {{-- Points --}}
                                            <span class="bg-yellow-600 px-2 py-1 rounded text-white">
                                                {{ $product->points ?? 0 }}
                                            </span>
                                        </li>
                                    @empty
                                        <li
                                            class="border-b py-3 flex flex-wrap justify-center items-center gap-3 rounded-xl font-bold">
                                            {{ __('admin/sitePages.No Results according to your search') }}
                                        </li>
                                    @endforelse

                                </ul>
                            </div>

                        </div>
                    @endif
                </div>

            </div>


            {{-- Buttons Section Start --}}
            <div class="col-span-12 w-full flex mt-2 justify-around">
                {{-- Add --}}
                <button type="button" wire:click.prevent="add"
                    class="bg-success hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Add') }}</button>
                {{-- Back --}}
                <a href="#" wire:click.stop.prevent="$set('addProduct',0)"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Cancel') }}</a>

            </div>
            {{-- Buttons Section End --}}
        </div>
    </div>
    {{-- Add Product Modal : Start --}}

</div>
