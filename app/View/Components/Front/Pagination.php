<?php

namespace App\View\Components\Front;

use Illuminate\View\Component;

class Pagination extends Component
{
    public $totalPages;
    public $currentPage;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($totalPages, $currentPage)
    {
        $this->totalPages = $totalPages;
        $this->currentPage = $currentPage;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.front.pagination');
    }
}
