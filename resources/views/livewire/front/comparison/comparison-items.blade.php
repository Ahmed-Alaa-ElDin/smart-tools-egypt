<section class="bg-white rounded shadow-lg p-4">
    <div class="grid grid-cols-12 justify-center items-start align-top gap-3 ">
        {{-- Table :: Start --}}
        <table class="col-span-12">
            {{-- Header :: Start --}}
            <thead>
                {{-- Product Name --}}
                <tr>
                    @foreach ($items as $item)
                        <th class="text-center">
                            {{-- link contains the name of the product --}}
                            <a href="{{ $item->type == 'Product' ? route('front.products.show', ['id' => $item->id, 'slug' => $item->slug]) : route('front.collections.show', ['id' => $item->id, 'slug' => $item->slug]) }}"
                                class="hover:text-primary">
                                {{ $item->name }}
                            </a>
                        </th>
                    @endforeach
                </tr>
            </thead>
            {{-- Header :: End --}}

            {{-- Body :: Start --}}
            {{-- Product's Thumbnail --}}
            <tr>
                @foreach ($items as $item)
                    <td class="text-center i">
                        {{-- Thumbnail :: Start --}}
                        {{-- @if ($item->options->thumbnail)
                            <a
                                href="{{ $item->options->type == 'Product' ? route('front.products.show', ['id' => $item->id, 'slug' => $item->options->slug]) : route('front.collections.show', ['id' => $item->id, 'slug' => $item->options->slug]) }}">
                                <img @if ($item->options->type == 'Product') src="{{ asset('storage/images/products/cropped100/' . $item->options->thumbnail->file_name) }}"
                                    @elseif ($item->options->type == 'Collection') src="{{ asset('storage/images/collections/cropped100/' . $item->options->thumbnail->file_name) }}" @endif
                                    class="rounded m-auto" alt="{{ $item->name[session('locale')] }}">
                            </a>
                        @else
                            <a
                                href="{{ $item->options->type == 'Product' ? route('front.products.show', ['id' => $item->id, 'slug' => $item->options->slug]) : route('front.collections.show', ['id' => $item->id, 'slug' => $item->options->slug]) }}">
                                <img src="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}"
                                    class="rounded m-auto" alt="{{ $item->name[session('locale')] }}">
                            </a>
                        @endif --}}
                        {{-- Thumbnail :: End --}}
                    </td>
                @endforeach
            </tr>

            {{-- Product's model --}}
            <tr>
                @foreach ($items as $item)
                    <td class="text-center">
                        <span class="text-xs">
                            {{ $item->model }}
                        </span>
                    </td>
                @endforeach
            </tr>


        </table>

        @forelse ($items as $item)
        @empty
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
        @endforelse
    </div>
</section>
