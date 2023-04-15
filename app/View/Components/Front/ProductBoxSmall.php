<?php

namespace App\View\Components\Front;

use Illuminate\View\Component;

class ProductBoxSmall extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public array $item, public bool $wishlist = false)
    {
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
