<?php

namespace App\Livewire\Front\Homepage;

use Livewire\Component;

class OffersProductsList extends Component
{
    public $section, $flash_sale, $items;

    ############## Mount :: Start ##############
    public function mount($section, $flash_sale)
    {
        $this->items = $section->offer->finalItems->toArray();

        $this->flash_sale = $flash_sale;

    }
    ############## Mount :: End ##############

    public function render()
    {
        return view('livewire.front.homepage.offers-products-list');
    }
}
