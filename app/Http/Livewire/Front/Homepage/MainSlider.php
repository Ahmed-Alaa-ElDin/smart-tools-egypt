<?php

namespace App\Http\Livewire\Front\Homepage;

use Livewire\Component;
use App\Models\Subcategory;
use App\Models\Supercategory;
use App\Models\MainSliderBanner;

class MainSlider extends Component
{
    public $todayDeals;
    public $banners, $topSupercategories, $topSubcategories, $items;

    public function mount()
    {
        $this->banners = MainSliderBanner::with("banner")
            ->where('rank', "<=", 10)
            ->orderBy("rank")
            ->get();

        $this->topSupercategories = Supercategory::select('id', 'name', 'icon', 'top')->with([
            'categories' => function ($q) {
                $q->select('id', 'name', 'supercategory_id')
                    ->with(['subcategories' => function ($q) {
                        $q->select('id', 'name', 'category_id');
                    }]);
            }
        ])->where('top', '>', 0)->orderBy('top')->get();

        $this->topSubcategories = Subcategory::select('id', 'name', 'image_name', 'top')->where('top', '>', 0)->orderBy('top')->take(5)->get();

        $this->items = $this->todayDeals && $this->todayDeals->finalItems ? $this->todayDeals->finalItems->toArray() : [];
    }
}
