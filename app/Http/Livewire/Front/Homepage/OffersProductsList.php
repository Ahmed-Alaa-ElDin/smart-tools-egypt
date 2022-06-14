<?php

namespace App\Http\Livewire\Front\Homepage;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OffersProductsList extends Component
{
    public $section, $flash_sale;

    ############## Mount :: Start ##############
    public function mount($section, $flash_sale)
    {
        $this->products = $section->offer->finalProducts->toArray();

        $this->flash_sale = $flash_sale;

    }
    ############## Mount :: End ##############

    public function render()
    {
        return view('livewire.front.homepage.offers-products-list');
    }
}
