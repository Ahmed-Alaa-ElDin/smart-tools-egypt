<?php

namespace App\Livewire\Front\Homepage;

use Livewire\Component;

class TopBrands extends Component
{
    public $brands;

    public function render()
    {
        return view('livewire.front.homepage.top-brands');
    }
}
