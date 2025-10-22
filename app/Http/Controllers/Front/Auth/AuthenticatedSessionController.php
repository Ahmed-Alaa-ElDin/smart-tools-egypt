<?php

namespace App\Http\Controllers\Front\Auth;

use App\Models\User;
use App\Facades\MetaPixel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Auth\LoginRequest;
use Laravel\Socialite\Facades\Socialite;
use Gloudemans\Shoppingcart\Facades\Cart;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('front.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $locale = Session::get('locale');

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/' . $locale);
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function facebookRedirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function facebookCallback()
    {
        $user = Socialite::driver('facebook')->user();

        $createdUser = User::updateOrCreate(
            ['auth_id' => $user->id],
            [
                'f_name' => [
                    'ar' => explode(' ', $user->name, 2)[0],
                    'en' => explode(' ', $user->name, 2)[0]
                ],
                'l_name' => [
                    'ar' => explode(' ', $user->name, 2)[1],
                    'en' => explode(' ', $user->name, 2)[1]
                ],
                'email' => $user->email,
                'auth_type' => 'facebook',
                'last_visit_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        if ($createdUser->profile_photo_path == null) {
            $createdUser->update([
                'visit_num' => $createdUser->visit_num + 1,
                'profile_photo_path' => singleImageUpload($user->avatar_original, 'profile-', 'profiles'),
            ]);
        } else {
            $createdUser->update([
                'visit_num' => $createdUser->visit_num + 1,
            ]);
        }


        $createdUser->assignRole('Customer');

        Auth::login($createdUser, true);

        // Send Meta Pixel event
        $pixelParams = [
            "eventName" => 'CompleteRegistration',
            "userData" => [],
            "customData" => [],
            "eventId" => MetaPixel::generateEventId(),
        ];

        MetaPixel::sendEvent(
            $pixelParams['eventName'],
            $pixelParams['userData'],
            $pixelParams['customData'],
            $pixelParams['eventId']
        );

        return redirect()->intended(RouteServiceProvider::HOME)->with('pixelParams', $pixelParams);
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function googleCallback()
    {
        $user = Socialite::driver('google')->user();

        $createdUser = User::updateOrCreate(
            ['auth_id' => $user->id],
            [
                'f_name' => [
                    'ar' => explode(' ', $user->name, 2)[0],
                    'en' => explode(' ', $user->name, 2)[0]
                ],
                'l_name' => [
                    'ar' => explode(' ', $user->name, 2)[1],
                    'en' => explode(' ', $user->name, 2)[1]
                ],
                'email' => $user->email,
                'auth_type' => 'google',
                'profile_photo_path' => singleImageUpload($user->avatar_original, 'profile-', 'profiles'),
                'last_visit_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        $createdUser->update([
            'visit_num' => $createdUser->visit_num + 1,
        ]);

        $createdUser->assignRole('Customer');

        Auth::login($createdUser, true);

        // Send Meta Pixel event
        $pixelParams = [
            "eventName" => 'CompleteRegistration',
            "userData" => [],
            "customData" => [],
            "eventId" => MetaPixel::generateEventId(),
        ];

        MetaPixel::sendEvent(
            $pixelParams['eventName'],
            $pixelParams['userData'],
            $pixelParams['customData'],
            $pixelParams['eventId']
        );

        return redirect()->intended(RouteServiceProvider::HOME)->with('pixelParams', $pixelParams);
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function twitterRedirect()
    {
        return Socialite::driver('twitter')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function twitterCallback()
    {
        $user = Socialite::driver('twitter')->user();

        $createdUser = User::updateOrCreate(
            ['auth_id' => $user->id],
            [
                'f_name' => [
                    'ar' => explode(' ', $user->name, 2)[0],
                    'en' => explode(' ', $user->name, 2)[0]
                ],
                'l_name' => [
                    'ar' => explode(' ', $user->name, 2)[1],
                    'en' => explode(' ', $user->name, 2)[1]
                ],
                'email' => $user->email,
                'auth_type' => 'twitter',
                'profile_photo_path' => singleImageUpload($user->avatar_original, 'profile-', 'profiles'),
                'last_visit_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        $createdUser->update([
            'visit_num' => $createdUser->visit_num + 1,
        ]);

        $createdUser->assignRole('Customer');

        Auth::login($createdUser, true);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
