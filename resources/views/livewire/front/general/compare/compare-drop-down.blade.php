<div class="hidden lg:block align-self-stretch ml-3 mr-0" data-hover="dropdown">
    <div class="nav-cart-box dropdown h-100" id="compare">
        <a href="javascript:void(0)" class="flex items-center gap-2 text-reset h-100" data-toggle="dropdown"
            data-display="static">
            <span class="material-icons">
                compare_arrows
            </span>
            <span class="grow ml-1 text-center">
                <span
                    class="badge bg-red-600 mb-1 text-white badge-inline badge-pill">{{ Cart::instance('compare')->count() }}</span>
                <span class="nav-box-text text-xs hidden xl:block opacity-70">
                    {{ __('front/homePage.Compare') }}
                </span>
            </span>
        </a>

        <div class="dropdown-menu p-0 stop-propagation z-50 min-w-max overflow-hidden">

            <div
                class="text-center p-2 overflow-y-auto overflow-x-hidden scrollbar scrollbar-thin scrollbar-thumb-red-200 max-h-[50vh]">
                {{-- Cart Items :: Start --}}
                <ul>
                    @forelse ($compare as $compare_item)
                        <li>
                            <div
                                class="flex flex-nowrap gap-4 justify-between items-center transition-all ease-in-out hover:bg-white hover:text-black rounded hover:shadow-xl px-2">
                                <a @if ($compare_item->options->type == 'Product') href="{{ route('front.products.show', ['id' => $compare_item->id, 'slug' => $compare_item->options->slug]) }}"
                                @elseif ($compare_item->options->type == 'Collection')
                                href="{{ route('front.collections.show', ['id' => $compare_item->id, 'slug' => $compare_item->options->slug]) }}" @endif
                                    class="flex flex-nowrap gap-4 justify-between items-center hover:bg-white hover:text-current hover:shadow-none w-full py-2">

                                    {{-- Thumbnail :: Start --}}
                                    @if ($compare_item->options->thumbnail)
                                        <img  @if ($compare_item->options->type == 'Product') src="{{ asset('storage/images/products/cropped100/' . $compare_item->options->thumbnail->file_name) }}"
                                        @elseif ($compare_item->options->type == 'Collection')
                                        src="{{ asset('storage/images/collections/cropped100/' . $compare_item->options->thumbnail->file_name) }}" @endif
                                            class="w-14 h-14 rounded"
                                            alt="{{ $compare_item->name[session('locale')] }}">
                                    @else
                                        <img src="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}"
                                            class="w-14 h-14 rounded"
                                            alt="{{ $compare_item->name[session('locale')] }}">
                                    @endif
                                    {{-- Thumbnail :: End --}}

                                    <div class="flex flex-col">

                                        {{-- Product Name :: Start --}}
                                        <h3 class="h5 m-0 font-bold truncate max-w-[150px]">
                                            {{ $compare_item->name[session('locale')] }}
                                        </h3>
                                        {{-- Product Name :: End --}}

                                        {{-- Product Amount & Price :: Start --}}
                                        <div class="flex flex-nowrap" dir="ltr">
                                            <div class="flex gap-1" dir="ltr">
                                                <span
                                                    class="font-bold text-green-700">{{ number_format(explode('.', $compare_item->price)[0], 0, '.', '\'') }}</span>
                                                <span
                                                    class="font-bold text-green-700 text-xs">{{ explode('.', $compare_item->price)[1] ?? '00' }}</span>
                                            </div>
                                        </div>
                                        {{-- Product Amount & Price :: End --}}
                                    </div>

                                </a>
                                {{-- Buttons :: Start --}}
                                <div class="flex gap-2">
                                    {{-- Add To Cart :: Start --}}
                                    <button title="{{ __('front/homePage.Add to cart') }}"
                                        class="w-8 h-8 rounded-circle bg-secondary border border-secondary text-white transition ease-in-out hover:bg-primary hover:text-white  animate-pulse text-center shadow-sm"
                                        wire:click="moveToCart('{{ $compare_item->rowId }}')">
                                        <span class="material-icons text-lg rounded-circle">
                                            shopping_cart
                                        </span>
                                    </button>
                                    {{-- Add To Cart :: End --}}

                                    {{-- Delete :: Start --}}
                                    <button title="{{ __('front/homePage.Remove from the compare list') }}"
                                        class="w-8 h-8 rounded-circle bg-white border border-primary text-primary transition ease-in-out hover:bg-primary hover:text-white"
                                        wire:click="removeFromCompare('{{ $compare_item->rowId }}')">
                                        <span class="material-icons text-lg">
                                            delete
                                        </span>
                                    </button>
                                    {{-- Delete :: End --}}
                                </div>
                                {{-- Buttons :: End --}}
                            </div>
                        </li>

                        @if (!$loop->last)
                            <hr class="my-2">
                        @endif
                    @empty
                        <li>
                            <h3 class="h5 m-0 font-bold px-3">{{ __('front/homePage.Comparison is Empty') }}</h3>
                        </li>
                    @endforelse
                </ul>
                {{-- Cart Items :: End --}}
            </div>

            @if ($compare_count > 0)
                {{-- CompareButtons :: Start --}}
                <div class="flex flex-col justify-center items-center gap-1 m-1 px-2">
                    <div class="flex justify-center items-center gap-3 w-full">
                        {{-- View & Edit Compare:: Start --}}
                        <a href="{{ route('front.comparison') }}" class="grow btn bg-primary btn-sm text-white font-bold">
                            <span class="material-icons">
                                visibility
                            </span>
                            &nbsp;
                            {{ __('front/homePage.View the Comparison') }}
                        </a>
                        {{-- View & Edit Compare:: End --}}

                        {{-- Clear Compare:: Start --}}
                        <button wire:click="clearCompare"
                            class="btn bg-white border border-primary btn-sm text-primary font-bold rounded-full"
                            title="{{ __('front/homePage.Clear Comparison') }}">
                            <span class="material-icons">
                                delete
                            </span>
                        </button>
                        {{-- Clear Compare:: End --}}
                    </div>
                </div>
                {{-- CompareButtons :: End --}}
            @endif
        </div>

    </div>
</div>
