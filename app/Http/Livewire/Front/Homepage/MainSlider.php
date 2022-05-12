<?php

namespace App\Http\Livewire\Front\Homepage;

use App\Models\Banner;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Supercategory;
use Livewire\Component;

class MainSlider extends Component
{
    public function mount()
    {
        $this->banners = Banner::where('rank', '<=', 10)->orderBy('rank')->get();

        $this->topSupercategories = Supercategory::select('id', 'name', 'icon', 'top')->with([
            'categories' => function ($q) {
                $q->select('id', 'name', 'supercategory_id')
                    ->with(['subcategories' => function ($q) {
                        $q->select('id', 'name', 'category_id');
                    }]);
            }
        ])->where('top', '>', 0)->orderBy('top')->get();

        $this->topSubcategories = Subcategory::select('id', 'name', 'image_name', 'top')->where('top', '>', 0)->orderBy('top')->get();

        $this->todayDeals = Product::select(['id', 'name', 'base_price', 'final_price', 'free_shipping', 'today_deal', 'free_shipping', 'points'])
            ->with(['thumbnail'])
            ->where('today_deal', '>', 0)
            ->where('under_reviewing', '=', 0)
            ->orderBy('today_deal')
            ->get();;
    }
}
