<?php

namespace App\Http\Livewire\Admin\Homepage\Sections;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsListForm extends Component
{
    use WithPagination;

    public $addProduct = 0;

    public $product_id;
    public $products_list, $searchProduct = '', $showResult = 1;
    public $products = [];

    public $search = "";

    protected $listeners = ['showResults'];

    ######## Fires once in the beginning : Start ########
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');
    }
    ######## Fires once in the beginning : End ########

    ######## Fires with each update : Start ########
    public function render()
    {
        $products = usort($this->products, function ($a, $b) {
            return $a['rank'] <=> $b['rank'];
        });

        $this->products_list = Product::select(['id', 'name', 'base_price', 'final_price', 'free_shipping', 'brand_id', 'points'])
            ->with(['brand' => function ($q) {
                $q->select('id', 'name');
            }])
            ->where('under_reviewing', '=', 0)
            ->whereNotIn('id', array_map(fn ($product) => $product['id'], $this->products))
            ->where(function ($q) {
                $q->where('name->ar', 'like', '%' . $this->searchProduct . '%')
                    ->orWhere('name->en', 'like', '%' . $this->searchProduct . '%')
                    ->orWhere('base_price', 'like', '%' . $this->searchProduct . '%')
                    ->orWhere('final_price', 'like', '%' . $this->searchProduct . '%')
                    ->orWhere('points', 'like', '%' . $this->searchProduct . '%');
            })
            ->get();

        return view('livewire.admin.homepage.sections.products-list-form', compact('products'));
    }
    ######## Fires with each update : Start ########

    ######## reset pagination after new search : Start ########
    public function updatingSearch()
    {
        $this->resetPage();
    }
    ######## reset pagination after new search : End ########

    ######## Check Rank : Start ########
    public function checkRank($rank, $old_rank)
    {
        $product_key = array_search($rank, array_column($this->products, 'rank'));

        if ($product_key !== false) {
            $this->products[$product_key]['rank'] = $old_rank;
        }
    }
    ######## Check Rank : End ########

    ######## Rank UP : Start #########
    public function rankUp($product_id)
    {
        $product_key = array_search($product_id, array_column($this->products, 'id'));

        if ($this->products[$product_key]['rank'] > 1) {
            if ($this->products[$product_key]['rank'] == 127) {
                $this->checkRank(11, $this->products[$product_key]['rank']);
                $this->products[$product_key]['rank'] = 11;
            } else {
                $this->checkRank($this->products[$product_key]['rank'] - 1, $this->products[$product_key]['rank']);
                $this->products[$product_key]['rank']--;
            }
        }

        $this->emitTo('admin.homepage.sections.section-form', 'listUpdated', ['selected_products' => $this->products]);
    }
    ######## Rank UP : End #########

    ######## Rank Down : Start #########
    public function rankDown($product_id)
    {
        $product_key = array_search($product_id, array_column($this->products, 'id'));

        $this->checkRank($this->products[$product_key]['rank'] + 1, $this->products[$product_key]['rank']);

        if ($this->products[$product_key]['rank'] < 12) {
            if ($this->products[$product_key]['rank'] == 11) {
                $this->products[$product_key]['rank'] = 127;
            } else {
                $this->products[$product_key]['rank']++;
            }
        }

        $this->emitTo('admin.homepage.sections.section-form', 'listUpdated', ['selected_products' => $this->products]);
    }
    ######## Rank Down : End #########

    ######## Deleted #########
    public function removeProduct($product_id)
    {
        try {
            $product_key = array_search($product_id, array_column($this->products, 'id'));

            unset($this->products[$product_key]);

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/sitePages.Product has been removed from list successfully'),
                'icon' => 'success'
            ]);

            $this->emitTo('admin.homepage.sections.section-form', 'listUpdated', ['selected_products' => $this->products]);
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

    ######## Add Product to List : Start #########
    public function add()
    {
        try {
            $product = Product::select(['id', 'name', 'base_price', 'final_price', 'points', 'under_reviewing'])->with(['thumbnail'])->findOrFail($this->product_id)->toArray();
            $product['rank'] = 127;

            $this->searchProduct = null;
            $this->product_id = null;

            $this->products[] = $product;

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/sitePages.Product has been added to the list successfully'),
                'icon' => 'success'
            ]);

            $this->emitTo('admin.homepage.sections.section-form', 'listUpdated', ['selected_products' => $this->products]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/sitePages.Product hasn't been added to the list"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Add Product to List : End #########
}
