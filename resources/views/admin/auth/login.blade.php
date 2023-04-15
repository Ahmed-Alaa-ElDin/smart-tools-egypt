@extends('layouts.auth.auth', ['title' => __('auth/authentication.Admins Login')])

@section('content')
    {{-- Login  Form --}}
    <section class="w-full bg-gray-900 min-h-screen flex justify-center items-center">
        <div class="p-4 md:px-24 md:py-8 w-full">
            <div class="grid grid-cols-2 p-4 md:p-8 bg-white rounded-xl shadow-xl">

                {{-- Logo :: Start --}}
                <div class="hidden md:flex justify-center items-center col-span-1">
                    <figure class="">
                        <img src="{{ asset('assets/img/logos/smart-tools-logo-text-400.png') }}"
                            alt="Smart Tools Egypt Logo">
                    </figure>
                </div>
                {{-- Logo :: End --}}

                {{-- Login Form :: Start --}}
                <div class="col-span-2 md:col-span-1">

                    {{-- Header :: Start --}}
                    <h1 class="h3 font-bold text-center mb-8">{{ __('auth/authentication.Admins Login') }}</h1>
                    {{-- Header :: End --}}

                    {{-- Form :: Start --}}
                    <form method="POST" action="{{ route('admin.login.store') }}">
                        @csrf
                        <div class="grid grid-cols-12 justify-center items-center gap-4">

                            {{-- Email :: Start --}}
                            <div class="col-span-12 md:col-span-6 w-full  group grid grid-cols-12">
                                <label for="email" class="col-span-12 block text-sm font-bold text-gray-900">
                                    <span>
                                        {{ __('auth/authentication.Email') }}
                                    </span>
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="col-span-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 @error('email') border-red-500 @enderror"
                                    placeholder="{{ __('auth/authentication.Enter Your Email') }}" required>

                                @error('email')
                                    <div class="col-span-12 my-1 text-red-600 text-center text-sm font-bold">{{ __($message) }}</div>
                                @enderror
                            </div>
                            {{-- Email :: End --}}

                            {{-- Password :: Start --}}
                            <div class="col-span-12 md:col-span-6 w-full  group grid grid-cols-12">
                                <label for="password" class="col-span-12 block text-sm font-bold text-gray-900">
                                    <span>
                                        {{ __('auth/authentication.Password') }}
                                    </span>
                                </label>
                                <input type="password" id="password" name="password"
                                    class="col-span-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 @error('password') border-red-500 @enderror"
                                    placeholder="{{ __('auth/authentication.Enter Your Password') }}" required>

                                @error('password')
                                    <div class="col-span-12 my-1 text-red-600 text-center text-sm font-bold">{{ __($message) }}</div>
                                @enderror
                            </div>
                            {{-- Password :: End --}}

                            {{-- Auth Error Message :: Start --}}
                            @error('auth')
                                <div class="col-span-12 text-red-600 text-center text-sm font-bold">{{ __($message) }}</div>
                            @enderror
                            {{-- Auth Error Message :: End --}}

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
                                    {{ __('auth/authentication.Login') }}
                                </button>
                            </div>
                            {{-- Submit :: End --}}
                        </div>
                    </form>
                    {{-- Form :: End --}}

                </div>
                {{-- Login Form :: End --}}
            </div>

        </div>
    </section>
@endsection
