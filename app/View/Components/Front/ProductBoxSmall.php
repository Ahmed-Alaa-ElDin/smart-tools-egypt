<?php

namespace App\View\Components\Front;

use Illuminate\View\Component;

class ProductBoxSmall extends Component
{
    public $product;
    public $key;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($product, $key)
    {
        $this->product = $product;
        $this->key = $key;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.front.product-box-small');
    }
}
