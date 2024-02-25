<div class="grid grid-cols-12 gap-3 items-start">

    {{-- Loader : :: Start --}}
    <x-admin.waiting />
    {{-- Loader : :: End --}}

    {{-- Big Side :: Start --}}
    <div class="col-span-12 lg:col-span-8 w-full grid gap-3">

        {{-- ######################################################### --}}
        {{-- ######################################################### --}}
        {{-- Media :: Start --}}
        <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-gray-100 p-4 text-center  rounded shadow">

            <div class="col-span-12 font-bold text-black mb-2">{{ __('admin/productsPages.Collection Media') }}</div>

            {{-- Gallery Images :: Start --}}
            <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 items-center w-full">
                {{-- Loading Spinner --}}
                <div wire:loading wire:target="gallery_images" class="col-span-12 my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 50 50" class="animate-spin inline-block">
                        <path fill="currentColor"
                            d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                    </svg>
                    <span> &nbsp;&nbsp; {{ __('admin/productsPages.Uploading ...') }}</span>
                </div>

                {{-- Gallery Image Label --}}
                <label for="gallery_images" class="col-span-12 text-xs text-gray-700 font-bold text-center">
                    {{ __('admin/productsPages.Gallery Images') }} </label>

                @if (!empty($gallery_images_name))
                    {{-- preview --}}
                    <div class="col-span-12 grid grid-cols-1 gap-3 items-center w-full">
                        <div class="text-center flex flex-wrap gap-3 justify-around">
                            @foreach ($gallery_images_name as $key => $gallery_image_name)
                                <div class="relative w-25">
                                    <span
                                        class="material-icons absolute rounded-circle bg-red-500 w-6 h-6 text-white left-2 top-2 text-sm font-bold cursor-pointer flex items-center justify-center select-none"
                                        wire:click="deleteImage({{ $key }})"
                                        title="{{ __('admin/productsPages.Delete Image') }}">clear</span>
                                    <span
                                        class="material-icons absolute rounded-circle w-6 h-6 select-none
                                        @if ($featured == $key) border-0 border-success text-white bg-success
                                        @else
                                        border-2 border-gray-500 text-gray-500 @endif
                                        right-2 top-2 text-sm font-bold cursor-pointer flex items-center justify-center"
                                        wire:click="setFeatured({{ $key }})"
                                        title="{{ __('admin/productsPages.Make Featured') }}">done</span>
                                    <img src="{{ asset('storage/images/collections/original/' . $gallery_image_name) }}"
                                        class="rounded-xl">
                                </div>
                            @endforeach
                        </div>

                        {{-- Upload More Image --}}
                        <label for="gallery_images" class="col-span-1 mt-2 text-xs text-gray-700 font-bold text-center">
                            {{ __('admin/productsPages.Add more images') }} </label>

                        <input
                            class="col-span-1 block w-full pl-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                            id="gallery_images" type="file" type="image" wire:model.live.blur="gallery_images" multiple>
                        <span class="col-span-1 text-xs text-gray-400">
                            {{ __('admin/productsPages.Use 600x600 sizes images') }}</span>
                        @error('gallery_images.*')
                            <span
                                class="col-span-1 bg-red-700 rounded text-white shadow px-3 py-1">{{ $message }}</span>
                        @enderror

                        <div class="mt-1 text-center">
                            <button class="btn btn-danger btn-sm text-bold"
                                wire:click.prevent='removePhoto'>{{ __('admin/productsPages.Remove / Replace All Collection Image') }}</button>
                        </div>
                    </div>
                @else
                    {{-- Upload New Image --}}
                    <input
                        class="col-span-12 block w-full pl-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                        id="gallery_images" type="file" type="image" wire:model.live.blur="gallery_images" multiple>
                    <span class="col-span-12 text-xs text-gray-400">
                        {{ __('admin/productsPages.Use 600x600 sizes images') }}</span>
                    @error('gallery_images.*')
                        <span class="col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">{{ $message }}</span>
                    @enderror

                @endif
            </div>
            {{-- Gallery Images :: End --}}

            <hr class="col-span-12 w-full my-2">

            {{-- Thumbnail Images :: Start --}}
            <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 items-center w-full">

                {{-- Loading Spinner --}}
                <div wire:loading wire:target="thumbnail_image" class="col-span-12 my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                        height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 50 50"
                        class="animate-spin inline-block">
                        <path fill="currentColor"
                            d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                    </svg>
                    <span> &nbsp;&nbsp; {{ __('admin/productsPages.Uploading ...') }}</span>
                </div>

                {{-- Thumbnail Image Label --}}
                <label for="thumbnail_image" class="col-span-12 text-xs text-gray-700 font-bold text-center">
                    {{ __('admin/productsPages.Thumbnail Image') }} </label>

                @if ($thumbnail_image_name != null)
                    {{-- preview --}}
                    <div class="col-span-12 grid grid-cols-1 gap-3 items-center w-full">
                        <div class="text-center flex flex-wrap gap-3 justify-around">
                            <div class="relative w-25">
                                <span
                                    class="material-icons absolute rounded-circle bg-red-500 w-6 h-6 text-white left-2 top-2 text-sm font-bold cursor-pointer flex items-center justify-center select-none"
                                    wire:click="deleteThumbnail"
                                    title="{{ __('admin/productsPages.Delete Image') }}">clear</span>
                                <img src="{{ asset('storage/images/collections/original/' . $thumbnail_image_name) }}"
                                    class="rounded-xl">
                            </div>
                        </div>

                    </div>
                @else
                    {{-- Upload New Image --}}
                    <input
                        class="col-span-12 block w-full pl-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                        id="thumbnail_image" type="file" type="image" wire:model.live.blur="thumbnail_image">
                    <span class="col-span-12 text-xs text-gray-400">
                        {{ __('admin/productsPages.Use 300x300 sizes image') }}</span>
                    @error('thumbnail_image')
                        <span class="col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">{{ $message }}</span>
                    @enderror
                @endif
            </div>
            {{-- Thumbnail Images :: End --}}

            <hr class="col-span-12 w-full my-2">

            {{-- Video Link Start --}}
            <div class="col-span-12 grid grid-cols-6 gap-x-4 gap-y-2 items-center w-full">
                <label for="video"
                    class="col-span-6 sm:col-span-1 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Video URL') }}</label>
                <div class="col-span-6 sm:col-span-5">
                    <input id="video" dir="ltr"
                        class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('video') border-red-900 border-2 @enderror"
                        type="text" wire:model.live.blur="video"
                        placeholder="{{ __('admin/productsPages.Youtube Link') }}">

                    @error('video')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Video Link End --}}
        </div>
        {{-- Media :: End --}}
        {{-- ######################################################### --}}
        {{-- ######################################################### --}}

        {{-- ######################################################### --}}
        {{-- ######################################################### --}}
        {{-- Collection's Products :: Start --}}
        <div class="grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-red-100 p-4 text-center rounded shadow">

            <div class="col-span-12 font-bold text-black mb-2">
                {{ __("admin/productsPages.Collection's Products") }}
            </div>

            <div class="col-span-12 relative">
                {{-- Search Product Input :: Start --}}
                <div class="flex rounded-md shadow-sm">
                    <span
                        class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-primary bg-primary text-center text-white text-sm">
                        <span class="material-icons">
                            search
                        </span>
                    </span>
                    <input type="text" wire:model.live.debounce.500ms='search' wire:keydown.Escape="$set('search','')"
                        data-name="collection-form"
                        class="searchInput flex-1 block rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm py-1 w-full border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                        placeholder="{{ __('admin/ordersPages.Search ...') }}">
                </div>
                {{-- Search Product Input :: End --}}

                @if ($search != null)
                    <div
                        class="absolute button-0 left-0 w-full z-10 bg-white border border-t-0 border-primary max-h-36 overflow-x-hidden rounded-b-xl p-2 scrollbar scrollbar-thin scrollbar-thumb-primary">
                        {{-- Loading :: Start --}}
                        <div wire:loading.delay wire:target="search" class="w-full">
                            <div class="flex gap-2 justify-center items-center p-4">
                                <span class="text-primary text-xs font-bold">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                        width="1em" class="animate-spin text-9xl" height="1em"
                                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 50 50">
                                        <path fill="currentColor"
                                            d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        {{-- Products List :: Start --}}
                        @forelse ($productsList as $product)
                            <div class="group flex justify-center items-center gap-1 cursor-pointer rounded transition-all ease-in-out hover:bg-red-100 p-2"
                                wire:click.stop="addProduct({{ $product->id }})"
                                wire:key="product-{{ $product->id }}-{{ rand() }}">
                                {{-- Product's Name --}}
                                <div class="flex flex-col justify-start ltr:text-left rtl:text-right gap-2 grow">
                                    <span class="font-bold text-black">{{ $product->name }}</span>
                                    <span
                                        class="text-xs font-bold text-gray-500">{{ $product->brand ? $product->brand->name : '' }}</span>
                                </div>

                                {{-- Price --}}
                                <div class="flex flex-wrap gap-2 justify-around items-center">
                                    {{-- Original Price --}}
                                    <span class="bg-secondary px-2 py-1 rounded text-white">
                                        {{ $product->original_price ?? 0.0 }}
                                        <span class="">
                                            {{ __('admin/productsPages. EGP') }}
                                        </span>
                                    </span>

                                    {{-- Base & Final Prices --}}
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
                                        <span class="bg-success px-2 py-1 rounded text-white ltr:ml-1 rtl:mr-1">
                                            {{ $product->final_price }}
                                            <span class="">
                                                {{ __('admin/productsPages. EGP') }}
                                            </span>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if (!$loop->last)
                                <hr class="my-1">
                            @endif
                        @empty
                            <div class="text-center font-bold">
                                {{ __('admin/ordersPages.No Products Found') }}
                            </div>
                        @endforelse
                        {{-- Products List :: End --}}
                    </div>
                @endif
            </div>

            @if (count($products))
                <div class="col-span-12">
                    {{-- Clear All Products --}}
                    <button wire:click="clearProducts"
                        class="btn btn-sm bg-red-500 hover:bg-red-700 focus:bg-red-700 active:bg-red-700 font-bold">
                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                            close
                        </span>
                        {{ __('admin/ordersPages.Clear Products') }}
                    </button>
                </div>
            @endif

            {{-- Product Selected :: Start --}}
            @if (count($products))
                <hr class="col-span-12 ">

                {{-- Product Info :: Start --}}
                <div class="col-span-12 flex flex-col justify-center items-center gap-2">
                    @forelse ($products as $product)
                        {{-- Product : Start --}}
                        <div class="p-4 scrollbar scrollbar-thin w-full bg-white rounded shadow"
                            wire:key='product-{{ $product['id'] }}-{{ rand() }}'>
                            <div class="flex gap-6 justify-start items-center">
                                {{-- Thumnail :: Start --}}
                                <a href="{{ route('front.products.show', ['id' => $product['id'], 'slug' => $product['slug'][session('locale')]]) }}"
                                    target="_blank" class="min-w-max block hover:text-current">
                                    @if ($product['thumbnail'])
                                        <img class="w-full h-full flex justify-center items-center bg-gray-200 rounded overflow-hidden"
                                            src="{{ asset('storage/images/products/cropped100/' . $product['thumbnail']['file_name']) }}"
                                            alt="{{ $product['name'][session('locale')] . 'image' }}">
                                    @else
                                        <div
                                            class="w-full h-full flex justify-center items-center bg-gray-200 rounded">
                                            <span class="block material-icons text-8xl">
                                                construction
                                            </span>
                                        </div>
                                    @endif
                                </a>
                                {{-- Thumnail :: End --}}

                                <div class="flex gap-6 justify-between items-center w-full max-w-100">
                                    {{-- Product Info : Start --}}
                                    <div class="grow flex flex-col justify-start gap-2">
                                        {{-- Product's Brand :: Start --}}
                                        <div class="flex items-center">
                                            <a href="{{ route('front.brands.show', ['brand' => $product['brand']['id']]) }}"
                                                class="text-sm font-bold text-gray-400 hover:text-current">
                                                {{ $product['brand'] ? $product['brand']['name'] : '' }}
                                            </a>
                                        </div>
                                        {{-- Product's Brand :: End --}}

                                        {{-- Product Name : Start --}}
                                        <div class="flex items-center">
                                            <a href="{{ route('front.products.show', ['id' => $product['id'], 'slug' => $product['slug'][session('locale')]]) }}"
                                                target="_blank" class="text-lg font-bold hover:text-current">
                                                {{ $product['name'][session('locale')] }}
                                            </a>
                                        </div>
                                        {{-- Product Name : End --}}

                                        {{-- Reviews : Start --}}
                                        <div class="my-1 flex justify-start items-center gap-2 select-none">
                                            <div class="rating flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span
                                                        class="material-icons inline-block @if ($i <= ceil($product['avg_rating'])) text-yellow-300 @else text-gray-400 @endif">
                                                        star
                                                    </span>
                                                @endfor
                                            </div>

                                            <span
                                                class="text-sm text-gray-600">({{ $product['reviews_count'] ?? 0 }})</span>
                                        </div>
                                        {{-- Reviews : End --}}
                                    </div>
                                    {{-- Product Info : End --}}

                                    {{-- Product Price : Start --}}
                                    <div class="flex flex-col items-end justify-center gap-2">
                                        @if ($product['under_reviewing'])
                                            <span class="text-yellow-600 font-bold text-sm">
                                                {{ __('front/homePage.Under Reviewing') }}
                                            </span>
                                        @else
                                            <div class="flex flex-wrap items-center gap-3 justify-around">
                                                {{-- Quantity : Start --}}
                                                <div
                                                    class="flex flex-col items-center justify-center gap-1 bg-yellow-600 p-1 rounded">
                                                    <div class="text-white text-xs font-bold">
                                                        {{ __('admin/productsPages.Available Quantity') }}
                                                    </div>
                                                    <div
                                                        class="text-gray-900 bg-white p-1 rounded w-full text-center text-xs font-bold">
                                                        {{ $product['quantity'] ?? '00' }}
                                                    </div>
                                                </div>
                                                {{-- Quantity : End --}}

                                                {{-- Original Price : Start --}}
                                                <div
                                                    class="flex flex-col items-center justify-center gap-1 bg-secondary p-1 rounded">
                                                    <div class="text-white text-xs font-bold">
                                                        {{ __('admin/productsPages.Original Price') }}
                                                    </div>
                                                    <div
                                                        class="flex rtl:flex-row-reverse items-center justify-center w-full gap-1 text-gray-900 bg-white p-1 rounded">
                                                        <span class="font-bold text-xs">
                                                            {{ __('front/homePage.EGP') }}
                                                        </span>
                                                        <span class="font-bold text-sm" dir="ltr">
                                                            {{ number_format(explode('.', $product['original_price'])[0], 0, '.', '\'') }}
                                                        </span>
                                                        <span class="text-xs font-bold">
                                                            {{ explode('.', $product['original_price'])[1] ?? '00' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                {{-- Original Price : End --}}

                                                {{-- Base Price : Start --}}
                                                <div
                                                    class="flex flex-col items-center justify-center gap-1 bg-primary p-1 rounded">
                                                    <div class="text-white text-xs font-bold">
                                                        {{ __('admin/productsPages.Base Price') }}
                                                    </div>
                                                    <div
                                                        class="flex rtl:flex-row-reverse items-center justify-center w-full gap-1 text-gray-900 bg-white p-1 rounded">
                                                        <span class="font-bold text-xs">
                                                            {{ __('front/homePage.EGP') }}
                                                        </span>
                                                        <span class="font-bold text-sm" dir="ltr">
                                                            {{ number_format(explode('.', $product['base_price'])[0], 0, '.', '\'') }}
                                                        </span>
                                                        <span class="text-xs font-bold">
                                                            {{ explode('.', $product['base_price'])[1] ?? '00' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                {{-- Base Price : End --}}

                                                {{-- Final Price : Start --}}
                                                <div
                                                    class="flex flex-col items-center justify-center gap-1 bg-successDark p-1 rounded">
                                                    <div class="text-white text-xs font-bold">
                                                        {{ __('admin/productsPages.Final Price') }}
                                                    </div>
                                                    <div
                                                        class="flex rtl:flex-row-reverse items-center justify-center w-full gap-1 text-gray-900 bg-white p-1 rounded">
                                                        <span class="font-bold text-xs">
                                                            {{ __('front/homePage.EGP') }}
                                                        </span>
                                                        <span class="font-bold text-sm" dir="ltr">
                                                            {{ number_format(explode('.', $product['final_price'])[0], 0, '.', '\'') }}
                                                        </span>
                                                        <span class="text-xs font-bold">
                                                            {{ explode('.', $product['final_price'])[1] ?? '00' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                {{-- Final Price : End --}}
                                            </div>
                                        @endif

                                        <div class="flex justify-center items-center gap-1 w-32">
                                            {{-- Add :: Start --}}
                                            <button
                                                class="w-6 h-6 rounded-circle bg-secondary text-white flex justify-center items-center"
                                                title="{{ __('front/homePage.Increase') }}"
                                                wire:click="amountUpdated('{{ $product['id'] }}',{{ $product['amount'] + 1 }})">
                                                <span class="material-icons text-xs">
                                                    add
                                                </span>
                                            </button>
                                            {{-- Add :: End --}}

                                            {{-- Amount :: Start --}}
                                            <input type="text" dir="ltr"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                                class="focus:ring-primary focus:border-primary flex-1 block w-full min-w-maxs rounded text-xs border-gray-300 text-center text-gray-700 px-1 p-2"
                                                value="{{ $product['amount'] }}"
                                                wire:change="amountUpdated('{{ $product['id'] }}',$event.target.value)">
                                            {{-- Amount :: End --}}

                                            {{-- Remove :: Start --}}
                                            <button
                                                class="w-6 h-6 rounded-circle bg-secondary text-white flex justify-center items-center"
                                                wire:key="DecreaseByOne-{{ rand() }}"
                                                title="{{ __('front/homePage.Decrease') }}"
                                                wire:click="amountUpdated('{{ $product['id'] }}',{{ $product['amount'] - 1 }})">
                                                <span class="material-icons text-xs">
                                                    remove
                                                </span>
                                            </button>
                                            {{-- Remove :: End --}}

                                            {{-- Delete :: Start --}}
                                            <button title="{{ __('front/homePage.Remove from Cart') }}"
                                                class="w-6 h-6 rounded-circle bg-white border border-primary text-primary transition ease-in-out hover:bg-primary hover:text-white flex justify-center items-center"
                                                wire:click="amountUpdated('{{ $product['id'] }}',0)">
                                                <span class="material-icons text-xs">
                                                    delete
                                                </span>
                                            </button>
                                            {{-- Delete :: End --}}
                                        </div>

                                    </div>
                                    {{-- Product Price : End --}}
                                </div>
                            </div>
                        </div>
                        {{-- Product : End --}}
                    @empty
                    @endforelse
                </div>
                {{-- Product Info :: End --}}
            @endif
            {{-- Product Selected :: End --}}

            @error('products')
                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}
                </div>
            @enderror

        </div>
        {{-- Collection's Products :: End --}}
        {{-- ######################################################### --}}
        {{-- ######################################################### --}}

        {{-- ######################################################### --}}
        {{-- ######################################################### --}}
        {{-- Collection Information :: Start --}}
        <div class="grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-gray-100 p-4 text-center rounded shadow">

            <div class="col-span-12 font-bold text-black mb-2">
                {{ __('admin/productsPages.Collection Information') }}
            </div>

            {{-- Name :: Start --}}
            <div class="col-span-12 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center rounded text-center">
                <label for="name"
                    class="col-span-12 md:col-span-2 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Name') }}</label>
                {{-- Name Ar --}}
                <div class="col-span-6 md:col-span-5">
                    <input
                        class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('name.ar') border-red-900 border-2 @enderror"
                        type="text" wire:model.live.blur="name.ar" id="name" dir="rtl"
                        placeholder="{{ __('admin/productsPages.in Arabic') }}" maxlength="100" required>
                    @error('name.ar')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
                {{-- Name En --}}
                <div class="col-span-6 md:col-span-5 ">
                    <input
                        class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('name.en') border-red-900 border-2 @enderror"
                        type="text" wire:model.live.blur="name.en" dir="ltr"
                        placeholder="{{ __('admin/productsPages.in English') }}" maxlength="100">
                    @error('name.en')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Name :: End --}}

            {{-- Model :: Start --}}
            <div class="col-span-6 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full">
                <label for="model"
                    class="col-span-3 sm:col-span-1 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Model') }}</label>
                <div class="col-span-3 sm:col-span-2">
                    <input id="model"
                        class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('model') border-red-900 border-2 @enderror"
                        type="text" wire:model.live.blur="model" placeholder="{{ __('admin/productsPages.Model') }}"
                        maxlength="100">

                    @error('model')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Model :: End --}}

            {{-- Barcode :: Start --}}
            <div class="col-span-6 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full">
                <label for="barcode"
                    class="col-span-3 sm:col-span-1 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Barcode') }}</label>
                <div class="col-span-3 sm:col-span-2">
                    <input id="barcode"
                        class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('barcode') border-red-900 border-2 @enderror"
                        type="text" wire:model.live.blur="barcode"
                        placeholder="{{ __('admin/productsPages.Barcode') }}" maxlength="200">

                    @error('barcode')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Barcode :: End --}}

            {{-- Weight :: Start --}}
            <div class="col-span-6 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full">
                <label for="weight"
                    class="col-span-3 sm:col-span-1 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Weight') }}</label>
                <div class="col-span-3 sm:col-span-2">
                    <input
                        class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('weight') border-red-900 border-2 @enderror"
                        type="text" wire:model.live.blur="weight" id="weight"
                        placeholder="{{ __('admin/productsPages.Weight in Kg.') }}">

                    @error('weight')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Weight :: End --}}

            {{-- Publish :: Start --}}
            <div
                class="col-span-6 md:col-span-3 md:col-start-4 lg:col-span-6 lg:col-start-1 grid grid-cols-2 gap-y-2 gap-x-2 items-center w-full">
                <label wire:click="publish"
                    class="col-span-1 lg:col-span-2 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Publish') }}</label>
                <div class="col-span-1 lg:col-span-2">
                    {!! $publish
                        ? '<span class="block cursor-pointer material-icons text-success select-none" wire:click="publish">toggle_on</span>'
                        : '<span class="block cursor-pointer material-icons text-red-600 select-none" wire:click="publish">toggle_off</span>' !!}

                    @error('publish')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>

            </div>
            {{-- Publish :: End --}}

            {{-- Refundable :: Start --}}
            <div class="col-span-6 md:col-span-3 lg:col-span-6 grid grid-cols-2 gap-y-2 items-center w-full">
                <label wire:click="refund"
                    class="col-span-1 lg:col-span-2 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Refundable') }}</label>
                <div class="col-span-1 lg:col-span-2">
                    {!! $refundable
                        ? '<span class="block cursor-pointer material-icons text-success select-none" wire:click="refund">toggle_on</span>'
                        : '<span class="block cursor-pointer material-icons text-red-600 select-none" wire:click="refund">toggle_off</span>' !!}

                    @error('refundable')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Refundable :: End --}}

            {{-- Description :: Start --}}
            <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 items-center w-full">
                <label
                    class="col-span-12 md:col-span-2 font-bold m-0 text-center font-bold text-xs text-gray-700">{{ __('admin/productsPages.Description') }}</label>

                <div class="col-span-12 md:col-span-10 grid grid-cols-3 gap-3">
                    {{-- Description Ar --}}
                    <div class="col-span-6 md:col-span-5">
                        <div wire:ignore
                            class="py-1 w-full px-6 rounded text-right border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 cursor-text @error('description.ar') border-red-900 border-2 @enderror"
                            type="text" id="description_ar"
                            placeholder="{{ __('admin/productsPages.in Arabic') }}">
                            @if ($description['ar'])
                                {!! $description['ar'] !!}
                            @else
                                <ul class="list-disc">
                                    <li></li>
                                </ul>
                            @endif
                        </div>

                        @error('description.ar')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Description En --}}
                    <div wire:ignore class="col-span-6 md:col-span-5 ">
                        <div class="py-1 w-full px-6 rounded text-left border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 cursor-text @error('description.en') border-red-900 border-2 @enderror"
                            type="text" id="description_en"
                            placeholder="{{ __('admin/productsPages.in English') }}">
                            @if ($description['en'])
                                {!! $description['en'] !!}
                            @else
                                <ul class="list-disc">
                                    <li></li>
                                </ul>
                            @endif
                        </div>
                    </div>

                    @error('description.en')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            {{-- Description :: End --}}

            {{-- Specifications :: :: Start --}}
            <div class="col-span-12 bg-gray-200 w-full rounded-xl p-2 flex flex-col justify-center items-center gap-2">
                @foreach ($specs as $key => $spec)
                    <div class="grid grid-cols-12 gap-2 bg-gray-300 rounded-xl p-2" wire:key="{{ 'spec-' . $key }}">
                        <div class="col-span-11 flex flex-col gap-1">
                            <div class="grid grid-cols-6 justify-center items-center gap-1">
                                <input wire:model.live.blur="specs.{{ $key }}.ar.title"
                                    placeholder="{{ __('admin/productsPages.Title (ar)') }}" type="text"
                                    class="col-span-2 py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('') border-red-900 border-2 @enderror">
                                <input wire:model.live.blur="specs.{{ $key }}.ar.value"
                                    placeholder="{{ __('admin/productsPages.Value (ar)') }}" type="text"
                                    class="col-span-4 py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('') border-red-900 border-2 @enderror">
                            </div>
                            <div class="grid grid-cols-6 justify-center items-center gap-1">
                                <input wire:model.live.blur="specs.{{ $key }}.en.title"
                                    placeholder="{{ __('admin/productsPages.Title (en)') }}" type="text"
                                    dir="ltr"
                                    class="col-span-2 py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('') border-red-900 border-2 @enderror">
                                <input wire:model.live.blur="specs.{{ $key }}.en.value"
                                    placeholder="{{ __('admin/productsPages.Value (en)') }}" type="text"
                                    dir="ltr"
                                    class="col-span-4 py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('') border-red-900 border-2 @enderror">
                            </div>
                        </div>
                        <div class="col-span-1 flex flex-col justify-center items-center gap-1">
                            <a href="#" wire:click.prevent="deleteSpec({{ $key }})"
                                class="box-content inline-flex justify-center items-center w-6 h-6 bg-white hover:bg-white focus:bg-white active:bg-white font-bold rounded-full border-4 border-gray-700">
                                <span class="material-icons text-red-500 text-xs font-bold shadow-xl">
                                    remove
                                </span>
                            </a>
                        </div>
                    </div>
                @endforeach

                {{-- Add New Specification --}}
                <div class="text-center col-span-6">
                    <a href="#" wire:click.prevent="addSpec"
                        class="btn btn-sm bg-success hover:bg-successDark focus:bg-success active:bg-success font-bold">
                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                            add
                        </span>
                        {{ __('admin/productsPages.Add Specification') }}</a>
                </div>
                {{-- Add New Specification --}}

            </div>
        </div>
        {{-- Collection Information :: End --}}
        {{-- ######################################################### --}}
        {{-- ######################################################### --}}

        {{-- ######################################################### --}}
        {{-- ######################################################### --}}
        {{-- Complementary Products Start --}}
        <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-red-100 p-4 text-center rounded shadow">

            <div class="col-span-12 font-bold text-black mb-2">
                {{ __('admin/productsPages.Complementary Products') }}
            </div>

            {{-- Add/Clear Complementary Product :: Start --}}
            <div class="col-span-12 flex items-center justify-around">
                <button wire:click="$dispatchTo('admin.products.product-list-popup','show',{modalName:'complementary-items-list'})"
                    class="btn btn-sm bg-success hover:bg-successDark focus:bg-success active:bg-success font-bold">
                    <span class="material-icons rtl:ml-1 ltr:mr-1">
                        add
                    </span>
                    {{ __('admin/productsPages.Add Products') }}
                </button>

                @if (count($complementaryItems))
                    <button wire:click="clearComplementaryProducts"
                        class="btn btn-sm bg-red-500 hover:bg-red-700 focus:bg-red-700 active:bg-red-700 font-bold">
                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                            close
                        </span>
                        {{ __('admin/productsPages.Clear Products') }}
                    </button>
                @endif
            </div>
            {{-- Add/Clear Complementary Product :: End --}}

            {{-- Control Ranking :: Start --}}
            <div class="col-span-12 flex items-center justify-around">
                @if (count($complementaryItems))
                    {{-- Clean Ranking --}}
                    <button wire:click="cleanComplementaryRanking"
                        class="btn btn-sm bg-info hover:bg-infoDark focus:bg-infoDark active:bg-infoDark font-bold">
                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                            sanitizer
                        </span>
                        {{ __('admin/productsPages.Clean Ranking') }}
                    </button>

                    {{-- Reset Ranking --}}
                    <button wire:click="resetComplementaryRank"
                        class="btn btn-sm bg-warningDark hover:bg-warningDarker focus:bg-warningDarker active:bg-warningDarker font-bold">
                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                            restart_alt
                        </span>
                        {{ __('admin/productsPages.Reset Ranking') }}
                    </button>
                @endif
            </div>
            {{-- Control Ranking :: End --}}

            {{-- Selected Product :: Start --}}
            @if (count($complementaryItems))
                <hr class="col-span-12 ">

                {{-- Product Info :: Start --}}
                <div class="col-span-12 grid grid-cols-10 justify-center items-center gap-2">
                    @foreach ($complementaryItems as $key => $complementaryItem)
                        {{-- Product : Start --}}
                        <div class="col-span-10 lg:col-span-5 p-4 w-full relative bg-white rounded shadow max-w-100 overflow-hidden"
                            wire:key='product-{{ $complementaryItem['id'] }}-{{ rand() }}'>
                            <div class="flex gap-6 justify-start items-center">
                                {{-- Thumnail :: Start --}}
                                @if ($complementaryItem['type'] == 'Product')
                                    <a href="{{ route('front.products.show', ['id' => $complementaryItem['id'], 'slug' => $complementaryItem['slug'][session('locale')]]) }}"
                                        target="_blank" class="min-w-max block hover:text-current">
                                        @if ($complementaryItem['thumbnail'])
                                            <img class="w-full h-full flex justify-center items-center bg-gray-200 rounded overflow-hidden"
                                                src="{{ asset('storage/images/products/cropped100/' . $complementaryItem['thumbnail']['file_name']) }}"
                                                alt="{{ $complementaryItem['name'][session('locale')] . ' image' }}">
                                        @else
                                            <div
                                                class="w-full h-full flex justify-center items-center bg-gray-200 rounded">
                                                <span class="block material-icons text-8xl">
                                                    construction
                                                </span>
                                            </div>
                                        @endif
                                    </a>
                                @elseif($complementaryItem['type'] == 'Collection')
                                    <a href="{{ route('front.collections.show', ['id' => $complementaryItem['id'], 'slug' => $complementaryItem['slug'][session('locale')]]) }}"
                                        target="_blank" class="min-w-max block hover:text-current">
                                        @if ($complementaryItem['thumbnail'])
                                            <img class="w-full h-full flex justify-center items-center bg-gray-200 rounded overflow-hidden"
                                                src="{{ asset('storage/images/collections/cropped100/' . $complementaryItem['thumbnail']['file_name']) }}"
                                                alt="{{ $complementaryItem['name'][session('locale')] . ' image' }}">
                                        @else
                                            <div
                                                class="w-full h-full flex justify-center items-center bg-gray-200 rounded">
                                                <span class="block material-icons text-8xl">
                                                    construction
                                                </span>
                                            </div>
                                        @endif
                                    </a>
                                @endif
                                {{-- Thumnail :: End --}}

                                <div class="flex gap-6 justify-between items-center w-full max-w-100">
                                    {{-- Product Info : Start --}}
                                    <div class="grow flex flex-col justify-start gap-2">
                                        {{-- Product's Brand :: Start --}}
                                        @isset($complementaryItem['brand'])
                                            <div class="flex items-center">
                                                <a href="{{ route('front.brands.show', ['brand' => $complementaryItem['brand']['id']]) }}"
                                                    class="text-xs font-bold text-gray-400 hover:text-current">
                                                    {{ $complementaryItem['brand']['name'] }}
                                                </a>
                                            </div>
                                        @endisset
                                        {{-- Product's Brand :: End --}}

                                        {{-- Product Name : Start --}}
                                        <div class="flex justify-start items-center text-left">
                                            @if ($complementaryItem['type'] == 'Product')
                                                <a href="{{ route('front.products.show', ['id' => $complementaryItem['id'], 'slug' => $complementaryItem['slug'][session('locale')]]) }}"
                                                    target="_blank" class="font-bold hover:text-current">
                                                    <span class="max-w-full block text-left rtl:text-right">
                                                        {{ $complementaryItem['name'][session('locale')] }}
                                                    </span>
                                                </a>
                                            @elseif($complementaryItem['type'] == 'Collection')
                                                <a href="{{ route('front.collections.show', ['id' => $complementaryItem['id'], 'slug' => $complementaryItem['slug'][session('locale')]]) }}"
                                                    target="_blank" class="font-bold hover:text-current">
                                                    <span class="max-w-full block text-left rtl:text-right">
                                                        {{ $complementaryItem['name'][session('locale')] }}
                                                    </span>
                                                </a>
                                            @endif
                                        </div>
                                        {{-- Product Name : End --}}

                                        {{-- Product's Rank :: Start --}}
                                        <div class="flex items-center justify-center gap-2">
                                            <label for="" class="m-0 text-xs font-bold">Rank</label>
                                            <input
                                                class="py-1 w-12 rounded-circle text-center select-none cursor-pointer focus:outline-success focus:ring-success focus:border-success @if ($complementaryItems[$key]['pivot']['rank'] == 0) border-danger border-2 @else border-success border-2 @endif"
                                                type="number" readonly
                                                wire:click="editComplementaryRank({{ $key }})"
                                                wire:model.live="complementaryItems.{{ $key }}.pivot.rank">
                                        </div>
                                        {{-- Product's Rank :: End --}}

                                    </div>
                                    {{-- Product Info : End --}}
                                </div>
                            </div>
                            <div class="absolute top-2 right-2 rtl:right-auto rtl:left-2">
                                <button
                                    wire:click="deleteComplementaryProduct({{ $complementaryItem['id'] }},'{{ $complementaryItem['type'] }}')"
                                    class="material-icons bg-red-500 p-1 w-6 h-6 text-white text-xs font-bold shadow-xl rounded-circle"
                                    title="Delete Product">
                                    close
                                </button>
                            </div>
                        </div>
                        {{-- Product : End --}}
                    @endforeach

                </div>
                {{-- Product Info :: End --}}
            @endif
            {{-- Selected Product :: End --}}

            @error('complementaryItems.*.pivot.rank')
                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1 max-w-2xl">
                    {{ $message }}
                </div>
            @enderror

            @error('complementaryItems')
                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}
                </div>
            @enderror
            {{-- Search Product/Collection End --}}
        </div>
        {{-- Complementary Products End --}}
        {{-- ######################################################### --}}
        {{-- ######################################################### --}}

        {{-- ######################################################### --}}
        {{-- ######################################################### --}}
        {{-- Related Products Start --}}
        <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-gray-100 p-4 text-center rounded shadow">

            <div class="col-span-12 font-bold text-black mb-2">
                {{ __('admin/productsPages.Related Products') }}
            </div>

            {{-- Add/Clear Related Product :: Start --}}
            <div class="col-span-12 flex items-center justify-around">
                <button wire:click="$dispatchTo('admin.products.product-list-popup','show',{modalName:'related-items-list'})"
                    class="btn btn-sm bg-success hover:bg-successDark focus:bg-success active:bg-success font-bold">
                    <span class="material-icons rtl:ml-1 ltr:mr-1">
                        add
                    </span>
                    {{ __('admin/productsPages.Add Products') }}
                </button>

                @if (count($relatedItems))
                    <button wire:click="clearRelatedProducts"
                        class="btn btn-sm bg-red-500 hover:bg-red-700 focus:bg-red-700 active:bg-red-700 font-bold">
                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                            close
                        </span>
                        {{ __('admin/productsPages.Clear Products') }}
                    </button>
                @endif
            </div>
            {{-- Add/Clear Related Product :: End --}}

            {{-- Control Ranking :: Start --}}
            <div class="col-span-12 flex items-center justify-around">
                @if (count($relatedItems))
                    {{-- Clean Ranking --}}
                    <button wire:click="cleanRelatedRanking"
                        class="btn btn-sm bg-info hover:bg-infoDark focus:bg-infoDark active:bg-infoDark font-bold">
                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                            sanitizer
                        </span>
                        {{ __('admin/productsPages.Clean Ranking') }}
                    </button>

                    {{-- Reset Ranking --}}
                    <button wire:click="resetRelatedRank"
                        class="btn btn-sm bg-warningDark hover:bg-warningDarker focus:bg-warningDarker active:bg-warningDarker font-bold">
                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                            restart_alt
                        </span>
                        {{ __('admin/productsPages.Reset Ranking') }}
                    </button>
                @endif
            </div>
            {{-- Control Ranking :: End --}}

            {{-- Selected Product :: Start --}}
            @if (count($relatedItems))
                <hr class="col-span-12 ">

                {{-- Product Info :: Start --}}
                <div class="col-span-12 grid grid-cols-10 justify-center items-center gap-2">
                    @foreach ($relatedItems as $key => $relatedItem)
                        {{-- Product : Start --}}
                        <div class="col-span-10 lg:col-span-5 p-4 w-full relative bg-white rounded shadow max-w-100 overflow-hidden"
                            wire:key='product-{{ $relatedItem['id'] }}-{{ rand() }}'>
                            <div class="flex gap-6 justify-start items-center">
                                {{-- Thumnail :: Start --}}
                                @if ($relatedItem['type'] == 'Product')
                                    <a href="{{ route('front.products.show', ['id' => $relatedItem['id'], 'slug' => $relatedItem['slug'][session('locale')]]) }}"
                                        target="_blank" class="min-w-max block hover:text-current">
                                        @if ($relatedItem['thumbnail'])
                                            <img class="w-full h-full flex justify-center items-center bg-gray-200 rounded overflow-hidden"
                                                src="{{ asset('storage/images/products/cropped100/' . $relatedItem['thumbnail']['file_name']) }}"
                                                alt="{{ $relatedItem['name'][session('locale')] . ' image' }}">
                                        @else
                                            <div
                                                class="w-full h-full flex justify-center items-center bg-gray-200 rounded">
                                                <span class="block material-icons text-8xl">
                                                    construction
                                                </span>
                                            </div>
                                        @endif
                                    </a>
                                @elseif($relatedItem['type'] == 'Collection')
                                    <a href="{{ route('front.collections.show', ['id' => $relatedItem['id'], 'slug' => $relatedItem['slug'][session('locale')]]) }}"
                                        target="_blank" class="min-w-max block hover:text-current">
                                        @if ($relatedItem['thumbnail'])
                                            <img class="w-full h-full flex justify-center items-center bg-gray-200 rounded overflow-hidden"
                                                src="{{ asset('storage/images/collections/cropped100/' . $relatedItem['thumbnail']['file_name']) }}"
                                                alt="{{ $relatedItem['name'][session('locale')] . ' image' }}">
                                        @else
                                            <div
                                                class="w-full h-full flex justify-center items-center bg-gray-200 rounded">
                                                <span class="block material-icons text-8xl">
                                                    construction
                                                </span>
                                            </div>
                                        @endif
                                    </a>
                                @endif
                                {{-- Thumnail :: End --}}

                                <div class="flex gap-6 justify-between items-center w-full max-w-100">
                                    {{-- Product Info : Start --}}
                                    <div class="grow flex flex-col justify-start gap-2">
                                        {{-- Product's Brand :: Start --}}
                                        @isset($relatedItem['brand'])
                                            <div class="flex items-center">
                                                <a href="{{ route('front.brands.show', ['brand' => $relatedItem['brand']['id']]) }}"
                                                    class="text-xs font-bold text-gray-400 hover:text-current">
                                                    {{ $relatedItem['brand']['name'] }}
                                                </a>
                                            </div>
                                        @endisset
                                        {{-- Product's Brand :: End --}}

                                        {{-- Product Name : Start --}}
                                        <div class="flex justify-start items-center text-left">
                                            @if ($relatedItem['type'] == 'Product')
                                                <a href="{{ route('front.products.show', ['id' => $relatedItem['id'], 'slug' => $relatedItem['slug'][session('locale')]]) }}"
                                                    target="_blank" class="font-bold hover:text-current">
                                                    <span class="max-w-full block text-left rtl:text-right">
                                                        {{ $relatedItem['name'][session('locale')] }}
                                                    </span>
                                                </a>
                                            @elseif($relatedItem['type'] == 'Collection')
                                                <a href="{{ route('front.collections.show', ['id' => $relatedItem['id'], 'slug' => $relatedItem['slug'][session('locale')]]) }}"
                                                    target="_blank" class="font-bold hover:text-current">
                                                    <span class="max-w-full block text-left rtl:text-right">
                                                        {{ $relatedItem['name'][session('locale')] }}
                                                    </span>
                                                </a>
                                            @endif
                                        </div>
                                        {{-- Product Name : End --}}

                                        {{-- Product's Rank :: Start --}}
                                        <div class="flex items-center justify-center gap-2">
                                            <label for="" class="m-0 text-xs font-bold">Rank</label>
                                            <input
                                                class="py-1 w-12 rounded-circle text-center select-none cursor-pointer focus:outline-success focus:ring-success focus:border-success @if ($relatedItems[$key]['pivot']['rank'] == 0) border-danger border-2 @else border-success border-2 @endif"
                                                type="number" readonly
                                                wire:click="editRelatedRank({{ $key }})"
                                                wire:model.live="relatedItems.{{ $key }}.pivot.rank">
                                        </div>
                                        {{-- Product's Rank :: End --}}
                                    </div>
                                    {{-- Product Info : End --}}
                                </div>
                            </div>
                            <div class="absolute top-2 right-2 rtl:right-auto rtl:left-2">
                                <button
                                    wire:click="deleteRelatedProduct({{ $relatedItem['id'] }},'{{ $relatedItem['type'] }}')"
                                    class="material-icons bg-red-500 p-1 w-6 h-6 text-white text-xs font-bold shadow-xl rounded-circle"
                                    title="Delete Product">
                                    close
                                </button>
                            </div>
                        </div>
                        {{-- Product : End --}}
                    @endforeach
                </div>
                {{-- Product Info :: End --}}
            @endif
            {{-- Selected Product :: End --}}

            @error('relatedItems.*.pivot.rank')
                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1 max-w-2xl">
                    {{ $message }}
                </div>
            @enderror

            @error('relatedItems')
                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}
                </div>
            @enderror
            {{-- Search Product/Collection End --}}
        </div>
        {{-- Related Products End --}}
        {{-- ######################################################### --}}
        {{-- ######################################################### --}}

    </div>

    {{-- Big Side :: End --}}

    {{-- Small Side :: Start --}}
    <div class="col-span-12 lg:col-span-4 w-full grid gap-3">

        {{-- Pricing and Stock :: Start --}}
        <div class="grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-red-100 p-4 text-center rounded shadow">

            <div class="col-span-12 font-bold text-black mb-2">{{ __('admin/productsPages.Pricing') }}
            </div>

            {{-- Original Price :: Start --}}
            <div
                class="col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-6 w-full grid grid-cols-3 gap-x-4 gap-y-2 items-center rounded text-center">
                <label for="original_price"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Original Price') }}</label>

                <div class="col-span-3">
                    <input dir="ltr"
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('original_price') border-red-900 border-2 @enderror"
                        type="text" wire:model.live.blur="original_price" id="original_price"
                        placeholder="{{ __('admin/productsPages.EGP') }}" required disabled>
                    @error('original_price')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Original Price :: End --}}

            {{-- Profit margin :: Start --}}
            <div
                class="col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-6 w-full grid grid-cols-3 gap-x-4 gap-y-2 items-center rounded text-center">
                <label for="profit_margin"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Profit Margin') }}</label>

                <div class="col-span-3">
                    <input dir="ltr"
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('profit_margin') border-red-900 border-2 @enderror"
                        type="text" wire:model.live.blur="profit_margin" id="profit_margin"
                        placeholder="{{ __('admin/productsPages.EGP') }}" required disabled>
                    @error('profit_margin')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Profit margin :: End --}}

            {{-- Base Price :: Start --}}
            <div
                class="col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-6 w-full grid grid-cols-3 gap-x-4 gap-y-2 items-center rounded text-center">
                <label for="base_price"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Base Price') }}</label>

                <div class="col-span-3">
                    <input dir="ltr"
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('base_price') border-red-900 border-2 @enderror"
                        type="text" wire:model.live.blur="base_price" id="base_price"
                        placeholder="{{ __('admin/productsPages.EGP') }}" required disabled>
                    @error('base_price')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Base Price :: End --}}

            {{-- Discount :: Start --}}
            <div
                class="col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-6 w-full grid grid-cols-3 gap-x-6 gap-y-2 items-center rounded text-center">
                <label for="discount"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Discount') }}</label>

                <div class="col-span-3">
                    <input dir="ltr"
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('discount') border-red-900 border-2 @enderror"
                        type="text" wire:model.live.blur="discount" id="discount"
                        placeholder="{{ __('admin/productsPages.Percentage') }}">
                    @error('discount')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Discount :: Start --}}

            {{-- Final Price :: Start --}}
            <div
                class="col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-12 w-full grid grid-cols-3 gap-x-6 gap-y-2 items-center rounded text-center">
                <label for="final_price"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Final Price') }}</label>

                <div class="col-span-3">
                    <input dir="ltr"
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('final_price') border-red-900 border-2 @enderror"
                        type="text" wire:model.live.blur="final_price" id="final_price"
                        placeholder="{{ __('admin/productsPages.EGP') }}">
                    @error('final_price')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Final Price :: End --}}

            {{-- Points :: Start --}}
            <div
                class="col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-6 w-full grid grid-cols-3 gap-x-6 gap-y-2 items-center rounded text-center">
                <label for="points"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Points') }}</label>

                <div class="col-span-3">
                    <input
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('points') border-red-900 border-2 @enderror"
                        type="text" wire:model.live.blur="points" id="points"
                        placeholder="{{ __('admin/productsPages.Points') }}">
                    @error('points')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Points :: End --}}

            {{-- Free Shipping:: Start --}}
            <div
                class="col-span-6 sm:col-span-6 md:col-span-3 lg:col-span-3 w-full grid grid-cols-3 gap-x-6 gap-y-1 items-center rounded text-center">
                <label for="free_shipping" wire:click="free_shipping"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer select-none">{{ __('admin/productsPages.Free Shipping') }}</label>

                <div class="col-span-3">
                    <div class="col-span-2 md:col-span-1">
                        {!! $free_shipping
                            ? '<span class="block cursor-pointer material-icons text-success select-none" wire:click="free_shipping">toggle_on</span>'
                            : '<span class="block cursor-pointer material-icons text-red-600 select-none" wire:click="free_shipping">toggle_off</span>' !!}

                        @error('free_shipping')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            {{-- Free Shipping:: End --}}

            {{-- under reviewing :: Start --}}
            <div
                class="col-span-6 sm:col-span-6 md:col-span-3 lg:col-span-3 w-full grid grid-cols-3 gap-x-6 gap-y-1 items-center rounded text-center">
                <label for="reviewing" wire:click="reviewing"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer select-none">{{ __('admin/productsPages.Under Reviewing') }}</label>

                <div class="col-span-3">
                    <div class="col-span-2 md:col-span-1">
                        {!! $reviewing
                            ? '<span class="block cursor-pointer material-icons text-success select-none" wire:click="reviewing">toggle_on</span>'
                            : '<span class="block cursor-pointer material-icons text-red-600 select-none" wire:click="reviewing">toggle_off</span>' !!}

                        @error('reviewing')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            {{-- under reviewing :: End --}}

        </div>
        {{-- Pricing and Stock :: End --}}

        {{-- SEO :: Start --}}
        <div class="grid grid-cols-3 gap-x-6 gap-y-2 items-center bg-gray-100 p-4 text-center  rounded shadow">

            <div class="col-span-3 font-bold text-black mb-2">{{ __('admin/productsPages.SEO') }}</div>


            {{-- SEO Keywords :: :: Start --}}
            <div class="col-span-3 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center rounded text-center">
                <label for="seo_keywords"
                    class="col-span-12 sm:col-span-2 md:col-start-3 lg:col-span-12 lg:col-start-1 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">
                    {{ __('admin/productsPages.Keywords') }}
                </label>

                <div class="col-span-12 sm:col-span-10 md:col-span-6 lg:col-span-12">
                    <textarea wire:model.live.blur="seo_keywords" placeholder="{{ __('admin/productsPages.Keywords ("Comma Separated")') }}"
                        class="py-1 w-full px-6 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 cursor-text @error('seo_keywords') border-red-900 border-2 @enderror"
                        type="text" id="seo_keywords">
                    </textarea>

                    @error('seo_keywords')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            {{-- SEO Keywords :: :: End --}}
        </div>
        {{-- SEO :: End --}}
    </div>
    {{-- Small Side :: End --}}

    {{-- Buttons Section :: Start --}}
    <div class="col-span-12 w-full flex flex-wrap mt-2 justify-around">
        @if ($collection_id != null)
            <button type="button" wire:click.prevent="update"
                class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/productsPages.Update') }}</button>
        @else
            {{-- Save and Back --}}
            <button type="button" wire:click.prevent="save"
                class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/productsPages.Save') }}</button>
            {{-- Save and New --}}
            <button type="button" wire:click.prevent="save('true')"
                class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/productsPages.Save and Add New Collection') }}</button>
        @endif
        {{-- Back --}}
        <a href="{{ url()->previous() }}"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/productsPages.Back') }}</a>

    </div>
    {{-- Buttons Section :: End --}}

    {{-- Modals Section Start --}}
    @livewire('admin.products.product-list-popup', [
        'modalName' => 'complementary-items-list',
    ])

    @livewire('admin.products.product-list-popup', [
        'modalName' => 'related-items-list',
    ])
    {{-- Modals Section End --}}
</div>
