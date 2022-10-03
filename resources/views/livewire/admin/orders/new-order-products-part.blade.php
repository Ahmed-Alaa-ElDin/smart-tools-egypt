<div class="bg-red-50 p-2 rounded-xl shadow">
    <x-admin.waiting />

    <div class="text-center mb-2 font-bold text-red-900">
        {{ __('admin/ordersPages.Products Choosing') }}
    </div>
    <div class="flex flex-wrap-reverse justify-around items-center gap-3 ">
        <div class="relative w-full md:w-auto md:min-w-[50%]">

            {{-- Search Product Input :: Start --}}
            <div class="flex rounded-md shadow-sm">
                <span
                    class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-primary bg-primary text-center text-white text-sm">
                    <span class="material-icons">
                        search
                    </span>
                </span>
                <input type="text" wire:model.debounce.500ms='search' wire:keydown.Escape="$set('search','')"
                    data-name="new-order-products-part"
                    class="searchInput focus:ring-0 flex-1 block rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-primary"
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
                    @forelse ($products_list as $product)
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

                                {{-- Points --}}
                                <span class="bg-yellow-600 px-2 py-1 rounded text-white">
                                    {{ $product->points ?? 0 }}
                                </span>
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
            <div>
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
    </div>

    {{-- Product Selected :: Start --}}
    @if (count($products))
        <hr class="my-2">

        {{-- Product Info :: Start --}}
        <div class="flex flex-col justify-center items-center gap-2">
            @forelse ($products as $product)
                {{-- Product : Start --}}
                <div class="p-4 scrollbar scrollbar-thin w-full bg-white rounded shadow"
                    wire:key='product-{{ $product['id'] }}-{{ rand() }}'>
                    <div class="flex gap-6 justify-start items-center">
                        {{-- Thumnail :: Start --}}
                        <a href="{{ route('front.product.show', ['id' => $product['id'], 'slug' => $product['slug'][session('locale')]]) }}"
                            target="_blank" class="min-w-max block hover:text-current">
                            @if ($product['thumbnail'])
                                <img class="w-full h-full flex justify-center items-center bg-gray-200 rounded overflow-hidden"
                                    src="{{ asset('storage/images/products/cropped100/' . $product['thumbnail']['file_name']) }}"
                                    alt="{{ $product['name'][session('locale')] . 'image' }}">
                            @else
                                <div class="w-full h-full flex justify-center items-center bg-gray-200 rounded">
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
                                {{-- todo :: brand link --}}
                                <div class="flex items-center">
                                    <a href="#" class="text-sm font-bold text-gray-400 hover:text-current">
                                        {{ $product['brand'] ? $product['brand']['name'] : '' }}
                                    </a>
                                </div>
                                {{-- Product's Brand :: End --}}

                                {{-- Product Name : Start --}}
                                <div class="flex items-center">
                                    <a href="{{ route('front.product.show', ['id' => $product['id'], 'slug' => $product['slug'][session('locale')]]) }}"
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

                                    <span class="text-sm text-gray-600">({{ $product['reviews_count'] ?? 0 }})</span>
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
                                    <div class="flex flex-col md:flex-row-reverse items-center gap-3">
                                        {{-- Base Price : Start --}}
                                        <del class="flex rtl:flex-row-reverse gap-1 font-bold text-gray-400">
                                            <span class="text-xs">
                                                {{ __('front/homePage.EGP') }}
                                            </span>
                                            <span class="font-bold text-2xl"
                                                dir="ltr">{{ number_format(explode('.', $product['base_price'])[0], 0, '.', '\'') }}</span>
                                        </del>
                                        {{-- Base Price : End --}}

                                        {{-- Final Price : Start --}}
                                        <div class="flex rtl:flex-row-reverse gap-1">
                                            <span
                                                class="font-bold text-successDark text-xs">{{ __('front/homePage.EGP') }}</span>
                                            <span class="font-bold text-successDark text-lg"
                                                dir="ltr">{{ number_format(explode('.', $product['final_price'])[0], 0, '.', '\'') }}</span>
                                            <span
                                                class="text-successDark text-xs">{{ explode('.', $product['final_price'])[1] ?? '00' }}</span>
                                        </div>
                                        {{-- Final Price : End --}}
                                    </div>
                                @endif

                                {{-- Free Shipping :: Start --}}
                                @if ($product['free_shipping'])
                                    <span class="text-xs font-bold text-success text-center w-full">
                                        {{ __('front/homePage.Free Shipping') }}
                                    </span>
                                @endif
                                {{-- Free Shipping :: End --}}


                                <div class="flex justify-center items-center gap-1 w-32">
                                    {{-- Add :: Start --}}
                                    <button class="w-6 h-6 rounded-circle bg-secondary text-white flex justify-center items-center"
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
                                    <button class="w-6 h-6 rounded-circle bg-secondary text-white flex justify-center items-center"
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

</div>
