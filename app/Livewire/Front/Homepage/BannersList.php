<?php

namespace App\Livewire\Front\Homepage;

use Livewire\Component;

class BannersList extends Component
{
    public $section;

    public function render()
    {
        $banners = $this->section->banners;

        return view('livewire.front.homepage.banners-list', compact('banners'));
    }
}
