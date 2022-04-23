<?php

namespace App\Http\Livewire\Front\Homepage;

use App\Models\HomepageBanner;
use Livewire\Component;

class MainSlider extends Component
{
    public function render()
    {
        $banners = HomepageBanner::where('rank', '<=', 10)->orderBy('rank')->get();

        return view('livewire.front.homepage.main-slider', compact('banners'));
    }
}
