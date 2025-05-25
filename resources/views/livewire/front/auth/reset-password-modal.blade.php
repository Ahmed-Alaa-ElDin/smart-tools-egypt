<div id="forgot-password-modal" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden shadow-lg overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    {{ __('auth/authentication.Reset Password') }}
                </h3>
                <button type="button"
                    id="close-forgot-password-modal"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    data-modal-hide="forgot-password-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5" x-data="{ showResetPasswordCode: @entangle('showResetPasswordCode'), showResetPassword: @entangle('showResetPassword') }">
                <div class="flex flex-col gap-4 mb-4">
                    {{-- Phone --}}
                    <div x-show="!showResetPassword" x-transition>
                        <label for="resetPasswordPhone" class="block mb-2 text-sm font-medium text-gray-900">
                            {{ __('auth/authentication.Phone') }}
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="text" wire:model="resetPasswordPhone" id="resetPasswordPhone" dir="ltr"
                                class="grow-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                placeholder="{{ __('auth/authentication.Enter Your Phone Number') }}" required
                                :disabled="showResetPasswordCode">
                            <button type="button" wire:click="changePhone" x-show="showResetPasswordCode"
                                class="shrink-0 text-white bg-primary hover:bg-primaryDark focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                {{ __('auth/authentication.Change Phone') }}
                            </button>
                        </div>
                        @error('resetPasswordPhone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Reset Password Code --}}
                    <div x-show="showResetPasswordCode && !showResetPassword" x-transition>
                        <label for="resetPasswordCode" class="block mb-2 text-sm font-medium text-gray-900">
                            {{ __('auth/authentication.Reset Password Code') }}
                        </label>
                        <input type="text" wire:model="resetPasswordCode" id="resetPasswordCode" dir="ltr"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="{{ __('auth/authentication.Enter Your Reset Password Code') }}" required>
                        @error('resetPasswordCode')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Reset Password --}}
                    <div x-show="showResetPassword" x-transition>
                        {{-- New Password --}}
                        <div>
                            <label for="newPassword" class="block mb-2 text-sm font-medium text-gray-900">
                                {{ __('auth/authentication.New Password') }}
                            </label>
                            <input type="password" wire:model="newPassword" id="newPassword"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                placeholder="{{ __('auth/authentication.Enter Your New Password') }}" required>
                            @error('newPassword')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div x-show="showResetPassword" x-transition>
                        {{-- New Password Confirmation --}}
                        <div>
                            <label for="newPasswordConfirmation" class="block mb-2 text-sm font-medium text-gray-900">
                                {{ __('auth/authentication.New Password Confirmation') }}
                            </label>
                            <input type="password" wire:model="newPassword_confirmation" id="newPasswordConfirmation"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                placeholder="{{ __('auth/authentication.Enter Your New Password Confirmation') }}"
                                required>
                            @error('newPassword_confirmation')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div x-show="!showResetPasswordCode" x-transition>
                    <button type="button" wire:click="sendResetPasswordCode"
                        class="w-full text-white bg-primary hover:bg-primaryDark focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        {{ __('auth/authentication.Send Password Reset code') }}
                    </button>
                </div>
                <div x-show="showResetPasswordCode && !showResetPassword" x-transition>
                    <button type="button" wire:click="checkResetPasswordCode"
                        class="w-full text-white bg-primary hover:bg-primaryDark focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        {{ __('auth/authentication.Confirm Code') }}
                    </button>
                </div>
                <div x-show="showResetPassword" x-transition>
                    <button type="button" wire:click="resetPassword"
                        class="w-full text-white bg-primary hover:bg-primaryDark focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        {{ __('auth/authentication.Confirm Password') }}
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
