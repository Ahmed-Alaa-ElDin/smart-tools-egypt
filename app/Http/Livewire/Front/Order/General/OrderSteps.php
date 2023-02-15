<?php

namespace App\Http\Livewire\Front\Order\General;

use Livewire\Component;

class OrderSteps extends Component
{
    public $step;

    public function render()
    {
        return view('livewire.front.order.general.order-steps');
    }
}
