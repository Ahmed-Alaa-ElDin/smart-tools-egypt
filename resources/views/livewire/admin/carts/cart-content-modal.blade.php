<div id="cart-content-modal" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden shadow-lg overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-3xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    {{ __('admin/ordersPages.Cart Content') }}
                </h3>
                <button type="button" id="close-cart-content-modal"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    data-modal-hide="cart-content-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5" x-data="{ showCartContent: @entangle('showCartContent') }">
                    <ul class="flex flex-col gap-2">
                        @forelse ($cart?->content ?? [] as $cartItem)
                            <li>
                                <livewire:admin.carts.cart-item-card :cartItem="$cartItem->toArray()" wire:key="cart-item-{{ $cartItem->rowId }}" />
                            </li>
                        @empty
                            <li class="flex flex-col gap-4 ">
                                {{ __('admin/ordersPages.No Cart Content') }}
                            </li>
                        @endforelse
                    </ul>
            </div>
            {{-- Modal footer --}}
            <div class="flex items-center justify-between p-4 md:p-5 border-t rounded-b">
                <div>
                    <button type="button" wire:click="completeOrder"
                        class="w-full text-white bg-primary hover:bg-primaryDark focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        {{ __('admin/ordersPages.Complete Order') }}
                    </button>
                </div>
                <div>
                    <button type="button" wire:click="cancelOrder"
                        class="w-full text-white bg-primary hover:bg-primaryDark focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        {{ __('admin/ordersPages.Cancel Order') }}
                    </button>
                </div>
                <div>
                    <button type="button" data-modal-hide="cart-content-modal"
                        class="w-full text-white bg-primary hover:bg-primaryDark focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        {{ __('admin/ordersPages.Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('livewire-js')
    <script>
        // Hide Reset Password Modal
        window.addEventListener('hideCartContentModal', function() {
            $('#close-cart-content-modal').click();
        });
    </script>
@endpush
