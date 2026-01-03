<?php

namespace App\Livewire\Front\Homepage;

use Livewire\Component;

use App\Models\Supercategory;

class AllCategoriesSmall extends Component
{
    public $topSupercategories;

    public function mount()
    {
        $this->topSupercategories = Supercategory::select('id', 'name', 'icon', 'top')->with([
            'categories' => function ($q) {
                $q->select('id', 'name', 'supercategory_id')
                    ->with([
                        'subcategories' => function ($q) {
                            $q->select('id', 'name', 'category_id');
                        }
                    ]);
            }
        ])->where('top', '>', 0)->orderBy('top')->get();
    }

    public function render()
    {
        return view('livewire.front.homepage.all-categories-small');
    }
}
