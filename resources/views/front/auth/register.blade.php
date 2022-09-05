@extends('layouts.auth.auth', ['title' => __('auth/authentication.Register')])

@section('content')
    {{-- Register  Form --}}
    <section class="w-full bg-gray-900 min-h-screen flex justify-center items-center">
        <div class="p-4 md:px-24 md:py-8 w-full">
            <div class="grid grid-cols-3 p-4 md:p-8 bg-white rounded-xl shadow-xl">

                {{-- Logo :: Start --}}
                <div class="hidden md:flex justify-center items-center col-span-1">
                    <figure class="">
                        <img src="{{ asset('assets/img/logos/smart-tools-logo-text-400.png') }}"
                            alt="Smart Tools Egypt Logo">
                    </figure>
                </div>
                {{-- Logo :: End --}}

                {{-- Register Form :: Start --}}
                <div class="col-span-3 md:col-span-2">

                    {{-- Header :: Start --}}
                    <h1 class="h3 font-bold text-center mb-8">{{ __('auth/authentication.Register') }}</h1>
                    {{-- Header :: End --}}

                    {{-- Form :: Start --}}
                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf
                        <div class="grid grid-cols-12 justify-center items-center gap-4">
                            {{-- First Name :: Start --}}
                            <div class="col-span-12 md:col-span-6 w-full group grid grid-cols-12">
                                <label for="f_name" class="col-span-12 block text-sm font-bold text-gray-900">
                                    <span>
                                        {{ __('auth/authentication.First Name') }}
                                    </span>
                                    <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="f_name" name="f_name" value="{{ old('f_name') }}"
                                    class="col-span-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 @error('f_name') border-red-500 @enderror"
                                    placeholder="{{ __('auth/authentication.Enter Your First Name') }}" required>

                                @error('f_name')
                                    <div class="col-span-12 my-1 text-red-600 text-center">{{ __($message) }}</div>
                                @enderror
                            </div>
                            {{-- First Name :: End --}}

                            {{-- Last Name :: Start --}}
                            <div class="col-span-12 md:col-span-6 w-full  group grid grid-cols-12">
                                <label for="l_name" class="col-span-12 block text-sm font-bold text-gray-900">
                                    <span>
                                        {{ __('auth/authentication.Last Name') }}
                                    </span>
                                    <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="l_name" name="l_name" value="{{ old('l_name') }}"
                                    class="col-span-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 @error('l_name') border-red-500 @enderror"
                                    placeholder="{{ __('auth/authentication.Enter Your Last Name') }}" required>

                                @error('l_name')
                                    <div class="col-span-12 my-1 text-red-600 text-center">{{ __($message) }}</div>
                                @enderror
                            </div>
                            {{-- Last Name :: End --}}

                            {{-- Phone :: Start --}}
                            <div class="col-span-12 md:col-span-6 w-full  group grid grid-cols-12">
                                <label for="phone" class="col-span-12 block text-sm font-bold text-gray-900">
                                    <span>
                                        {{ __('auth/authentication.Phone') }}
                                    </span>
                                    <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" dir="ltr"
                                    class="col-span-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 @error('phone') border-red-500 @enderror"
                                    placeholder="{{ __('auth/authentication.Enter Your Phone Number') }}" required>

                                @error('phone')
                                    <div class="col-span-12 my-1 text-red-600 text-center">{{ __($message) }}</div>
                                @enderror
                            </div>
                            {{-- Phone :: End --}}

                            {{-- Email :: Start --}}
                            <div class="col-span-12 md:col-span-6 w-full  group grid grid-cols-12">
                                <label for="email" class="col-span-12 block text-sm font-bold text-gray-900">
                                    <span>
                                        {{ __('auth/authentication.Email') }}
                                    </span>
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" dir="ltr"
                                    class="col-span-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 @error('email') border-red-500 @enderror"
                                    placeholder="{{ __('auth/authentication.Enter Your Email') }}">

                                @error('email')
                                    <div class="col-span-12 my-1 text-red-600 text-center">{{ __($message) }}</div>
                                @enderror
                            </div>
                            {{-- Email :: End --}}

                            {{-- Password :: Start --}}
                            <div class="col-span-12 md:col-span-6 w-full  group grid grid-cols-12">
                                <label for="password" class="col-span-12 block text-sm font-bold text-gray-900">
                                    <span>
                                        {{ __('auth/authentication.Password') }}
                                    </span>
                                    <span class="text-red-600">*</span>
                                </label>
                                <input type="password" id="password" name="password"
                                    class="col-span-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 @error('password') border-red-500 @enderror"
                                    placeholder="{{ __('auth/authentication.Enter Your Password') }}" required>

                                @error('password')
                                    <div class="col-span-12 my-1 text-red-600 text-center">{{ __($message) }}</div>
                                @enderror
                            </div>
                            {{-- Password :: End --}}

                            {{-- Confirm Password :: Start --}}
                            <div class="col-span-12 md:col-span-6 w-full  group grid grid-cols-12">
                                <label for="password_confirmation"
                                    class="col-span-12 block text-sm font-bold text-gray-900">
                                    <span>
                                        {{ __('auth/authentication.Confirm Password') }}
                                    </span>
                                    <span class="text-red-600">*</span>
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="col-span-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 @error('password_confirmation') border-red-500 @enderror"
                                    placeholder="{{ __('auth/authentication.Enter Your Password Again') }}" required>
                            </div>

                            {{-- Remeber Me :: Start --}}
                            <div class="col-span-12 w-full group grid grid-cols-12 select-none">
                                <div class="col-span-12 flex items-center gap-2">
                                    <input type="checkbox" id="remember" name="remember"
                                        class="form-checkbox rounded-full h-4 w-4 text-gray-800 transition duration-150 ease-in-out focus:outline-0 focus:ring-0 focus:ring-shadow-0  cursor-pointer">
                                    <label for="remember"
                                        class="ml-2 block text-sm leading-5 text-gray-900 m-0 font-bold  cursor-pointer">
                                        <span>
                                            {{ __('auth/authentication.Remember Me') }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                            {{-- Remeber Me :: End --}}

                            {{-- Submit :: Start --}}
                            <div class="col-span-12 w-full group grid grid-cols-12">
                                <button type="submit"
                                    class="col-span-12 bg-primary hover:bg-primaryDark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    {{ __('auth/authentication.Register') }}
                                </button>
                            </div>
                            {{-- Submit :: End --}}
                        </div>
                    </form>
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
                        <span class="font-bold">{{ __('auth/authentication.If you already have an account') }}</span>

                        <a href="{{ route('login') }}"
                            class="btn btn-sm bg-secondary font-bold rounded-full">{{ __('auth/authentication.Login') }}</a>
                    </div>
                    {{-- Or Login :: End --}}

                </div>
                {{-- Register Form :: End --}}
            </div>

        </div>
    </section>
@endsection
