<?php

namespace App\Http\Requests\Auth;

use App\Models\Phone;
use App\Providers\RouteServiceProvider;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => ['required', 'numeric'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        try {
            $phone = Phone::with('user')->where('phone', $this->phone)->where('default', 1)->firstOrFail();

            if ($phone) {
                $user = $phone->user;
            }

            if ($user && Hash::check($this->password, $user->password)) {
                Auth::login($user, $this->remember);

                RateLimiter::clear($this->throttleKey());

                ############ Edit Last Visit Date & Visit Count :: Start ############
                $user->last_visit_at = now();
                $user->visit_num += 1;
                $user->save();
                ############ Edit Last Visit Date & Visit Count :: End ############

                ############ Restore Cart Data :: Start ############
                Cart::instance('cart')->restore(Auth::user()->id);
                Cart::instance('wishlist')->restore(Auth::user()->id);
                Cart::instance('compare')->restore(Auth::user()->id);
                ############ Restore Cart Data :: End ############

                return redirect()->intended(RouteServiceProvider::HOME);
            } else {
                $this->incrementLoginAttempts();

                throw ValidationException::withMessages([
                    'auth' => [
                        'The provided credentials are incorrect.',
                    ],
                ]);
            }
        } catch (\Throwable $th) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'auth' => __('auth.failed'),
            ]);
        }
        // if (!Auth::attempt($this->only('phone', 'password'), $this->boolean('remember'))) {
        //     RateLimiter::hit($this->throttleKey());

        //     throw ValidationException::withMessages([
        //         'phone' => __('auth.failed'),
        //     ]);
        // }

        // RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'auth' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('phone')) . '|' . $this->ip();
    }
}
