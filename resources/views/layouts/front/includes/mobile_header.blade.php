<div class="mobile-bottom-header fixed lg:hidden bottom-0 bg-white shadow-lg border-top rounded-top w-full">
    <div class="flex justify-center items-center px-4">
        <div class="col">
            <a href="{{ route('front.homepage') }}" class="block text-center pb-2 pt-3 text-xs">
                <span class="material-icons opacity-60 text-xl">
                    home
                </span>
                <span class="block font-bold opacity-60">{{ __('front/homePage.Home') }}</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('front.wishlist') }}" class="block text-center pb-2 pt-3 text-xs">
                <span class="material-icons opacity-60 text-xl">
                    favorite
                </span>
                <span class="block font-bold opacity-60">{{ __('front/homePage.My Wishlist') }}</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('front.cart') }}" class="relative flex justify-center text-center pb-2 pt-3 min-w-max text-xs">
                <span
                    class="flex justify-center items-center bg-primary h-12 w-12 border border-white border-4 rounded-circle absolute -mt-12">
                    <span class="material-icons text-white">
                        shopping_cart
                    </span>
                </span>
                <span class="flex justify-center items-center mt-1 font-bold opacity-60">
                    {{ __('front/homePage.Cart') }}
                    (<span class="cart-count">{{ Cart::instance('cart')->count() }}</span>)
                </span>
            </a>
        </div>
        <div class="col">
            <a href="#" class="block text-center pb-2 pt-3 text-xs">
                <span class="inline-block relative px-2">
                    <span class="material-icons opacity-60 text-xl">
                        notifications
                    </span>
                </span>
                <span class="block font-bold opacity-60 ">{{ __('front/homePage.Notifications') }}</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('front.profile.index') }}" class="block text-center pb-2 pt-3 text-xs">
                <span class="block mx-auto">
                    @if (auth()->user() && auth()->user()->profile_photo_path)
                        <img class="h-8 w-8 rounded-full m-auto"
                            src="{{ asset('storage/images/profiles/cropped100/' . auth()->user()->profile_photo_path) }}"
                            alt="{{ auth()->user()->f_name . ' ' . auth()->user()->l_name . 'profile image' }}">
                    @else
                        <span class="material-icons text-xl">
                            account_circle
                        </span>
                    @endif
                </span>
                <span class="block font-bold opacity-60">{{ __('front/homePage.Account') }}</span>
            </a>
        </div>
    </div>
</div>
