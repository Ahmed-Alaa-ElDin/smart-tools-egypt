<?php

namespace App\Http\Livewire\Front\Order;

use App\Models\Coupon;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EditOrder extends Component
{
    public $order, $products;

    public function mount()
    {
    }

    public function render()
    {
        return view('livewire.front.orders.edit-order');
    }
}
