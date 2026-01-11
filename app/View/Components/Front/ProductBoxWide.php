<?php

namespace App\View\Components\Front;

use Illuminate\View\Component;

class ProductBoxWide extends Component
{
    public $item;
    public $type;
    public $total;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item , $type = 'cart')
    {
        $this->item = $item;
        $this->type = $type;
        $this->total = $item['final_price'] * $item['quantity'];
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.front.product-box-wide');
    }
}
