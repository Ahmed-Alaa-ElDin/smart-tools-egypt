<section class="bg-white rounded shadow-lg p-4">
    <div class="grid grid-cols-12 justify-center items-start align-top gap-3 ">
        @forelse ($items as $item)
            <div
                class="col-span-12 md:col-span-6 flex gap-4 justify-start items-center transition-all ease-in-out hover:bg-white hover:text-black rounded shadow p-2  overflow-hidden">
                <a
                    href="{{ $item->options->type == 'Product' ? route('front.products.show', ['id' => $item->id, 'slug' => $item->options->slug]) : route('front.collections.show', ['id' => $item->id, 'slug' => $item->options->slug]) }}">
                    <div class="flex justify-center items-center w-24 h-24 rounded overflow-hidden">
                        {{-- Thumbnail :: Start --}}
                        @if ($item->options->thumbnail)
                            <img @if ($item->options->type == 'Product') src="{{ asset('storage/images/products/original/' . $item->options->thumbnail->file_name) }}"
                                @elseif ($item->options->type == 'Collection') src="{{ asset('storage/images/collections/original/' . $item->options->thumbnail->file_name) }}" @endif
                                class="w-100" alt="{{ $item->name[session('locale')] }}">
                        @else
                            <img src="{{ asset('assets/img/logos/smart-tools-logo-100.png') }}" class="w-100"
                                alt="{{ $item->name[session('locale')] }}">
                        @endif
                        {{-- Thumbnail :: End --}}
                    </div>
                </a>

                <div class="w-full">
                    <a href="{{ $item->options->type == 'Product' ? route('front.products.show', ['id' => $item->id, 'slug' => $item->options->slug]) : route('front.collections.show', ['id' => $item->id, 'slug' => $item->options->slug]) }}"
                        class="hover:bg-white hover:text-current hover:shadow-none w-full py-2">

                        <div class="flex flex-wrap justify-between items-center gap-4">

                            {{-- Product Name :: Start --}}
                            <h3 class="h5 m-0 font-bold truncate max-w-[100%]">
                                {{ $item->name[session('locale')] }}
                            </h3>
                            {{-- Product Name :: End --}}

                            {{-- Product Price :: Start --}}
                            <div class="flex flex-nowrap" dir="ltr">
                                <div class="flex gap-1" dir="ltr">
                                    <span
                                        class="font-bold text-green-700 text-2xl">{{ number_format(explode('.', $item->price)[0], 0, '.', '\'') }}</span>
                                    <span
                                        class="font-bold text-green-700 text-sm">{{ explode('.', $item->price)[1] ?? '00' }}</span>
                                </div>
                            </div>
                            {{-- Product Price :: End --}}
                        </div>

                    </a>
                    {{-- Buttons :: Start --}}
                    <div class="flex justify-around items-center mt-2 gap-2">
                        {{-- Add To Cart :: Start --}}
                        @livewire(
                            'front.general.cart.add-to-cart-button',
                            [
                                'item_id' => $item->id,
                                'type' => $item->options->type,
                                'text' => false,
                                'add_buy' => 'add',
                                'unique' => 'item-' . $item->id,
                            ],
                            key("add-cart-button-{$item->id}")
                        )

                        {{-- Delete :: Start --}}
                        @livewire('front.general.wishlist.remove-from-wishlist-button', ['item_id' => $item->id, 'type' => $item->options->type, 'text' => false, 'add_buy' => 'add'], key('add-cart-button-' . Str::random(10)))
                        {{-- Delete :: End --}}
                    </div>
                    {{-- Buttons :: End --}}
                </div>
            </div>
        @empty
            <div class="col-span-12">
                <div class="text-center p-3">
                    <h3 class="text-xl font-bold">
                        {{ __('front/homePage.Wishlist is Empty') }}
                    </h3>
                </div>

                <div class="text-center p-3">
                    <a href="{{ route('front.homepage') }}" class="btn bg-primary font-bold">
                        {{ __('front/homePage.Continue Shopping') }}
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="col-span-12">
        {{ $items->links() }}
    </div>
</section>
