<?php

namespace App\Http\Livewire\Front\Homepage;

use App\Models\Banner;
use Livewire\Component;

class TopBanner extends Component
{
    public function mount()
    {
        $this->banner = Banner::where('top_banner', 1)->first();
    }

    public function render()
    {
        return view('livewire.front.homepage.top-banner');
    }
}
