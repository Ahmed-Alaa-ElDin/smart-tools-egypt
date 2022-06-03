<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequestAdmin;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthControllerAdmin extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequestAdmin  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequestAdmin $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        try {
            $user = auth()->user();

            ############ Edit Last Visit Date & Visit Count :: Start ############
            $user->last_visit_at = now();
            $user->visit_num += 1;
            $user->save();
            ############ Edit Last Visit Date & Visit Count :: End ############

            ############ Restore Cart Data :: Start ############
            $this->cart = Cart::instance('cart')->restore(Auth::user()->id);
            ############ Restore Cart Data :: End ############
        } catch (\Throwable $th) {
        }

        return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
