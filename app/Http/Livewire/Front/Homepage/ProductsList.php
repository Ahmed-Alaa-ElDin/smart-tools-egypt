<?php

namespace App\Http\Livewire\Front\Homepage;

use Livewire\Component;

class ProductsList extends Component
{
    public $section;
    public $key;
    public $amount;

    ############## Mount :: Start ##############
    public function mount()
    {
        $this->products = $this->section->finalProducts->toArray();
    }
    ############## Mount :: End ##############

    ############## Render Section :: Start ##############
    public function render()
    {
        return view('livewire.front.homepage.products-list');
    }
    ############## Render Section :: End ##############
}
