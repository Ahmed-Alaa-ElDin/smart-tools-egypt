<div id="forgot-password-modal" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full bg-slate-950/70 backdrop-blur-sm transition-all duration-300">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/70">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-icons text-lg">lock_reset</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 leading-tight">
                            {{ __('auth/authentication.Reset Password') }}
                        </h3>
                        <p class="text-xs text-gray-500">
                            {{ __('auth/authentication.reset_password_modal_subtitle') }}
                        </p>
                    </div>
                </div>

                <button type="button"
                    id="close-forgot-password-modal"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-xl text-sm w-8 h-8 inline-flex justify-center items-center transition-colors"
                    data-modal-hide="forgot-password-modal">
                    <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <!-- Modal body -->
            <div class="p-6" x-data="{ showResetPasswordCode: @entangle('showResetPasswordCode'), showResetPassword: @entangle('showResetPassword') }">
                <div class="flex flex-col gap-4 mb-5">
                    {{-- Phone --}}
                    <div x-show="!showResetPassword" x-transition>
                        <label for="resetPasswordPhone" class="block mb-1.5 text-xs font-bold text-gray-800">
                            {{ __('auth/authentication.Phone') }}
                        </label>
                        <div class="flex items-center gap-2">
                            <div class="relative w-full rounded-xl">
                                <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 ltr:pl-3.5 rtl:pr-3.5 flex items-center pointer-events-none text-gray-400">
                                    <span class="material-icons text-base">smartphone</span>
                                </div>
                                <input type="tel" wire:model="resetPasswordPhone" id="resetPasswordPhone" dir="ltr"
                                    class="block w-full ltr:pl-10 rtl:pr-10 py-2.5 px-3.5 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary disabled:opacity-60 disabled:cursor-not-allowed transition-all"
                                    placeholder="{{ __('auth/authentication.Enter Your Phone Number') }}" required
                                    :disabled="showResetPasswordCode">
                            </div>

                            <button type="button" wire:click="changePhone" x-show="showResetPasswordCode"
                                class="shrink-0 text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 font-bold rounded-xl text-xs px-3.5 py-2.5 text-center transition-all">
                                {{ __('auth/authentication.Change Phone') }}
                            </button>
                        </div>
                        @error('resetPasswordPhone')
                            <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1">
                                <span class="material-icons text-xs">cancel</span>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Reset Password Code --}}
                    <div x-show="showResetPasswordCode && !showResetPassword" x-transition>
                        <label for="resetPasswordCode" class="block mb-1.5 text-xs font-bold text-gray-800">
                            {{ __('auth/authentication.Reset Password Code') }}
                        </label>
                        <div class="relative w-full rounded-xl">
                            <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 ltr:pl-3.5 rtl:pr-3.5 flex items-center pointer-events-none text-gray-400">
                                <span class="material-icons text-base">pin</span>
                            </div>
                            <input type="text" wire:model="resetPasswordCode" id="resetPasswordCode" dir="ltr"
                                class="block w-full ltr:pl-10 rtl:pr-10 py-2.5 px-3.5 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all tracking-widest text-center font-bold"
                                placeholder="{{ __('auth/authentication.Enter Your Reset Password Code') }}" required>
                        </div>
                        @error('resetPasswordCode')
                            <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1">
                                <span class="material-icons text-xs">cancel</span>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Reset Password Inputs --}}
                    <div x-show="showResetPassword" x-transition class="space-y-4">
                        {{-- New Password --}}
                        <div>
                            <label for="newPassword" class="block mb-1.5 text-xs font-bold text-gray-800">
                                {{ __('auth/authentication.New Password') }}
                            </label>
                            <div class="relative w-full rounded-xl">
                                <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 ltr:pl-3.5 rtl:pr-3.5 flex items-center pointer-events-none text-gray-400">
                                    <span class="material-icons text-base">lock</span>
                                </div>
                                <input type="password" wire:model="newPassword" id="newPassword"
                                    class="block w-full ltr:pl-10 rtl:pr-10 py-2.5 px-3.5 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                    placeholder="{{ __('auth/authentication.Enter Your New Password') }}" required>
                            </div>
                            @error('newPassword')
                                <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1">
                                    <span class="material-icons text-xs">cancel</span>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        {{-- New Password Confirmation --}}
                        <div>
                            <label for="newPasswordConfirmation" class="block mb-1.5 text-xs font-bold text-gray-800">
                                {{ __('auth/authentication.New Password Confirmation') }}
                            </label>
                            <div class="relative w-full rounded-xl">
                                <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 ltr:pl-3.5 rtl:pr-3.5 flex items-center pointer-events-none text-gray-400">
                                    <span class="material-icons text-base">lock_reset</span>
                                </div>
                                <input type="password" wire:model="newPassword_confirmation" id="newPasswordConfirmation"
                                    class="block w-full ltr:pl-10 rtl:pr-10 py-2.5 px-3.5 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                    placeholder="{{ __('auth/authentication.Enter Your New Password Confirmation') }}" required>
                            </div>
                            @error('newPassword_confirmation')
                                <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1">
                                    <span class="material-icons text-xs">cancel</span>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div x-show="!showResetPasswordCode" x-transition>
                    <button type="button" wire:click="sendResetPasswordCode" wire:loading.attr="disabled"
                        class="w-full flex items-center justify-center gap-2 text-white bg-gradient-to-r from-primary to-primaryDark hover:from-primaryDark hover:to-primaryDarker shadow-md shadow-primary/25 font-bold rounded-xl text-sm px-5 py-3 text-center transition-all">
                        <span>{{ __('auth/authentication.Send Password Reset code') }}</span>
                        <span class="material-icons text-base rtl:rotate-180">send</span>
                    </button>
                </div>

                <div x-show="showResetPasswordCode && !showResetPassword" x-transition>
                    <button type="button" wire:click="checkResetPasswordCode" wire:loading.attr="disabled"
                        class="w-full flex items-center justify-center gap-2 text-white bg-gradient-to-r from-primary to-primaryDark hover:from-primaryDark hover:to-primaryDarker shadow-md shadow-primary/25 font-bold rounded-xl text-sm px-5 py-3 text-center transition-all">
                        <span>{{ __('auth/authentication.Confirm Code') }}</span>
                        <span class="material-icons text-base">check_circle</span>
                    </button>
                </div>

                <div x-show="showResetPassword" x-transition>
                    <button type="button" wire:click="resetPassword" wire:loading.attr="disabled"
                        class="w-full flex items-center justify-center gap-2 text-white bg-gradient-to-r from-primary to-primaryDark hover:from-primaryDark hover:to-primaryDarker shadow-md shadow-primary/25 font-bold rounded-xl text-sm px-5 py-3 text-center transition-all">
                        <span>{{ __('auth/authentication.Confirm Password') }}</span>
                        <span class="material-icons text-base">done_all</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Hide Reset Password Modal
        window.addEventListener('hideResetPasswordModal', function() {
            $('#close-forgot-password-modal').click();
        });
    </script>
@endpush
