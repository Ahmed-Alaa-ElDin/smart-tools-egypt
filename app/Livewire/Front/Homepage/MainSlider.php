<?php

namespace App\Livewire\Front\Homepage;

use Livewire\Component;

class MainSlider extends Component
{
    public $banners;

    public function render()
    {
        return view('livewire.front.homepage.main-slider');
    }
}