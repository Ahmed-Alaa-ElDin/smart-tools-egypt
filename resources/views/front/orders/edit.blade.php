@extends('layouts.front.user-control-layout', ['titlePage' => __('front/homePage.Edit Order'), 'page' => 'orders'])

@section('breadcrumb')
    <li class="breadcrumb-item hover:text-primary">
        <a href="{{ route('front.homepage') }}">
            {{ __('front/homePage.Homepage') }}
        </a>
    </li>
    <li class="breadcrumb-item hover:text-primary">
        <a href="{{ route('front.orders.index') }}">
            {{ __('front/homePage.My Orders') }}
        </a>
    </li>
    <li class="breadcrumb-item text-gray-700 font-bold" aria-current="page">
        {{ __('front/homePage.Edit Order') }}
    </li>
@endsection

@section('sub-content')
    <div class="container col-span-12">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 flex flex-col gap-5 self-start">

                {{-- ############## Edit Order :: Start ############## --}}
                <div class="bg-white rounded-xl overflow-hidden">
                    {{-- ############## Title :: Start ############## --}}
                    <div class="flex justify-between items-center">
                        <h3 class="h5 text-center font-bold p-4 m-0">
                            {{ __('front/homePage.Edit Order') }}
                        </h3>
                    </div>
                    {{-- ############## Title :: End ############## --}}

                    <hr>

                    <form class="m-0" action="{{ route('front.orders.update-calc', $order->id) }}" method="POST">
                        @csrf

                        {{-- ################# Order Summary :: Start ################# --}}
                        <div class="p-4 bg-gray-100 flex flex-col gap-3">
                            {{-- Title --}}
                            <div class="flex justify-center items-center gap-1">
                                <span class="font-bold"> {{ __('front/homePage.Order summary before edits') }} </span>
                            </div>

                            <div class="flex flex-wrap justify-around items-center">
                                {{-- Creation Date --}}
                                <div class="flex justify-center items-center gap-1">
                                    <span class="text-sm font-bold"> {{ __('front/homePage.Creation Date') }}:
                                    </span>
                                    <span class="">
                                        {{ $order->created_at->format('d/m/Y') }}
                                    </span>
                                </div>

                                {{-- Order Delivery Fees --}}
                                <div class="flex justify-center items-center gap-1">
                                    <span class="text-sm font-bold"> {{ __('front/homePage.Shipping:') }} </span>
                                    @if ($order->invoice->delivery_fees == 0)
                                        <div class="text-successDark font-bold">
                                            {{ __('front/homePage.Free Shipping') }}
                                        </div>
                                    @else
                                        <div class="flex rtl:flex-row-reverse gap-1">
                                            <span class="text-xs">{{ __('front/homePage.EGP') }}</span>
                                            <span class="font-bold"
                                                dir="ltr">{{ number_format($order->invoice->delivery_fees, 2, '.', '\'') }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Order Total --}}
                                <div class="flex justify-center items-center gap-1">
                                    <span class="text-sm font-bold"> {{ __('front/homePage.Total:') }} </span>
                                    <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                                        <span class="text-xs">{{ __('front/homePage.EGP') }}</span>
                                        <span dir="ltr"
                                            class="font-bold">{{ number_format($order->invoice->total ?? 0, 2, '.', '\'') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ################# Order Summary :: End ################# --}}

                        <hr>

                        {{-- ############## Order Items :: Start ############## --}}
                        @if ($order->products->count() || $order->collections->count())
                            {{-- Collection :: Start --}}
                            @foreach ($order->collections as $collection)
                                {{-- collection Id --}}
                                <input type="hidden" name="collections_ids[]" value="{{ $collection->id }}">

                                <div
                                    class="flex gap-5 items-center p-4 scrollbar scrollbar-hidden rounded @if ($collection->pivot->quantity == 0) bg-red-100 @endif">
                                    {{-- Thumbnail :: Start --}}
                                    <a href="{{ route('front.collections.show', ['id' => $collection->id, 'slug' => $collection->slug]) }}"
                                        class="block hover:text-current">
                                        @if ($collection->thumbnail)
                                            <img class="w-full h-full flex justify-center items-center bg-gray-200"
                                                src="{{ asset('storage/images/collections/cropped100/' . $collection->thumbnail->file_name) }}"
                                                alt="{{ $collection->name . 'image' }}">
                                        @else
                                            <div class="w-full h-full flex justify-center items-center bg-gray-200 rounded">
                                                <span class="block material-icons text-8xl">
                                                    construction
                                                </span>
                                            </div>
                                        @endif
                                    </a>
                                    {{-- Thumbnail :: End --}}

                                    {{-- Collection Info :: Start --}}
                                    <div class="grow flex flex-col justify-start gap-2">

                                        {{-- Collection Name :: Start --}}
                                        <div class="flex items-center">
                                            <a href="{{ route('front.collections.show', ['id' => $collection->id, 'slug' => $collection->slug]) }}"
                                                class="text-lg font-bold truncate  hover:text-current">
                                                {{ $collection->name }}
                                            </a>
                                        </div>
                                        {{-- Collection Name :: End --}}

                                        {{-- Reviews :: Start --}}
                                        <div class="my-1 flex justify-start items-center gap-2">
                                            <div class="rating flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span
                                                        class="material-icons inline-block @if ($i <= ceil($collection->avg_rating)) text-yellow-300 @else text-gray-400 @endif">
                                                        star
                                                    </span>
                                                @endfor
                                            </div>

                                            <span
                                                class="text-sm text-gray-600">({{ $collection->reviews->count() ?? 0 }})</span>
                                        </div>
                                        {{-- Reviews :: End --}}

                                    </div>
                                    {{-- Collection Info :: End --}}

                                    {{-- Collection Price :: Start --}}
                                    <div class="flex flex-col justify-center items-end gap-1">
                                        @if ($collection['under_reviewing'])
                                            <span class="text-yellow-600 font-bold text-sm">
                                                {{ __('front/homePage.Under Reviewing') }}
                                            </span>
                                        @else
                                            <div class="flex flex-col gap-1 justify-center items-center">
                                                <h4 class="text-xs font-bold">
                                                    {{ __('front/homePage.Piece Price') }}
                                                </h4>
                                                <div class="flex flex-col md:flex-row-reverse items-center gap-3">
                                                    {{-- Base Price :: Start --}}
                                                    <del class="flex rtl:flex-row-reverse gap-1 font-bold text-red-400">
                                                        <span class="text-xs">
                                                            {{ __('front/homePage.EGP') }}
                                                        </span>
                                                        <span class="font-bold text-2xl"
                                                            dir="ltr">{{ number_format(explode('.', $collection->base_price)[0], 0, '.', '\'') ?? '00' }}</span>
                                                    </del>
                                                    {{-- Base Price :: End --}}

                                                    {{-- Final Price :: Start --}}
                                                    <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                                                        <span
                                                            class="font-bold text-xs">{{ __('front/homePage.EGP') }}</span>
                                                        <span class="font-bold text-lg"
                                                            dir="ltr">{{ number_format(explode('.', $collection->pivot->price)[0], 0, '.', '\'') ?? '00' }}</span>
                                                        <span
                                                            class="text-xs">{{ explode('.', $collection->pivot->price)[1] ?? '00' }}</span>
                                                    </div>
                                                    {{-- Final Price :: End --}}

                                                </div>
                                            </div>
                                        @endif

                                        {{-- Collection Amount :: Start --}}
                                        <div>
                                            <div class="flex items-center justify-center gap-2">
                                                <span class="text-xs font-bold text-gray-600">
                                                    {{ __('front/homePage.Quantity') }}
                                                </span>
                                                <select name="collections_quantities[]"
                                                    class="text-primary font-bold text-sm rounded-xl cursor-pointer border-0 shadow focus:outline-none active:outline-none focus:ring-0 active:ring-0">
                                                    @for ($i = 0; $i <= ($collection->pivot->quantity + 5 <= $collection->quantity ? $collection->pivot->quantity + 5 : $collection->pivot->quantity + $collection->quantity); $i++)
                                                        <option value="{{ $i }}"
                                                            @if ($i == $collection->pivot->quantity) selected @endif>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Collection Amount :: End --}}

                                    </div>
                                    {{-- Collection Price :: End --}}
                                </div>
                            @endforeach
                            {{-- Collection :: End --}}

                            {{-- Product :: Start --}}
                            @foreach ($order->products as $product)
                                {{-- product Id --}}
                                <input type="hidden" name="products_ids[]" value="{{ $product->id }}">

                                <div
                                    class="flex gap-5 items-center p-4 scrollbar scrollbar-hidden rounded @if ($product->pivot->quantity == 0) bg-red-100 @endif">
                                    {{-- Thumbnail :: Start --}}
                                    <a href="{{ route('front.products.show', ['id' => $product->id, 'slug' => $product->slug]) }}"
                                        class="block hover:text-current">
                                        @if ($product->thumbnail)
                                            <img class="w-full h-full flex justify-center items-center bg-gray-200"
                                                src="{{ asset('storage/images/products/cropped100/' . $product->thumbnail->file_name) }}"
                                                alt="{{ $product->name . 'image' }}">
                                        @else
                                            <div class="w-full h-full flex justify-center items-center bg-gray-200 rounded">
                                                <span class="block material-icons text-8xl">
                                                    construction
                                                </span>
                                            </div>
                                        @endif
                                    </a>
                                    {{-- Thumbnail :: End --}}

                                    {{-- Product Info :: Start --}}
                                    <div class="grow flex flex-col justify-start gap-2">
                                        {{-- Product's Brand :: Start --}}
                                        {{-- todo :: brand link --}}
                                        <div class="flex items-center">
                                            <a href="{{ route('front.brands.show', ['brand' => $product->brand->id]) }}" class="text-sm font-bold text-gray-400 hover:text-current">
                                                {{ $product->brand ? $product->brand->name : '' }}
                                            </a>
                                        </div>
                                        {{-- Product's Brand :: End --}}

                                        {{-- Product Name :: Start --}}
                                        <div class="flex items-center">
                                            <a href="{{ route('front.products.show', ['id' => $product->id, 'slug' => $product->slug]) }}"
                                                class="text-lg font-bold truncate  hover:text-current">
                                                {{ $product->name }}
                                            </a>
                                        </div>
                                        {{-- Product Name :: End --}}

                                        {{-- Reviews :: Start --}}
                                        <div class="my-1 flex justify-start items-center gap-2">
                                            <div class="rating flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span
                                                        class="material-icons inline-block @if ($i <= ceil($product->avg_rating)) text-yellow-300 @else text-gray-400 @endif">
                                                        star
                                                    </span>
                                                @endfor
                                            </div>

                                            <span
                                                class="text-sm text-gray-600">({{ $product->reviews->count() ?? 0 }})</span>
                                        </div>
                                        {{-- Reviews :: End --}}

                                    </div>
                                    {{-- Product Info :: End --}}

                                    {{-- Product Price :: Start --}}
                                    <div class="flex flex-col justify-center items-end gap-1">
                                        @if ($product['under_reviewing'])
                                            <span class="text-yellow-600 font-bold text-sm">
                                                {{ __('front/homePage.Under Reviewing') }}
                                            </span>
                                        @else
                                            <div class="flex flex-col gap-1 justify-center items-center">
                                                <h4 class="text-xs font-bold">
                                                    {{ __('front/homePage.Piece Price') }}
                                                </h4>
                                                <div class="flex flex-col md:flex-row-reverse items-center gap-3">
                                                    {{-- Base Price :: Start --}}
                                                    <del class="flex rtl:flex-row-reverse gap-1 font-bold text-red-400">
                                                        <span class="text-xs">
                                                            {{ __('front/homePage.EGP') }}
                                                        </span>
                                                        <span class="font-bold text-2xl"
                                                            dir="ltr">{{ number_format(explode('.', $product->base_price)[0], 0, '.', '\'') ?? '00' }}</span>
                                                    </del>
                                                    {{-- Base Price :: End --}}

                                                    {{-- Final Price :: Start --}}
                                                    <div class="flex rtl:flex-row-reverse text-successDark gap-1">
                                                        <span
                                                            class="font-bold text-xs">{{ __('front/homePage.EGP') }}</span>
                                                        <span class="font-bold text-lg"
                                                            dir="ltr">{{ number_format(explode('.', $product->pivot->price)[0], 0, '.', '\'') ?? '00' }}</span>
                                                        <span
                                                            class="text-xs">{{ explode('.', $product->pivot->price)[1] ?? '00' }}</span>
                                                    </div>
                                                    {{-- Final Price :: End --}}

                                                </div>
                                            </div>
                                        @endif

                                        {{-- Product Amount :: Start --}}
                                        <div>
                                            <div class="flex items-center justify-center gap-2">
                                                <span class="text-xs font-bold text-gray-600">
                                                    {{ __('front/homePage.Quantity') }}
                                                </span>
                                                <select name="products_quantities[]"
                                                    class="text-primary font-bold text-sm rounded-xl cursor-pointer border-0 shadow focus:outline-none active:outline-none focus:ring-0 active:ring-0">
                                                    @for ($i = 0; $i <= ($product->pivot->quantity + 5 <= $product->quantity ? $product->pivot->quantity + 5 : $product->pivot->quantity + $product->quantity); $i++)
                                                        <option value="{{ $i }}"
                                                            @if ($i == $product->pivot->quantity) selected @endif>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Product Amount :: End --}}

                                    </div>
                                    {{-- Product Price :: End --}}
                                </div>
                            @endforeach
                            {{-- Product :: End --}}

                            <hr>
                        @else
                            <div class="text-center p-4">
                                <span class="font-bold">
                                    {{ __('front/homePage.No products in this order') }}
                                </span>
                            </div>
                        @endif

                        <hr>

                        {{-- Buttons :: Start --}}
                        <div class="p-2 flex justify-around items-center gap-2">
                            @if ($order->products->count() || $order->collections->count())
                                <button type="submit" name="type" value="preview"
                                    class="btn bg-white text-successDark border border-successDark font-bold transition-all ease-in-out hover:text-white hover:bg-successDark hover:border-white">
                                    {{ __('front/homePage.Preview Order Summary After Edits') }}
                                </button>
                            @endif
                            <a href="{{ route('front.orders.index') }}" class="btn bg-primary font-bold">
                                {{ __('front/homePage.Back') }}
                            </a>
                        </div>
                        {{-- Buttons :: End --}}
                    </form>

                </div>
                {{-- ############## Edit Order :: End ############## --}}
            </div>
        </div>
    </div>
@endsection

{{-- Extra Scripts --}}
