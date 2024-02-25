<?php

namespace App\Livewire\Front\Homepage;

use Livewire\Component;
use App\Models\Subcategory;
use App\Models\Supercategory;
use App\Models\SubsliderBanner;
use App\Models\MainSliderBanner;
use App\Models\SubsliderSmallBanner;

class MainSlider extends Component
{
    public $todayDeals;
    public $banners, $topSupercategories, $subsliderBanners, $subsliderSmallBanners, $items;

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

        $this->subsliderBanners = SubsliderBanner::with("banner")
            ->where('rank', "<=", 4)
            ->orderBy("rank")
            ->get();

        $this->subsliderSmallBanners = SubsliderSmallBanner::with("banner")
            ->where('rank', "<=", 5)
            ->orderBy("rank")
            ->get();

        $this->items = $this->todayDeals && $this->todayDeals->finalItems ? $this->todayDeals->finalItems->toArray() : [];
    }
}
