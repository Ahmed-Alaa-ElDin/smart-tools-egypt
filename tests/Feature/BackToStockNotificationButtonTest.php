<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Product;
use App\Models\BackToStockNotification;
use App\Livewire\Front\General\BackToStockNotification\BackToStockNotificationButton;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BackToStockNotificationButtonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_add_notification()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        Livewire::actingAs($user)
            ->test(BackToStockNotificationButton::class, ['item_id' => $product->id])
            ->call('addToNotify')
            ->assertSet('isNotified', true)
            ->assertDispatched('swalDone');

        $this->assertDatabaseHas('back_to_stock_notifications', [
            'user_id' => $user->id,
            'sent_at' => null,
            'phone' => null,
        ]);

        $this->assertDatabaseHas('back_to_stock_notifiables', [
            'notification_id'=> BackToStockNotification::latest()->first()->id,
            'notifiable_id' => $product->id,
            'notifiable_type' => Product::class
        ]);
    }

    /** @test */
    public function guest_user_can_add_notification_with_valid_phone()
    {
        $product = Product::factory()->create();
        $phone = '01212345678';

        Livewire::test(BackToStockNotificationButton::class, ['item_id' => $product->id])
            ->set('phone', $phone)
            ->call('addToNotify')
            ->assertSet('isNotified', true)
            ->assertDispatched('swalDone');

        $this->assertDatabaseHas('back_to_stock_notifications', [
            'phone' => $phone,
            'sent_at' => null,
            'user_id' => null,
        ]);

        $this->assertDatabaseHas('back_to_stock_notifiables', [
            'notification_id'=> BackToStockNotification::latest()->first()->id,
            'notifiable_id' => $product->id,
            'notifiable_type' => Product::class
        ]);
    }

    /** @test */
    // public function invalid_phone_number_shows_validation_error()
    // {
    //     $product = Product::factory()->create();
    //     $phone = '01000000000';

    //     Livewire::test(BackToStockNotificationButton::class, ['item_id' => $product->id])
    //         ->set('phone', $phone)
    //         ->call('addToNotify')
    //         ->assertHasErrors(['phone' => 'regex']);
    // }

    /** @test */
    public function guest_phone_is_stored_in_session()
    {
        $product = Product::factory()->create();
        $phone = '01212345678';

        Livewire::test(BackToStockNotificationButton::class, ['item_id' => $product->id])
            ->call('confirmGuestPhone', $phone)
            ->assertSet('phone', $phone)
            ->assertSessionHas('guest_phone', $phone);
    }

    /** @test */
    public function notification_fails_for_invalid_item()
    {
        Livewire::test(BackToStockNotificationButton::class, ['item_id' => 999])
            ->call('addToNotify')
            ->assertDispatched('swalDone');
    }
}
