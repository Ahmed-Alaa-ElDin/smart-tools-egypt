<div>
    @forelse ($products as $product)
        {{-- Product : Start --}}
        <x-front.product-box-wide :product="$product" type="cart" wire:key="product-{{ rand() }}" />
        {{-- Product : End --}}
        <hr>

        @if ($loop->last && $step == 1)
            {{-- ############## Buttons :: Start ############## --}}
            <div class="p-2 flex justify-center items-center">
                <a class="btn bg-primary font-bold self-stretch" href="{{ route('front.order.shipping') }}">
                    {{ __('front/homePage.Proceed to Shipping Info.') }}
                    &nbsp;
                    <span class="material-icons">
                        local_shipping
                    </span>
                </a>
            </div>
            {{-- ############## Buttons :: End ############## --}}
        @endif

    @empty
        <div class="text-center p-3">
            <h3 class="text-xl font-bold">
                {{ __('front/homePage.Shopping Cart is Empty') }}
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
