<?php

namespace App\Livewire\Front\Order\OrderForm;

use Livewire\Component;
use App\Enums\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Front\Order\OrderForm\Wrapper;

class PaymentSection extends Component
{
    public $payment_methods = [];
    public $selected_method_id;
    public $points = 0;
    public $points_egp = 0;
    public $balance = 0;

    public function rules()
    {
        return [
            'points' => 'numeric|min:0|integer|max:' . (Auth::user()->valid_points ?? 0),
            'balance' => 'numeric|min:0|max:' . (Auth::user()->balance ?? 0),
        ];
    }

    public function mount()
    {
        // For now, hardcode or fetch from DB/Enum
        $this->payment_methods = [
            ['id' => PaymentMethod::Cash->value, 'name' => __('front/homePage.Cash on Delivery'), 'icon' => 'payments', 'desc' => __('front/homePage.Pay when you receive your order')],
            ['id' => PaymentMethod::ElectronicWallet->value, 'name' => __('front/homePage.E-Wallet'), 'icon' => 'account_balance_wallet', 'desc' => __('front/homePage.Vodafone Cash, Fawry, etc.')],
            ['id' => PaymentMethod::Flash->value, 'name' => __('front/homePage.Flash'), 'icon' => 'credit_card', 'desc' => __('front/homePage.Pay securely with your credit/debit card')],
        ];

        $this->selected_method_id = PaymentMethod::Cash->value;
        $this->selectMethod($this->selected_method_id);
    }

    public function updatedPoints($points)
    {
        if (!Auth::check())
            return;
        $this->validateOnly('points');
        $this->points_egp = $points * config('settings.points_conversion_rate');
        $this->dispatch('pointsUpdated', points: $this->points, points_egp: $this->points_egp)->to(Wrapper::class);
    }

    public function updatedBalance($balance)
    {
        if (!Auth::check())
            return;
        $this->validateOnly('balance');
        $this->dispatch('balanceUpdated', balance: $this->balance)->to(Wrapper::class);
    }

    public function selectMethod($id)
    {
        $this->selected_method_id = $id;
        $this->dispatch('paymentMethodSelected', $id)->to(Wrapper::class);
    }

    public function render()
    {
        return view('livewire.front.order.order-form.payment-section');
    }
}
