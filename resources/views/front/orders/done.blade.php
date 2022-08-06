@extends('layouts.front.site', ['titlePage' => __('front/homePage.Order Billing Details')])

@section('cart-wishlist-compare')
    <div class="grow text-center font-bold text-primary">
        {{ __('front/homePage.Checkout') }}
    </div>
@endsection

@section('content')
    <div class="container p-4">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 flex flex-col gap-5 self-start">

                {{-- ############## Order Steps :: Start ############## --}}
                @livewire('front.order.general.order-steps', ['step' => 4])
                {{-- ############## Order Steps :: End ############## --}}

                {{-- ############## Order Creation Status :: Start ############## --}}
                <div class="bg-white rounded overflow-hidden">
                    {{-- ############## Title :: Start ############## --}}
                    <div class="flex justify-between items-center">
                        <h3 class="h5 text-center font-bold p-4 m-0">
                            {{ __('front/homePage.Order Creation Status') }}
                        </h3>
                    </div>
                    {{-- ############## Title :: End ############## --}}

                    <hr>

                    <div class="p-7 flex justify-center items-center">
                        <div class="flex flex-col gap-2 text-center">
                            <span class="material-icons text-9xl text-success">
                                done_all
                            </span>
                            @if (session('cancel'))
                                <div class="text-xl font-bold">
                                    {!! session('cancel') !!}
                                </div>
                            @else
                                <div class="text-xl font-bold">
                                    {{ __('front/homePage.Order Created Successfully') }}
                                </div>
                                <div class="text-sm">
                                    {{ __('front/homePage.Your order has been successfully created. We will contact you shortly.') }}
                                </div>
                            @endif

                            @if (session('order_id'))
                                {{-- todo :: add tracking link --}}
                                <a href="#" class="btn bg-secondary font-bold">
                                    {{ __('front/homePage.Track Your Order') }}
                                </a>
                            @else
                                <a href="{{ route('front.homepage') }}" class="btn bg-primary font-bold">
                                    {{ __('front/homePage.Continue Shopping') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- ############## Order Creation Status :: End ############## --}}
            </div>
        </div>

        {{-- todo: Other Product Suggestions (Similar Products, Related Products, etc.) in the Cart Page (if any) --}}
    </div>
@endsection

{{-- Extra Scripts --}}
@push('js')
    <script>
        window.addEventListener('swalNotification', function(e) {
            Swal.fire({
                text: e.detail.text,
                icon: e.detail.icon,
                position: 'top-right',
                showConfirmButton: false,
                toast: true,
                timer: 3000,
                timerProgressBar: true,
            })
        });
    </script>
@endpush
