<?php

namespace App\Http\Livewire\Front\Homepage;

use Livewire\Component;

class TopCategories extends Component
{
    public $categories;

    public function render()
    {
        return view('livewire.front.homepage.top-categories');
    }
}
