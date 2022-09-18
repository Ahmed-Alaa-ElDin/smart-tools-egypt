<?php

namespace App\View\Components\Admin\Orders;

use Illuminate\View\Component;

class OrderProductsList extends Component
{
    public $products;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($products)
    {
        $this->products = $products;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.orders.order-products-list');
    }
}
