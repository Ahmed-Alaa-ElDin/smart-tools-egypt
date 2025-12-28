<?php

namespace App\Livewire\Front\Homepage;

use Livewire\Component;

class AllCategoriesLarge extends Component
{

    public $topSupercategories;
    public function render()
    {
        return view('livewire.front.homepage.all-categories-large');
    }
}