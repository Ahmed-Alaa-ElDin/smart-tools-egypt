@extends('layouts.auth.auth', ['title' => __('auth/authentication.Register')])

@section('content')
    <section class="min-h-screen w-full flex flex-col justify-between py-6 px-4 sm:px-6 lg:px-8 relative bg-gradient-to-br from-slate-950 via-gray-900 to-zinc-950 overflow-hidden">
        {{-- Ambient background glows --}}
        <div class="pointer-events-none absolute -top-40 -left-40 w-96 h-96 rounded-full bg-primary/20 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-primaryDark/25 blur-3xl"></div>
        <div class="pointer-events-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full bg-primary/5 blur-3xl"></div>

        {{-- Top Navigation Bar --}}
        <header class="w-full max-w-5xl mx-auto flex items-center justify-between z-10 py-2">
            <a href="{{ route('front.homepage') }}"
                class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-sm font-semibold text-gray-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 backdrop-blur-md transition-all duration-200 group">
                <span class="material-icons text-base transition-transform group-hover:-translate-x-0.5 rtl:group-hover:translate-x-0.5 rtl:rotate-180">arrow_back</span>
                <span>{{ __('auth/authentication.Back to Store') }}</span>
            </a>

            <div class="flex items-center gap-2">
                @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    @if ($localeCode !== LaravelLocalization::getCurrentLocale())
                        <a rel="alternate" hreflang="{{ $localeCode }}"
                            href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-gray-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 backdrop-blur-md transition-all duration-200">
                            <span class="material-icons text-sm">language</span>
                            <span>{{ $properties['native'] }}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        </header>

        {{-- Main Card Container --}}
        <div class="w-full max-w-5xl mx-auto my-auto z-10 py-4">
            <div class="grid grid-cols-1 lg:grid-cols-12 bg-white rounded-3xl shadow-2xl shadow-black/50 overflow-hidden border border-white/10">

                {{-- Brand Showcase Side :: 5 Cols --}}
                <div class="lg:col-span-5 bg-gradient-to-br from-gray-900 via-neutral-900 to-slate-950 p-8 sm:p-10 flex flex-col justify-between text-white relative overflow-hidden border-b lg:border-b-0 lg:border-e border-gray-800">
                    {{-- Decorative side accent --}}
                    <div class="pointer-events-none absolute -top-20 -right-20 w-60 h-60 bg-primary/20 rounded-full blur-2xl"></div>

                    {{-- Brand Header & Logo --}}
                    <div class="relative z-10 text-center lg:text-start">
                        <a href="{{ route('front.homepage') }}" class="inline-block p-4 sm:p-5 rounded-3xl bg-white shadow-2xl shadow-black/40 hover:scale-105 transition-all duration-200">
                            <img src="{{ asset('assets/img/logos/smart-tools-logo-text-400.png') }}"
                                alt="Smart Tools Egypt Logo"
                                class="h-24 sm:h-28 md:h-32 w-auto object-contain mx-auto lg:mx-0">
                        </a>

                        <h2 class="mt-6 text-2xl font-extrabold tracking-tight text-white leading-snug">
                            {{ __('auth/authentication.register_hero_title') }}
                        </h2>
                        <p class="mt-2 text-sm text-gray-300 leading-relaxed">
                            {{ __('auth/authentication.register_hero_subtitle') }}
                        </p>
                    </div>

                    {{-- Feature Bullets --}}
                    <div class="relative z-10 my-8 space-y-3.5">
                        <div class="flex items-center gap-3.5 p-3 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                            <div class="w-10 h-10 rounded-lg bg-red-500/20 text-red-400 flex items-center justify-center shrink-0">
                                <span class="material-icons text-xl">loyalty</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">
                                    {{ __('auth/authentication.feature_deals_title') }}
                                </h4>
                                <p class="text-xs text-gray-300">
                                    {{ __('auth/authentication.feature_deals_desc') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3.5 p-3 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                            <div class="w-10 h-10 rounded-lg bg-red-500/20 text-red-400 flex items-center justify-center shrink-0">
                                <span class="material-icons text-xl">inventory_2</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">
                                    {{ __('auth/authentication.feature_tracking_title') }}
                                </h4>
                                <p class="text-xs text-gray-300">
                                    {{ __('auth/authentication.feature_tracking_desc') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3.5 p-3 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                            <div class="w-10 h-10 rounded-lg bg-red-500/20 text-red-400 flex items-center justify-center shrink-0">
                                <span class="material-icons text-xl">speed</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">
                                    {{ __('auth/authentication.feature_fast_checkout_title') }}
                                </h4>
                                <p class="text-xs text-gray-300">
                                    {{ __('auth/authentication.feature_fast_checkout_desc') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom Trust Note --}}
                    <div class="relative z-10 pt-4 border-t border-gray-800/80 text-xs text-gray-400 flex items-center justify-between">
                        <span>Smart Tools Egypt &copy; {{ date('Y') }}</span>
                        <span class="inline-flex items-center gap-1 text-emerald-400">
                            <span class="material-icons text-sm">lock</span>
                            {{ __('auth/authentication.secure_register') }}
                        </span>
                    </div>
                </div>

                {{-- Form Side :: 7 Cols --}}
                <div class="lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center bg-white">
                    {{-- Form Header --}}
                    <div class="mb-6">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-primary mb-3">
                            <span class="material-icons text-sm">person_add</span>
                            <span>{{ __('auth/authentication.create_account') }}</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                            {{ __('auth/authentication.Register') }}
                        </h1>
                        <p class="mt-1.5 text-sm text-gray-500">
                            {{ __('auth/authentication.register_form_subtitle') }}
                        </p>
                    </div>

                    {{-- Registration Form --}}
                    <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
                        @csrf

                        {{-- Name Row: First Name & Last Name --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- First Name --}}
                            <div>
                                <label for="f_name" class="block text-sm font-bold text-gray-800 mb-1.5">
                                    {{ __('auth/authentication.First Name') }}
                                    <span class="text-primary">*</span>
                                </label>
                                <div class="relative rounded-xl shadow-sm">
                                    <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 ltr:pl-3.5 rtl:pr-3.5 flex items-center pointer-events-none text-gray-400">
                                        <span class="material-icons text-lg">person</span>
                                    </div>
                                    <input type="text" id="f_name" name="f_name" value="{{ old('f_name') }}"
                                        class="block w-full ltr:pl-11 rtl:pr-11 py-3 px-4 text-sm text-gray-900 bg-gray-50/70 border rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 @error('f_name') border-red-400 bg-red-50/30 ring-2 ring-red-100 @else border-gray-200 @enderror"
                                        placeholder="{{ __('auth/authentication.Enter Your First Name') }}" required autofocus>
                                </div>
                                @error('f_name')
                                    <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1">
                                        <span class="material-icons text-xs">cancel</span>
                                        <span>{{ __($message) }}</span>
                                    </p>
                                @enderror
                            </div>

                            {{-- Last Name --}}
                            <div>
                                <label for="l_name" class="block text-sm font-bold text-gray-800 mb-1.5">
                                    {{ __('auth/authentication.Last Name') }}
                                    <span class="text-primary">*</span>
                                </label>
                                <div class="relative rounded-xl shadow-sm">
                                    <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 ltr:pl-3.5 rtl:pr-3.5 flex items-center pointer-events-none text-gray-400">
                                        <span class="material-icons text-lg">badge</span>
                                    </div>
                                    <input type="text" id="l_name" name="l_name" value="{{ old('l_name') }}"
                                        class="block w-full ltr:pl-11 rtl:pr-11 py-3 px-4 text-sm text-gray-900 bg-gray-50/70 border rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 @error('l_name') border-red-400 bg-red-50/30 ring-2 ring-red-100 @else border-gray-200 @enderror"
                                        placeholder="{{ __('auth/authentication.Enter Your Last Name') }}" required>
                                </div>
                                @error('l_name')
                                    <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1">
                                        <span class="material-icons text-xs">cancel</span>
                                        <span>{{ __($message) }}</span>
                                    </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Contact Row: Phone & Email --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="block text-sm font-bold text-gray-800 mb-1.5">
                                    {{ __('auth/authentication.Phone') }}
                                    <span class="text-primary">*</span>
                                </label>
                                <div class="relative rounded-xl shadow-sm">
                                    <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 ltr:pl-3.5 rtl:pr-3.5 flex items-center pointer-events-none text-gray-400">
                                        <span class="material-icons text-lg">smartphone</span>
                                    </div>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" dir="ltr"
                                        class="block w-full ltr:pl-11 rtl:pr-11 py-3 px-4 text-sm text-gray-900 bg-gray-50/70 border rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 @error('phone') border-red-400 bg-red-50/30 ring-2 ring-red-100 @else border-gray-200 @enderror"
                                        placeholder="{{ __('auth/authentication.Enter Your Phone Number') }}" required>
                                </div>
                                @error('phone')
                                    <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1">
                                        <span class="material-icons text-xs">cancel</span>
                                        <span>{{ __($message) }}</span>
                                    </p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-bold text-gray-800 mb-1.5 flex items-center justify-between">
                                    <span>{{ __('auth/authentication.Email') }}</span>
                                    <span class="text-xs font-normal text-gray-400">({{ __('auth/authentication.Optional') }})</span>
                                </label>
                                <div class="relative rounded-xl shadow-sm">
                                    <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 ltr:pl-3.5 rtl:pr-3.5 flex items-center pointer-events-none text-gray-400">
                                        <span class="material-icons text-lg">mail</span>
                                    </div>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" dir="ltr"
                                        class="block w-full ltr:pl-11 rtl:pr-11 py-3 px-4 text-sm text-gray-900 bg-gray-50/70 border rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 @error('email') border-red-400 bg-red-50/30 ring-2 ring-red-100 @else border-gray-200 @enderror"
                                        placeholder="{{ __('auth/authentication.Enter Your Email') }}">
                                </div>
                                @error('email')
                                    <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1">
                                        <span class="material-icons text-xs">cancel</span>
                                        <span>{{ __($message) }}</span>
                                    </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Password Row: Password & Confirm Password --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Password --}}
                            <div>
                                <label for="password" class="block text-sm font-bold text-gray-800 mb-1.5">
                                    {{ __('auth/authentication.Password') }}
                                    <span class="text-primary">*</span>
                                </label>
                                <div class="relative rounded-xl shadow-sm">
                                    <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 ltr:pl-3.5 rtl:pr-3.5 flex items-center pointer-events-none text-gray-400">
                                        <span class="material-icons text-lg">lock</span>
                                    </div>
                                    <input type="password" id="password" name="password"
                                        class="block w-full ltr:pl-11 rtl:pr-11 ltr:pr-11 rtl:pl-11 py-3 px-4 text-sm text-gray-900 bg-gray-50/70 border rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 @error('password') border-red-400 bg-red-50/30 ring-2 ring-red-100 @else border-gray-200 @enderror"
                                        placeholder="{{ __('auth/authentication.Enter Your Password') }}" required>
                                    <button type="button" class="toggle-password absolute inset-y-0 ltr:right-0 rtl:left-0 ltr:pr-3.5 rtl:pl-3.5 flex items-center text-gray-400 hover:text-gray-700 cursor-pointer select-none transition-colors" data-target="password" title="Toggle password visibility">
                                        <span class="material-icons text-lg">visibility</span>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1">
                                        <span class="material-icons text-xs">cancel</span>
                                        <span>{{ __($message) }}</span>
                                    </p>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label for="password_confirmation" class="block text-sm font-bold text-gray-800 mb-1.5">
                                    {{ __('auth/authentication.Confirm Password') }}
                                    <span class="text-primary">*</span>
                                </label>
                                <div class="relative rounded-xl shadow-sm">
                                    <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 ltr:pl-3.5 rtl:pr-3.5 flex items-center pointer-events-none text-gray-400">
                                        <span class="material-icons text-lg">lock_reset</span>
                                    </div>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="block w-full ltr:pl-11 rtl:pr-11 ltr:pr-11 rtl:pl-11 py-3 px-4 text-sm text-gray-900 bg-gray-50/70 border rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 @error('password_confirmation') border-red-400 bg-red-50/30 ring-2 ring-red-100 @else border-gray-200 @enderror"
                                        placeholder="{{ __('auth/authentication.Enter Your Password Again') }}" required>
                                    <button type="button" class="toggle-password absolute inset-y-0 ltr:right-0 rtl:left-0 ltr:pr-3.5 rtl:pl-3.5 flex items-center text-gray-400 hover:text-gray-700 cursor-pointer select-none transition-colors" data-target="password_confirmation" title="Toggle password confirmation visibility">
                                        <span class="material-icons text-lg">visibility</span>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1">
                                        <span class="material-icons text-xs">cancel</span>
                                        <span>{{ __($message) }}</span>
                                    </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Terms Reassurance Note --}}
                        <p class="text-xs text-gray-500 pt-1 leading-relaxed">
                            {{ __('auth/authentication.terms_agreement') }}
                        </p>

                        {{-- Submit Button --}}
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl text-base font-bold text-white bg-gradient-to-r from-primary to-primaryDark hover:from-primaryDark hover:to-primaryDarker shadow-lg shadow-primary/25 hover:shadow-primary/40 active:scale-[0.99] transform transition-all duration-150">
                                <span>{{ __('auth/authentication.Register') }}</span>
                                <span class="material-icons text-lg rtl:rotate-180">arrow_forward</span>
                            </button>
                        </div>
                    </form>

                    {{-- Social Register Divider --}}
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs font-semibold uppercase">
                            <span class="bg-white px-3 text-gray-400">
                                {{ __('auth/authentication.Or register with') }}
                            </span>
                        </div>
                    </div>

                    {{-- Social Media Buttons --}}
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('google.redirect') }}"
                            class="flex items-center justify-center gap-2.5 py-2.5 px-4 rounded-xl border border-gray-200 hover:border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-semibold text-sm shadow-sm hover:shadow transition-all group">
                            <svg class="w-4 h-4 shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                            </svg>
                            <span>Google</span>
                        </a>

                        <a href="{{ route('facebook.redirect') }}"
                            class="flex items-center justify-center gap-2.5 py-2.5 px-4 rounded-xl border border-[#1877F2]/20 hover:border-[#1877F2]/40 bg-[#1877F2]/5 hover:bg-[#1877F2]/10 text-[#1877F2] font-semibold text-sm shadow-sm hover:shadow transition-all group">
                            <svg class="w-4 h-4 fill-current shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span>Facebook</span>
                        </a>
                    </div>

                    {{-- Switch to Login Section --}}
                    <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-start bg-gray-50/70 p-4 rounded-2xl">
                        <div>
                            <p class="text-xs text-gray-500">{{ __('auth/authentication.If you already have an account') }}</p>
                            <p class="text-sm font-bold text-gray-900">{{ __('auth/authentication.login_now_to_account') }}</p>
                        </div>
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-gray-800 bg-white hover:bg-gray-100 border border-gray-200 shadow-sm hover:shadow transition-all">
                            <span>{{ __('auth/authentication.Login') }}</span>
                            <span class="material-icons text-sm rtl:rotate-180">arrow_forward</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- Footer note --}}
        <footer class="w-full text-center py-2 text-xs text-gray-500 z-10">
            <span>{{ config('app.name', 'Smart Tools Egypt') }} &mdash; {{ __('auth/authentication.Register') }}</span>
        </footer>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Universal Password Visibility Toggle
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target') || 'password';
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('.material-icons');
                    if (!input) return;

                    if (input.type === 'password') {
                        input.type = 'text';
                        if (icon) icon.textContent = 'visibility_off';
                    } else {
                        input.type = 'password';
                        if (icon) icon.textContent = 'visibility';
                    }
                });
            });
        });
    </script>
@endpush
