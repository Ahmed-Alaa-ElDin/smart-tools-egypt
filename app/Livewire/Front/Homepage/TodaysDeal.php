<?php

namespace App\Livewire\Front\Homepage;

use Livewire\Component;

class TodaysDeal extends Component
{
    public $section;
    public $key;
    public $amount;
    public $items;

    ############## Mount :: Start ##############
    public function mount()
    {
        $this->items = $this->section && $this->section->finalItems ? $this->section->finalItems->toArray() : [];
    }
    ############## Mount :: End ##############

    ############## Render Section :: Start ##############
    public function render()
    {
        return view('livewire.front.homepage.todays-deal');
    }
    ############## Render Section :: End ##############
}
