<?php

namespace App\Http\Livewire\Front\Homepage;

use App\Models\HomepageBanner;
use App\Models\Supercategory;
use Livewire\Component;

class MainSlider extends Component
{
    public function mount()
    {
        $this->banners = HomepageBanner::where('rank', '<=', 10)->orderBy('rank')->get();

        $this->topsupercategories = Supercategory::select('id', 'name', 'icon', 'top')->with([
            'categories' => function ($q)
            {
                $q->select('id','name','supercategory_id')
                ->with(['subcategories'=> function ($q)
                {
                    $q->select('id','name','category_id');
                }]);
            }
        ])->where('top', '>', 0)->orderBy('top')->get();

        // dd($this->topsupercategories);
    }
}
