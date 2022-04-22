<div class="mobile-bottom-header fixed lg:hidden bottom-0 bg-white shadow-lg border-top rounded-top w-full">
    <div class="flex justify-between items-center">
        <div class="col">
            <a href="#" class="block text-center pb-2 pt-3">
                <span class="material-icons opacity-60 text-xl">
                    home
                </span>
                <span class="block font-bold opacity-60">{{ __('front/homePage.Home') }}</span>
            </a>
        </div>
        <div class="col">
            <a href="#" class="block text-center pb-2 pt-3">
                <span class="material-icons opacity-60 text-xl">
                    category
                </span>
                <span class="block font-bold opacity-60">{{ __('front/homePage.Categories') }}</span>
            </a>
        </div>
        <div class="col">
            <a href="#" class="relative flex justify-center text-center pb-2 pt-3 min-w-max">
                <span
                    class="flex justify-center items-center bg-primary h-12 w-12 border border-white border-4 rounded-circle absolute -mt-12">
                    <span class="material-icons text-white">
                        shopping_cart
                    </span>
                </span>
                <span class="flex justify-center items-center mt-1 font-bold opacity-60">
                    {{ __('front/homePage.Cart') }}
                    (<span class="cart-count">0</span>)
                </span>
            </a>
        </div>
        <div class="col">
            <a href="#" class="block text-center pb-2 pt-3">
                <span class="inline-block relative px-2">
                    <span class="material-icons opacity-60 text-xl">
                        notifications
                    </span>
                </span>
                <span class="block font-bold opacity-60 ">{{ __('front/homePage.Notifications') }}</span>
            </a>
        </div>
        <div class="col">
            <a href="#" class="block text-center pb-2 pt-3">
                <span class="block mx-auto">
                    <span class="material-icons opacity-60 text-xl">
                        account_circle
                    </span>
                </span>
                <span class="block font-bold opacity-60">{{ __('front/homePage.Account') }}</span>
            </a>
        </div>
    </div>
</div>
