@extends('layouts.auth.login')

@section('content')
    {{-- Log in  Form  --}}
    <section class="login shadow-xl">
        <div class="container">
            <div class="login-content">
                <div class="login-image hidden md:flex">
                    <figure class="">
                        <img src="{{ asset('assets/admin/img/smart-tools-logo.png') }}" alt="sign in image">
                    </figure>
                </div>

                <div class="login-form">

                    {{-- Header --}}
                    <h2 class="form-title text-center">{{ __('auth/authentication.Log in') }}</h2>

                    {{-- Form Start --}}
                    <form method="POST" action="{{ route('admin.login') }}" class="register-form mt-8 space-y-6" id="login-form">
                        @csrf
                        <div class="rounded-md shadow-sm">

                            {{-- Email --}}
                            <div>
                                <label for="email" class="sr-only">{{ __('auth/authentication.email') }}</label>
                                <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                                    class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-secondary textsecondary rounded-t-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm"
                                    placeholder="{{ __('auth/authentication.email') }}">
                                </div>

                                {{-- Password --}}
                            <div>
                                <label for="password"
                                    class="sr-only">{{ __('auth/authentication.password') }}</label>
                                <input id="password" name="password" type="password" required
                                    class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-secondary textsecondary rounded-b-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm"
                                    placeholder="{{ __('auth/authentication.password') }}">
                                </div>
                            </div>
                            @error('email')
                                <div class="mt-1 text-primary text-center">{{ __($message) }}</div>
                            @enderror
                            @error('password')
                                <div class="mt-1 text-primary text-center">{{ __($message) }}</div>
                            @enderror

                        {{-- Remeber Me --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember" name="remember" type="checkbox"
                                    class="h-4 w-4 text-primary focus:ring-primary border-secondary rounded cursor-pointer shadow-xl">
                                <label for="remember" class="ml-2 block text-sm text-gray-900 cursor-pointer">
                                    {{ __('auth/authentication.Remember') }}
                                </label>
                            </div>
                        </div>

                        {{-- Submit  --}}
                        <div>
                            <button type="submit"
                                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-bold rounded-md text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary shadow-lg">
                                {{ __('auth/authentication.Log in') }}
                            </button>
                        </div>
                    </form>
                    {{-- Form End --}}

                    {{-- Social Media Start --}}
                    <div class="social-login">
                        <span class="social-label">{{ __('auth/authentication.Or login with') }}</span>
                        <ul class="socials ml-2">
                            <li><a href="#"><i
                                        class="display-flex-center fa-brands fa-xl fa-facebook bg-facebook p-2 text-white rounded-xl shadow-xl"></i></a>
                                    </li>
                            <li><a href="#"><i
                                class="display-flex-center fa-brands fa-xl fa-twitter bg-twitter p-2 text-white rounded-xl shadow-xl"></i></a>
                            </li>
                            <li><a href="#"><i
                                class="display-flex-center fa-brands fa-xl fa-google bg-google p-2 text-white rounded-xl shadow-xl"></i></a>
                            </li>
                        </ul>
                    </div>
                    {{-- Social Media End --}}

                </div>
            </div>
        </div>
    </section>
    @endsection
