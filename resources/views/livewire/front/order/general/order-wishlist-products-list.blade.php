<div>
    @forelse ($products as $product)
        {{-- Product : Start --}}
        <x-front.product-box-wide :product="$product" type="wishlist" wire:key="product-{{ rand() }}" />
        {{-- Product : End --}}

        @if (!$loop->last)
            <hr>
        @endif

    @empty
        <div class="text-center p-3">
            <h3 class="text-xl font-bold">
                {{ __('front/homePage.Wishlist is Empty') }}
            </h3>
        </div>

        <hr>

        <div class="text-center p-3">
            <a href="{{ route('front.homepage') }}" class="btn bg-primary font-bold">
                {{ __('front/homePage.Continue Shopping') }}
            </a>
        </div>
    @endforelse
</div>
