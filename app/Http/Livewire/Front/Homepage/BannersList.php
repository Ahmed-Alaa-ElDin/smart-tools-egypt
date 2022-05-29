<?php

namespace App\Http\Livewire\Front\Homepage;

use Livewire\Component;

class BannersList extends Component
{
    public $section;

    public function render()
    {
        return view('livewire.front.homepage.banners-list');
    }
}
