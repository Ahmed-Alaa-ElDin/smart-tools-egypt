<?php

namespace App\Livewire\Front\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Services\Front\Communication\SMSService;

class ResetPasswordModal extends Component
{
    public $resetPasswordPhone;
    public $resetPasswordCode;
    public $newPassword;
    public $newPassword_confirmation;
    public $showResetPasswordCode = false;
    public $showResetPassword = false;

    public function render()
    {
        return view('livewire.front.auth.reset-password-modal');
    }

    public function sendResetPasswordCode()
    {
        $this->validateForm();

        $this->sendResetPasswordCodeToUser();
    }

    private function validateForm()
    {
        $this->validate([
            'resetPasswordPhone' => [
                'required',
                'numeric',
                'digits:11',
                'starts_with:010,011,012,015',
                Rule::exists('phones', 'phone')->where('default', 1),
            ],
        ], [
            'resetPasswordPhone.exists' => __('auth/authentication.The phone number does not exist'),
        ]);
    }

    private function sendResetPasswordCodeToUser()
    {
        $user = User::whereHas('phones', function ($query) {
            $query->where('phone', $this->resetPasswordPhone)->where('default', 1);
        })->firstOrFail();

        $code = $this->generateResetPasswordCode();

        $user->update([
            'reset_password_code' => $code,
        ]);

        $result = $this->sendSMS($code);

        if ($result) {
            $this->dispatch(
                'swalDone',
                text: __('auth/authentication.Reset password code sent successfully'),
                icon: 'success'
            );
            $this->showResetPasswordCode = true;
        } else {
            $this->dispatch(
                'swalDone',
                text: __('auth/authentication.Reset password code sent failed'),
                icon: 'error'
            );
        }
    }

    private function generateResetPasswordCode()
    {
        $code = rand(100000, 999999);

        return $code;
    }

    private function sendSMS($code)
    {
        $message = __('auth/authentication.Reset password code', ['code' => $code]);

        $result = (new SMSService())->sendSMS($this->resetPasswordPhone, $message);

        return $result[0] && $result[0]['type'] == 'success';
    }

    public function changePhone()
    {
        $this->resetForm();
        $this->showResetPasswordCode = false;
    }

    public function checkResetPasswordCode()
    {
        $this->validate([
            'resetPasswordCode' => 'required|numeric|digits:6',
        ]);

        $user = User::whereHas('phones', function ($query) {
            $query->where('phone', $this->resetPasswordPhone)->where('default', 1);
        })->firstOrFail();

        if ($user->reset_password_code != $this->resetPasswordCode) {
            $this->dispatch(
                'swalDone',
                text: __('auth/authentication.Reset password code is incorrect'),
                icon: 'error'
            );
            return;
        }

        $this->showResetPassword = true;
    }

    public function resetPassword()
    {
        $this->validate([
            'newPassword' => ['required', 'string', 'confirmed', Password::defaults()],
        ]);

        $user = User::whereHas('phones', function ($query) {
            $query->where('phone', $this->resetPasswordPhone)->where('default', 1);
        })->firstOrFail();

        $user->update([
            'password' => bcrypt($this->newPassword),
            'reset_password_code' => null,
        ]);

        $this->dispatch(
            'swalDone',
            text: __('auth/authentication.Password reset successfully'),
            icon: 'success'
        );

        $this->resetForm();

        // Hide modal
        $this->dispatch('hideResetPasswordModal');
    }

    private function resetForm()
    {
        $this->reset([
            'resetPasswordPhone',
            'resetPasswordCode',
            'newPassword',
            'newPassword_confirmation',
        ]);
    }
}
