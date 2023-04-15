<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Models\Phone;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('front.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'f_name' => ['required', 'string', 'max:255'],
            'l_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'digits:11|regex:/^01[0-2,5]\d{1,8}$/', 'max:255', 'unique:phones'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'f_name' => [
                'ar' => $request->f_name,
                'en' => $request->f_name
            ],
            'l_name' => [
                'ar' => $request->l_name,
                'en' => $request->l_name
            ],
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'last_visit_at' => now(),
            'visit_num' => 1,
        ]);

        $user->assignRole('Customer');

        Phone::create([
            'phone' => $request->phone,
            'user_id' => $user->id,
            'default' => 1
        ]);

        ############ Restore Cart Data :: Start ############
        $this->cart = Cart::instance('cart')->store($user->id);
        $this->cart = Cart::instance('wishlist')->store($user->id);
        $this->cart = Cart::instance('compare')->store($user->id);
        ############ Restore Cart Data :: End ############

        event(new Registered($user));

        Auth::login($user, $request->remember);

        return redirect(RouteServiceProvider::HOME);
    }
}
