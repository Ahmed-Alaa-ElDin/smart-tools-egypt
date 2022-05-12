<?php

namespace App\Http\Livewire\Admin\Homepage\Todaydeals;

use App\Models\Product;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class TodayDealsList extends Component
{
    use WithPagination;

    public $addProduct = 0;

    public $product_id;
    public $products_list, $searchProduct = '', $showResult = 1;

    public $search = "";

    protected $listeners = ['showResults'];

    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');
    }

    public function render()
    {
        $products = Product::select(['id', 'name', 'base_price', 'final_price','points', 'free_shipping', 'today_deal'])
            ->with(['thumbnail'])
            ->where('today_deal', '>', 0)
            ->where('under_reviewing', '=', 0)
            ->where(function ($q) {
                $q->where('name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('base_price', 'like', '%' . $this->search . '%')
                    ->orWhere('final_price', 'like', '%' . $this->search . '%')
                    ->orWhere('points', 'like', '%' . $this->search . '%');
            })
            ->orderBy('today_deal')
            ->paginate($this->perPage);

        $this->products_list = Product::select(['id', 'name', 'base_price', 'final_price', 'free_shipping', 'today_deal', 'brand_id'])
            ->with(['brand' => function ($q) {
                $q->select('id', 'name');
            }])
            ->where('under_reviewing', '=', 0)
            ->where('today_deal', '=', 0)
            ->where(function ($q) {
                $q->where('name->ar', 'like', '%' . $this->searchProduct . '%')
                    ->orWhere('name->en', 'like', '%' . $this->searchProduct . '%')
                    ->orWhere('base_price', 'like', '%' . $this->searchProduct . '%')
                    ->orWhere('final_price', 'like', '%' . $this->searchProduct . '%')
                    ->orWhere('points', 'like', '%' . $this->searchProduct . '%');
            })
            ->get();

        return view('livewire.admin.homepage.todaydeals.today-deals-list', compact('products'));
    }

    ######## reset pagination after new search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    ######## Check Rank : Start ########
    public function checkRank($today_deal, $old_today_deal)
    {
        $product = Product::where('today_deal', $today_deal)->first();

        if ($product) {
            $product->today_deal = $old_today_deal;
            $product->save();
        } else {
            return 0;
        }
    }
    ######## Check Rank : End ########

    ######## Rank UP : Start #########
    public function rankUp($product_id)
    {
        $product = Product::findOrFail($product_id);

        if ($product->today_deal > 1) {
            if ($product->today_deal == 127) {
                $this->checkRank(11, $product->today_deal);
                $product->today_deal = 11;
            } else {
                $this->checkRank($product->today_deal - 1, $product->today_deal);
                $product->today_deal--;
            }
            $product->save();
        }
    }
    ######## Rank UP : End #########

    ######## Rank Down : Start #########
    public function rankDown($product_id)
    {
        $product = Product::findOrFail($product_id);

        $this->checkRank($product->today_deal + 1, $product->today_deal);

        if ($product->today_deal < 12) {
            if ($product->today_deal == 11) {
                $product->today_deal = 127;
            } else {
                $product->today_deal++;
            }
            $product->save();
        }
    }
    ######## Rank Down : End #########

    ######## Deleted #########
    public function removeProduct($product_id)
    {
        try {
            $product = Product::findOrFail($product_id);

            $product->update([
                'today_deal' => 0
            ]);

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/sitePages.Product has been removed from list successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/sitePages.Product hasn't been removed from list"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Deleted #########

    ######## Product Selected : Start ########
    public function productSelected($product_id, $product_name)
    {
        $this->searchProduct = $product_name;
        $this->product_id = $product_id;
        $this->showResult = 0;
    }
    ######## Product Selected : End ########

    ######## Show Results : Start ########
    public function showResults($status)
    {
        $this->showResult = $status;
    }
    ######## Show Results : End ########

    ######## Save : Start #########
    public function add()
    {
        try {
            Product::findOrFail($this->product_id)->update(['today_deal' => 127]);

            $this->searchProduct = null;
            $this->product_id = null;

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/sitePages.Product has been added to the list successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/sitePages.Product hasn't been added to the list"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Save : End #########
}
