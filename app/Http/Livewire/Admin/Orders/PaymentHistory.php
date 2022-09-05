<?php

namespace App\Http\Livewire\Admin\Orders;

use Livewire\Component;

class PaymentHistory extends Component
{
    public $order;

    public function render()
    {
        return view('livewire.admin.orders.payment-history');
    }
}