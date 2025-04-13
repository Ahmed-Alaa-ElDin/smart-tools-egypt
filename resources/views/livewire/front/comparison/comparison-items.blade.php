<section class="bg-white rounded shadow-lg p-4">
    @php
        $i = 0;
    @endphp
    @if (count($items))
        <div
            class="grid grid-cols-12 justify-center items-start align-top gap-3 rounded-xl overflow-x-scroll border-2 border-secondary">

            {{-- Table :: Start --}}
            <table class="col-span-12">
                {{-- Header :: Start --}}
                <thead>
                </thead>
                {{-- Header :: End --}}

                {{-- Body :: Start --}}
                <tbody>
                    {{-- Product Name --}}
                    <tr>
                        <th rowspan="2" colspan="2"
                            class="text-center bg-primary text-white border border-secondary p-2">
                            <span class="text-xs md:text-sm font-bold">
                                {{ __('front/homePage.Name') }}
                            </span>
                        </th>
                        @foreach ($items as $item)
                            <th class="text-center border border-secondary bg-primary">
                                {{-- link contains the name of the product --}}
                                <a href="{{ $item->type == 'Product' ? route('front.products.show', ['id' => $item->id, 'slug' => $item->slug]) : route('front.collections.show', ['id' => $item->id, 'slug' => $item->slug]) }}"
                                    class="hover:text-gray-200 text-white text-xs md:text-md">
                                    {{ $item->name }}
                                </a>
                            </th>
                        @endforeach
                    </tr>

                    {{-- Product's Thumbnail --}}
                    <tr>
                        @foreach ($items as $item)
                            <td class="text-center border border-secondary">
                                {{-- Thumbnail :: Start --}}
                                @if ($item->thumbnail)
                                    <a
                                        href="{{ $item->type == 'Product' ? route('front.products.show', ['id' => $item->id, 'slug' => $item->slug]) : route('front.collections.show', ['id' => $item->id, 'slug' => $item->slug]) }}">
                                        <img @if ($item->type == 'Product') src="{{ asset('storage/images/products/cropped100/' . $item->thumbnail->file_name) }}"
                                    @elseif ($item->type == 'Collection') src="{{ asset('storage/images/collections/cropped100/' . $item->thumbnail->file_name) }}" @endif
                                            class="rounded m-auto" alt="{{ $item->name }}">
                                    </a>
                                @else
                                    <a
                                        href="{{ $item->type == 'Product' ? route('front.products.show', ['id' => $item->id, 'slug' => $item->slug]) : route('front.collections.show', ['id' => $item->id, 'slug' => $item->slug]) }}">
                                        <img src="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}"
                                            class="rounded m-auto" alt="{{ $item->name }}">
                                    </a>
                                @endif
                                {{-- Thumbnail :: End --}}
                            </td>
                        @endforeach
                    </tr>

                    {{-- Product's model --}}
                    <tr>
                        <th colspan="2" class="text-center bg-primary text-white border border-secondary p-2">
                            <span class="text-xs md:text-sm font-bold">
                                {{ __('front/homePage.Model') }}
                            </span>
                        </th>
                        @foreach ($items as $item)
                            <td class="text-center border border-secondary {{ $i % 2 ?: 'bg-gray-100' }}">
                                <span class="text-gray-800 text-xs md:text-sm font-bold">
                                    {{ $item->model }}
                                </span>
                            </td>
                        @endforeach
                        @php $i++ @endphp
                    </tr>

                    {{-- Product's Base Price --}}
                    <tr>
                        <th colspan="2" class="text-center bg-primary text-white border border-secondary p-2">
                            <span class="text-xs md:text-sm font-bold">
                                {{ __('front/homePage.Before Discount') }}
                            </span>
                        </th>
                        @foreach ($items as $item)
                            <td class="text-center border border-secondary {{ $i % 2 ?: 'bg-gray-100' }}">
                                <span
                                    class="font-bold {{ $item->final_price < $item->base_price ? 'text-xs md:text-sm line-through text-red-500' : 'text-sm md:text-md text-green-500' }}">
                                    {{ $item->base_price . ' ' . __('front/homePage.EGP') }}
                                </span>
                            </td>
                        @endforeach
                        @php $i++ @endphp
                    </tr>

                    {{-- Products Final Price --}}
                    <tr>
                        <th colspan="2" class="text-center bg-primary text-white border border-secondary p-2">
                            <span class="text-xs md:text-sm font-bold">
                                {{ __('front/homePage.After Discount') }}
                            </span>
                        </th>
                        @foreach ($items as $item)
                            <td class="text-center border border-secondary {{ $i % 2 ?: 'bg-gray-100' }}">
                                <span
                                    class="font-bold {{ $item->best_price < $item->final_price ? 'text-xs md:text-sm line-through text-red-500' : 'text-sm md:text-md text-green-500' }}">
                                    {{ $item->final_price . ' ' . __('front/homePage.EGP') }}
                                </span>
                            </td>
                        @endforeach
                        @php $i++ @endphp
                    </tr>

                    {{-- Products Best Price --}}
                    <tr>
                        <th colspan="2" class="text-center bg-primary text-white border border-secondary p-2">
                            <span class="text-xs md:text-sm font-bold">
                                {{ __('front/homePage.Final Price') }}
                            </span>
                        </th>
                        @foreach ($items as $item)
                            <td class="text-center border border-secondary {{ $i % 2 ?: 'bg-gray-100' }}">
                                <span class="font-bold text-green-500 text-xs md:text-sm">
                                    {{ $item->best_price . ' ' . __('front/homePage.EGP') }}
                                </span>
                            </td>
                        @endforeach
                        @php $i++ @endphp
                    </tr>

                    {{-- Points --}}
                    <tr>
                        <th colspan="2" class="text-center bg-primary text-white border border-secondary p-2">
                            <span class="text-xs md:text-sm font-bold">
                                {{ __('front/homePage.Points') }}
                            </span>
                        </th>
                        @foreach ($items as $item)
                            <td class="text-center border border-secondary {{ $i % 2 ?: 'bg-gray-100' }}">
                                <span class="font-bold text-green-500 text-xs md:text-sm">
                                    {{ $item->best_points . ' ' . trans_choice('front/homePage.Point/Points', $item->best_points) }}
                                </span>
                            </td>
                        @endforeach
                        @php $i++ @endphp
                    </tr>

                    {{-- Description --}}
                    <tr>
                        <th colspan="2" class="text-center bg-primary text-white border border-secondary p-2">
                            <span class="text-xs md:text-sm font-bold">
                                {{ __('front/homePage.Description') }}
                            </span>
                        </th>
                        @foreach ($items as $item)
                            <td class="description border border-secondary p-2 {{ $i % 2 ?: 'bg-gray-100' }}">
                                <span class="font-bold text-xs md:text-sm text-gray-800 overflow-hidden">
                                    {!! $item->description !!}
                                </span>
                            </td>
                        @endforeach
                        @php $i++ @endphp
                    </tr>

                    {{-- Specs --}}
                    @foreach ($specs as $title => $spec)
                        <tr>
                            @if ($loop->first)
                                <th rowspan="{{ count($specs) }}"
                                    class="text-center bg-primary text-white border border-secondary p-2">
                                    <span class="text-xs md:text-sm font-bold">
                                        {{ __('front/homePage.Specs') }}
                                    </span>
                                </th>
                            @endif
                            <th class="text-center bg-primary text-white border border-secondary p-2">
                                <span class="text-xs md:text-sm font-bold">
                                    {{ $title }}
                                </span>
                            </th>
                            @foreach ($items as $item)
                                <td class="specs border border-secondary {{ $i % 2 ?: 'bg-gray-100' }} text-center">
                                    <span class="font-bold text-xs md:text-sm text-gray-800 overflow-hidden">
                                        {{ $spec[$item->id] }}
                                    </span>
                                </td>
                            @endforeach
                            @php $i++ @endphp
                        </tr>
                    @endforeach

                    {{-- Actions --}}
                    <tr>
                        <th colspan="2" class="text-center bg-primary text-white border border-secondary p-2">
                            <span class="text-xs md:text-sm font-bold">
                                {{ __('front/homePage.Actions') }}
                            </span>
                        </th>
                        @foreach ($items as $item)
                            <td class="text-center border border-secondary {{ $i % 2 ?: 'bg-gray-100' }}">
                                <div class="flex justify-center items-center gap-2 p-2">
                                    {{-- Add to cart : Start --}}
                                    @livewire(
                                        'front.general.cart.add-to-cart-button',
                                        [
                                            'item_id' => $item->id,
                                            'text' => false,
                                            'large' => false,
                                            'type' => $item->type,
                                            'add_buy' => 'add',
                                            'unique' => 'item-' . $item->id,
                                        ],
                                        key("add-cart-button-{$item->id}")
                                    )
                                    {{-- Add to cart : End --}}

                                    {{-- Add to wishlist : Start --}}
                                    @livewire(
                                        'front.general.wishlist.add-to-wishlist-button',
                                        [
                                            'item_id' => $item->id,
                                            'text' => false,
                                            'large' => false,
                                            'type' => $item->type,
                                        ],
                                        key("add-wishlist-button-{$item->id}")
                                    )
                                    {{-- Add to wishlist : End --}}

                                    {{-- Add to comparison : Start --}}
                                    @livewire(
                                        'front.general.compare.remove-from-compare-button',
                                        [
                                            'item_id' => $item->id,
                                            'text' => false,
                                            'large' => false,
                                            'type' => $item->type,
                                        ],
                                        key("remove-from-compare-button-{$item->id}")
                                    )
                                    {{-- Add to comparison : End --}}
                            </td>
                        @endforeach
                        @php $i++ @endphp
                </tbody>

            </table>
        @else
            <div class="col-span-12">
                <div class="text-center p-3">
                    <h3 class="text-xl font-bold">
                        {{ __('front/homePage.Comparison list is Empty') }}
                    </h3>
                </div>

                <div class="text-center p-3">
                    <a href="{{ route('front.homepage') }}" class="btn bg-primary font-bold">
                        {{ __('front/homePage.Continue Shopping') }}
                    </a>
                </div>
            </div>
    @endif
    </div>
</section>
