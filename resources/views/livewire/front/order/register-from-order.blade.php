<div>
    <section class="flex justify-center items-center overflow-auto scrollbar scrollbar-thin">
        <div class="p-4 md:px-24 md:py-8 w-full h-screen">
            <div class="grid grid-cols-3 px-4 md:p-8 bg-white rounded-xl shadow-xl">

                {{-- Register Form :: Start --}}
                <div class="col-span-3">

                    {{-- Header :: Start --}}
                    <h1 class="h3 font-bold text-center mb-8">{{ __('auth/authentication.Shipping info.') }}</h1>
                    {{-- Header :: End --}}

                    {{-- Form :: Start --}}
                        <div class="grid grid-cols-12 justify-center items-center gap-4 mb-3">
                            {{-- First Name :: Start --}}
                            <div class="col-span-12 md:col-span-6 xl:col-span-4 w-full group grid grid-cols-12">
                                <label for="f_name" class="col-span-12 block text-sm font-bold text-gray-900">
                                    <span>
                                        {{ __('auth/authentication.First Name') }}
                                    </span>
                                    <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="f_name" wire:model="f_name"
                                    class="col-span-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 @error('f_name') border-red-500 @enderror"
                                    placeholder="{{ __('auth/authentication.Enter Your First Name') }}" required>

                                @error('f_name')
                                    <div class="col-span-12 my-1 text-red-600 text-center">{{ __($message) }}</div>
                                @enderror
                            </div>
                            {{-- First Name :: End --}}

                            {{-- Last Name :: Start --}}
                            <div class="col-span-12 md:col-span-6 xl:col-span-4 w-full  group grid grid-cols-12">
                                <label for="l_name" class="col-span-12 block text-sm font-bold text-gray-900">
                                    <span>
                                        {{ __('auth/authentication.Last Name') }}
                                    </span>
                                    <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="l_name" wire:model="l_name"
                                    class="col-span-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 @error('l_name') border-red-500 @enderror"
                                    placeholder="{{ __('auth/authentication.Enter Your Last Name') }}">

                                @error('l_name')
                                    <div class="col-span-12 my-1 text-red-600 text-center">{{ __($message) }}</div>
                                @enderror
                            </div>
                            {{-- Last Name :: End --}}

                            {{-- Phone :: Start --}}
                            <div class="col-span-12 md:col-span-6 xl:col-span-4 w-full  group grid grid-cols-12">
                                <label for="phone" class="col-span-12 block text-sm font-bold text-gray-900">
                                    <span>
                                        {{ __('auth/authentication.Phone') }}
                                    </span>
                                    <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="phone" wire:model="phone" dir='ltr'
                                    class="col-span-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 @error('phone') border-red-500 @enderror"
                                    placeholder="{{ __('auth/authentication.Enter Your Phone Number') }}" required>

                                @error('phone')
                                    <div class="col-span-12 my-1 text-red-600 text-center">{{ __($message) }}</div>
                                @enderror
                            </div>
                            {{-- Phone :: End --}}

                            {{-- Email :: Start --}}
                            <div class="col-span-12 md:col-span-6 xl:col-span-4 w-full  group grid grid-cols-12">
                                <label for="email" class="col-span-12 block text-sm font-bold text-gray-900">
                                    <span>
                                        {{ __('auth/authentication.Email') }}
                                    </span>
                                </label>
                                <input type="email" id="email" wire:model="email" dir='ltr'
                                    class="col-span-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 @error('email') border-red-500 @enderror"
                                    placeholder="{{ __('auth/authentication.Enter Your Email') }}">

                                @error('email')
                                    <div class="col-span-12 my-1 text-red-600 text-center">{{ __($message) }}</div>
                                @enderror
                            </div>
                            {{-- Email :: End --}}

                            {{-- Password :: Start --}}
                            <div class="col-span-12 md:col-span-6 xl:col-span-4 w-full  group grid grid-cols-12">
                                <label for="password" class="col-span-12 block text-sm font-bold text-gray-900">
                                    <span>
                                        {{ __('auth/authentication.Password') }}
                                    </span>
                                    <span class="text-red-600">*</span>
                                </label>
                                <input type="password" id="password" wire:model="password"
                                    class="col-span-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 @error('password') border-red-500 @enderror"
                                    placeholder="{{ __('auth/authentication.Enter Your Password') }}" required>

                                @error('password')
                                    <div class="col-span-12 my-1 text-red-600 text-center">{{ __($message) }}</div>
                                @enderror
                            </div>
                            {{-- Password :: End --}}

                            {{-- Confirm Password :: Start --}}
                            <div class="col-span-12 md:col-span-6 xl:col-span-4 w-full  group grid grid-cols-12">
                                <label for="password_confirmation"
                                    class="col-span-12 block text-sm font-bold text-gray-900">
                                    <span>
                                        {{ __('auth/authentication.Confirm Password') }}
                                    </span>
                                    <span class="text-red-600">*</span>
                                </label>
                                <input type="password" id="password_confirmation" wire:model="password_confirmation"
                                    class="col-span-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 @error('password_confirmation') border-red-500 @enderror"
                                    placeholder="{{ __('auth/authentication.Enter Your Password Again') }}" required>
                            </div>

                            {{-- Address --}}
                            <div
                                class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
                                {{-- User Address Select Boxes --}}
                                <div class="grid grid-cols-3 gap-x-4 gap-y-2 col-span-12">
                                    <div class="bg-red-200 rounded col-span-3 grid grid-cols-3 gap-x-4 gap-y-2 p-2 ">

                                        {{-- Country --}}
                                        <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                                            <label
                                                class="col-span-1 lg:col-span-3 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                                for="country">{{ __('front/homePage.Country') }}</label>
                                            <select
                                                class="col-span-2 lg:col-span-3 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                                                wire:model='address.country_id' id="country">
                                                @forelse ($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}
                                                    </option>
                                                @empty
                                                    <option value="">
                                                        {{ __('front/homePage.No Countries in Database') }}
                                                    </option>
                                                @endforelse
                                            </select>
                                        </div>

                                        {{-- Governorate --}}
                                        <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                                            <label
                                                class="col-span-1 lg:col-span-3 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                                for="governorate">{{ __('front/homePage.Governorate') }}</label>
                                            <select
                                                class="col-span-2 lg:col-span-3 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                                                wire:model='address.governorate_id' id="governorate">
                                                @forelse ($governorates as $governorate)
                                                    <option value="{{ $governorate['id'] }}">
                                                        {{ $governorate['name'][session('locale')] }}</option>
                                                @empty
                                                    @if ($country == null)
                                                        <option value="">
                                                            {{ __('front/homePage.Please Choose Country First') }}
                                                        </option>
                                                    @else
                                                        <option value="">
                                                            {{ __('front/homePage.No Governorates in Database') }}
                                                        </option>
                                                    @endif
                                                @endforelse
                                            </select>
                                        </div>

                                        {{-- City --}}
                                        <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                                            <label
                                                class="col-span-1 lg:col-span-3 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                                for="city">{{ __('front/homePage.City') }}</label>

                                            <select
                                                class="col-span-2 lg:col-span-3 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                                                wire:model='address.city_id' id="city">
                                                @forelse ($cities as $city)
                                                    <option value="{{ $city['id'] }}">
                                                        {{ $city['name'][session('locale')] }}
                                                    </option>
                                                @empty
                                                    @if ($address['governorate_id'] == null)
                                                        <option value="">
                                                            {{ __('front/homePage.Please Choose Governorate First') }}
                                                        </option>
                                                    @else
                                                        <option value="">
                                                            {{ __('front/homePage.No Cities in Database') }}
                                                        </option>
                                                    @endif
                                                @endforelse
                                            </select>
                                        </div>

                                        {{-- Details --}}
                                        <div
                                            class="details col-span-3 grid grid-cols-6 justify-between items-center m-0">
                                            <label
                                                class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                                for="details">{{ __('front/homePage.Address Details') }}</label>
                                            <textarea id="details" rows="2" wire:model.lazy="address.details" dir="rtl"
                                                placeholder="{{ __('front/homePage.Please mention the details of the address such as street name, building number, ... etc.') }}"
                                                class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 overflow-hidden"></textarea>
                                        </div>

                                        {{-- Landmarks --}}
                                        <div
                                            class="landmarks col-span-3 grid grid-cols-6 justify-between items-center">
                                            <label
                                                class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                                for="landmarks">{{ __('front/homePage.Landmarks') }}</label>
                                            <textarea id="landmarks" rows="2" wire:model.lazy="address.landmarks" dir="rtl"
                                                placeholder="{{ __('front/homePage.Please mention any landmarks such as mosque, grocery, ... etc.') }}"
                                                class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"></textarea>
                                        </div>
                                    </div>

                                    @error('address.*')
                                        <div
                                            class="inline-block mt-2 col-span-3 md:col-span-1 md:col-start-2 bg-red-700 rounded text-white shadow px-3 py-1">
                                            {{ $message }}</div>
                                    @enderror

                                </div>
                            </div>

                            {{-- Submit :: Start --}}
                            <div class="col-span-12 w-full group flex justify-around items-center">
                                <button wire:click.prevent="submit" type="button"
                                    class="btn bg-success hover:bg-successDark text-white font-bold py-2 px-8 rounded focus:outline-none focus:shadow-outline">
                                    {{ __('auth/authentication.Submit') }}
                                </button>

                                <a href="{{ route('front.cart') }}"
                                    class="btn bg-primary hover:bg-primaryDark text-white font-bold py-2 px-8 rounded focus:outline-none focus:shadow-outline">
                                    <span class="material-icons">
                                        shopping_cart
                                    </span>
                                    &nbsp;
                                    {{ __('auth/authentication.Back to Cart') }}
                                </a>
                            </div>
                            {{-- Submit :: End --}}
                        </div>
                    {{-- Form :: End --}}

                    {{-- Social Media :: Start --}}
                    <div class="flex items-center justify-center w-full gap-3 mb-3">
                        <span class="font-bold">{{ __('auth/authentication.Or register with') }}</span>

                        <ul class="flex items-center gap-1">
                            <li>
                                <a href="{{ route('facebook.redirect') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                        class="w-8 h-8 p-2 text-white rounded-full shadow bg-facebook transition-all ease-in-out hover:scale-110"
                                        width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M13.397 20.997v-8.196h2.765l.411-3.209h-3.176V7.548c0-.926.258-1.56 1.587-1.56h1.684V3.127A22.336 22.336 0 0 0 14.201 3c-2.444 0-4.122 1.492-4.122 4.231v2.355H7.332v3.209h2.753v8.202h3.312z" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('twitter.redirect') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                        class="w-8 h-8 p-2 text-white rounded-full shadow bg-twitter transition-all ease-in-out hover:scale-110"
                                        width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M23.643 4.937c-.835.37-1.732.62-2.675.733a4.67 4.67 0 0 0 2.048-2.578a9.3 9.3 0 0 1-2.958 1.13a4.66 4.66 0 0 0-7.938 4.25a13.229 13.229 0 0 1-9.602-4.868c-.4.69-.63 1.49-.63 2.342A4.66 4.66 0 0 0 3.96 9.824a4.647 4.647 0 0 1-2.11-.583v.06a4.66 4.66 0 0 0 3.737 4.568a4.692 4.692 0 0 1-2.104.08a4.661 4.661 0 0 0 4.352 3.234a9.348 9.348 0 0 1-5.786 1.995a9.5 9.5 0 0 1-1.112-.065a13.175 13.175 0 0 0 7.14 2.093c8.57 0 13.255-7.098 13.255-13.254c0-.2-.005-.402-.014-.602a9.47 9.47 0 0 0 2.323-2.41l.002-.003Z" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('google.redirect') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                        class="w-8 h-8 p-2 text-white rounded-full shadow bg-google transition-all ease-in-out hover:scale-110"
                                        width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M21.456 10.154c.123.659.19 1.348.19 2.067c0 5.624-3.764 9.623-9.449 9.623A9.841 9.841 0 0 1 2.353 12a9.841 9.841 0 0 1 9.844-9.844c2.658 0 4.879.978 6.583 2.566l-2.775 2.775V7.49c-1.033-.984-2.344-1.489-3.808-1.489c-3.248 0-5.888 2.744-5.888 5.993c0 3.248 2.64 5.998 5.888 5.998c2.947 0 4.953-1.685 5.365-3.999h-5.365v-3.839h9.26Z" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                    {{-- Social Media :: End --}}

                    {{-- Or Login :: Start --}}
                    <div class="flex items-center justify-center w-full gap-3">
                        <span
                            class="font-bold">{{ __('auth/authentication.If you already have an account') }}</span>

                        <a href="{{ route('login') }}"
                            class="btn btn-sm bg-secondary font-bold rounded-full">{{ __('auth/authentication.Login') }}</a>
                    </div>
                    {{-- Or Login :: End --}}

                </div>
                {{-- Register Form :: End --}}
            </div>

        </div>
    </section>
</div>
