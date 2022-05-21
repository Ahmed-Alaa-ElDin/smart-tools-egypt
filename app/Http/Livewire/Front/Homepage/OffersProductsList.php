<?php

namespace App\Http\Livewire\Front\Homepage;

use Livewire\Component;

class OffersProductsList extends Component
{
    public $section, $flash_sale;

    public function render()
    {
        return view('livewire.front.homepage.offers-products-list');
    }
}
