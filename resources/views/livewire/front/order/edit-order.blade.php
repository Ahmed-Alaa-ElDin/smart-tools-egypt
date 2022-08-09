<div>
    <div class="p-4 bg-gray-100 flex justify-around items-center">
        {{-- Order Placed --}}
        <div class="flex justify-center items-center gap-1">
            <span class="text-sm font-bold"> {{ __('front/homePage.Order Placed') }}:
            </span>
            <span class="">
                {{ $order->created_at->format('d/m/Y') }}
            </span>
        </div>

        {{-- Order Subtotal --}}
        <div class="flex justify-center items-center gap-1">
            <span class="text-sm font-bold"> {{ __('front/homePage.Subtotal :') }} </span>
            <div class="flex rtl:flex-row-reverse gap-1">
                <span class="text-xs">{{ __('front/homePage.EGP') }}</span>
                {{-- <span dir="ltr" class="font-bold">{{ number_format($subtotal, 2, '.', '\'') }}</span> --}}
            </div>
        </div>

        {{-- Order Delivery Fees --}}
        <div class="flex justify-center items-center gap-1">
            <span class="text-sm font-bold"> {{ __('front/homePage.Shipping :') }} </span>
            {{-- @if ($delivery_fees == 0.0) --}}
            <div class="text-successDark font-bold">
                {{ __('front/homePage.Free Shipping') }}
            </div>
            {{-- @else --}}
            <div class="flex rtl:flex-row-reverse gap-1">
                <span class="text-xs">{{ __('front/homePage.EGP') }}</span>
                {{-- <span class="font-bold" dir="ltr">{{ number_format($delivery_fees, 2, '.', '\'') }}</span> --}}
            </div>
            {{-- @endif --}}
        </div>

        {{-- Order Total --}}
        <div class="flex justify-center items-center gap-1">
            <span class="text-sm font-bold"> {{ __('front/homePage.Total :') }} </span>
            <div class="flex rtl:flex-row-reverse gap-1">
                <span class="text-xs">{{ __('front/homePage.EGP') }}</span>
                {{-- <span dir="ltr" class="font-bold">{{ number_format($subtotal + $delivery_fees, 2, '.', '\'') }}</span> --}}
            </div>
        </div>
    </div>
    <div class="p-4">
        @forelse ($order->products as $key => $product)
            <div>
                {{ $product->name }}
            </div>
        @empty
            <div class="text-center">
                <span class="font-bold">
                    {{ __('front/homePage.No products in this order') }}
                </span>
            </div>
        @endforelse
        @forelse ($products as $key => $product)
            <div>
                {{ $product->name }}
            </div>
        @empty
            <div class="text-center">
                <span class="font-bold">
                    {{ __('front/homePage.No products in this order') }}
                </span>
            </div>
        @endforelse
    </div>

    {{-- Buttons :: Start --}}
    <hr>
    <div class="p-2 flex justify-around items-center gap-2">
        @if (count($order->products) > 0)
            <button wire:click='saveEdits' class="btn bg-successDark font-bold">
                {{ __('front/homePage.Save Edits') }}
            </button>
        @endif
        <a href="{{ route('front.orders.index') }}" class="btn bg-primary font-bold">
            {{ __('front/homePage.Back') }}
        </a>
    </div>
    {{-- Buttons :: End --}}
</div>
