<div>
    <div class="px-4">
        {{-- Payment Method :: Start --}}
        <div class="flex flex-col gap-2 p-4">
            <h2 class="col-span-2 text-center font-bold">
                {{ __('front/homePage.Payment Method') }}
            </h2>
            <div class="flex flex-wrap justify-around items-center gap-2">
                {{-- Cash on Delivery --}}
                <div class="select-none cursor-pointer  hover:shadow-inner shadow bg-gray-100  rounded-xl p-2">
                    Cash on delivery (COD)
                </div>

                {{-- Credit Card --}}
                <div class="select-none cursor-pointer  hover:shadow-inner shadow bg-gray-100  rounded-xl p-2">
                    Credit / Debit Card
                </div>

                {{-- installment --}}
                <div class="select-none cursor-pointer  hover:shadow-inner shadow bg-gray-100  rounded-xl p-2">
                    Installment
                </div>

                {{-- Vodafone Cash --}}
                <div class="select-none cursor-pointer  hover:shadow-inner shadow bg-gray-100  rounded-xl p-2">
                    Vodafone Cash
                </div>
            </div>
        </div>
        {{-- Payment Method :: End --}}

        <hr>

        {{-- Payment Method Details :: Start --}}
        Payment Method Details
        {{-- Payment Method Details :: End --}}
    </div>
</div>
