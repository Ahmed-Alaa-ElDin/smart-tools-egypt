<?php

namespace App\View\Components\Admin\Orders;

use Illuminate\View\Component;

class OrderPaymentHistoryView extends Component
{
    public $payments;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.orders.order-payment-history-view');
    }
}
