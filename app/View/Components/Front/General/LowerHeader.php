<?php

namespace App\View\Components\Front\General;

use App\Models\NavLink;
use Illuminate\View\Component;

class LowerHeader extends Component
{
    public $nav_links;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->nav_links = NavLink::where('active', 1)->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.front.general.lower-header');
    }
}
