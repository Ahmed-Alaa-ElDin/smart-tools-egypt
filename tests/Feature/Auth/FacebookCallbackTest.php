<?php

namespace Tests\Feature\Auth;

use App\Http\Controllers\Front\Auth\AuthenticatedSessionController;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Tests\TestCase;

class FacebookCallbackTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // The application loads auth routes with require_once, so register the target per test.
        Route::get('/test/login', fn () => 'Login')->name('login');

        // Exercise the callback with sessions, without unrelated storefront database middleware.
        Route::middleware([
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        ])->get('/test/facebook/callback', [AuthenticatedSessionController::class, 'facebookCallback']);
        Route::getRoutes()->refreshNameLookups();
    }

    public function test_cancellation_redirects_without_contacting_facebook(): void
    {
        Socialite::shouldReceive('driver')->never();

        $this->get('/test/facebook/callback?error=access_denied&error_reason=user_denied')
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['auth' => __('auth.facebook_canceled')]);

        $this->assertGuest();
    }

    public function test_missing_code_redirects_without_contacting_facebook(): void
    {
        Socialite::shouldReceive('driver')->never();

        $this->get('/test/facebook/callback')
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['auth' => __('auth.facebook_failed')]);

        $this->assertGuest();
    }

    public function test_invalid_state_redirects_to_login(): void
    {
        Socialite::shouldReceive('driver')->with('facebook')->once()->andReturnSelf();
        Socialite::shouldReceive('user')->once()->andThrow(new InvalidStateException());

        $this->get('/test/facebook/callback?code=test-code')
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['auth' => __('auth.facebook_failed')]);

        $this->assertGuest();
    }

    public function test_facebook_connection_failure_redirects_to_login(): void
    {
        Socialite::shouldReceive('driver')->with('facebook')->once()->andReturnSelf();
        Socialite::shouldReceive('user')->once()->andThrow(
            new ConnectException('Connection failed', new Request('POST', 'https://graph.facebook.com/oauth/access_token'))
        );

        $this->get('/test/facebook/callback?code=test-code')
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['auth' => __('auth.facebook_failed')]);

        $this->assertGuest();
    }
}
