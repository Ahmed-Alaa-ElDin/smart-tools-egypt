<?php

namespace App\Livewire\Admin\Setting\Homepage\Todaydeals;

use App\Models\Collection;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class TodayDealsList extends Component
{
    public $items = [];

    public $search = "", $list = [];

    protected $listeners = ['clearSearch'];

    public function mount()
    {
        $this->section = Section::with([
            'products' => fn ($q) => $q
                ->select(['products.id', 'name', 'original_price', 'base_price', 'final_price', 'points', 'under_reviewing'])
                ->with(['thumbnail'])
                ->withPivot('rank'),
            'collections' => fn ($q) => $q
                ->select(['collections.id', 'name', 'original_price', 'base_price', 'final_price', 'points', 'under_reviewing'])
                ->with(['thumbnail'])
                ->withPivot('rank'),
        ])
            ->firstOrCreate([
                'title->en' =>  "Today's Deal"
            ], [
                'title' => [
                    "en" => "Today's Deal",
                    "ar" => "عرض اليوم"
                ],
                'type' => 0,
                'active' => 1,
                'rank' => 127,
                'today_deals' => 1
            ]);

        $products = $this->section->products->map(function ($product) {
            $product->rank = $product->pivot->rank;
            $product->type = 'Product';
            return $product;
        })->toArray();

        $collections = $this->section->collections->map(function ($collection) {
            $collection->rank = $collection->pivot->rank;
            $collection->type = 'Collection';
            return $collection;
        })->toArray();

        $this->items =  array_merge(
            $products,
            $collections
        );
    }

    public function render()
    {
        $products = usort($this->items, function ($a, $b) {
            return $a['rank'] <=> $b['rank'];
        });

        return view('livewire.admin.setting.homepage.todaydeals.today-deals-list', compact('products'));
    }

    ######## Search for product and collection : Start ########
    public function updatedSearch()
    {
        $products_id = array_map(fn ($item) => $item['id'], array_filter($this->items, fn ($item) => $item['type'] == 'Product'));

        $products = Product::select([
            'id',
            'name',
            'barcode',
            'original_price',
            'base_price',
            'final_price',
            'under_reviewing',
            'points',
            'description',
            'model',
            'brand_id'
        ])->with(
            'brand',
        )->whereNotIn(
            'id',
            $products_id
        )->where(
            fn ($q) =>
            $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('barcode', 'like', '%' . $this->search . '%')
                ->orWhere('original_price', 'like', '%' . $this->search . '%')
                ->orWhere('base_price', 'like', '%' . $this->search . '%')
                ->orWhere('final_price', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%')
                ->orWhere('model', 'like', '%' . $this->search . '%')
                ->orWhereHas('brand', fn ($q) => $q->where('brands.name', 'like', '%' . $this->search . '%'))
        )->get();

        $collections_id = array_map(fn ($item) => $item['id'], array_filter($this->items, fn ($item) => $item['type'] == 'Collection'));

        $collections = Collection::select([
            'id',
            'name',
            'barcode',
            'original_price',
            'base_price',
            'final_price',
            'under_reviewing',
            'points',
            'description',
            'model'
        ])->whereNotIn(
            'id',
            $collections_id
        )->where(
            fn ($q) =>
            $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('barcode', 'like', '%' . $this->search . '%')
                ->orWhere('original_price', 'like', '%' . $this->search . '%')
                ->orWhere('base_price', 'like', '%' . $this->search . '%')
                ->orWhere('final_price', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%')
                ->orWhere('model', 'like', '%' . $this->search . '%')
        )->get();

        $this->list = $collections->concat($products)->map(function ($product_collection) {
            $product_collection->product_collection = class_basename($product_collection);
            return $product_collection;
        })->toArray();
    }
    ######## Search for product and collection : Start ########

    // Clear search input :: Start
    public function clearSearch()
    {
        $this->list = [];

        $this->search = '';
    }
    // Clear search input :: End

    // Add Products or Collections to the item :: Start
    public function addProduct($product_id, $product_collection)
    {
        if ($product_collection == 'Product') {

            $product = Product::with('thumbnail')->select([
                'id',
                'name',
                'barcode',
                'original_price',
                'base_price',
                'final_price',
                'under_reviewing',
                'points',
                'description',
                'model',
                'brand_id'
            ])->find($product_id)->toArray();

            $product['rank'] = 127;
            $product['type'] = $product_collection;

            $this->items[] = $product;
        } elseif ($product_collection == 'Collection') {

            $collection = Collection::with('thumbnail')->select([
                'id',
                'name',
                'barcode',
                'original_price',
                'base_price',
                'final_price',
                'under_reviewing',
                'points',
                'description',
                'model',
            ])->find($product_id)->toArray();

            $collection['rank'] = 127;
            $collection['type'] = $product_collection;

            $this->items[] = $collection;
        }

        $this->dispatch('swalDone', text: __('admin/sitePages.Product has been added to the list successfully'),
            icon:'success');
    }
    // Add Products or Collections to the item :: End

    ######## Check Rank : Start ########
    public function checkRank($rank, $old_rank)
    {
        $product_key = null;

        array_map(function ($item) use ($rank, &$product_key) {
            if ($item['rank'] == $rank) {
                $product_key = array_search($item, $this->items);
            }
        }, $this->items);

        if ($product_key !== null) {
            $this->items[$product_key]['rank'] = $old_rank;
        }
    }
    ######## Check Rank : End ########

    ######## Rank UP : Start #########
    public function rankUp($product_id, $type = 'Product')
    {
        $product_key = null;

        array_map(function ($item) use ($product_id, $type, &$product_key) {
            if ($item['id'] == $product_id && $item['type'] == $type) {
                $product_key = array_search($item, $this->items);
            }
        }, $this->items);

        if ($this->items[$product_key]['rank'] > 1) {
            if ($this->items[$product_key]['rank'] == 127) {
                $this->checkRank(11, $this->items[$product_key]['rank']);
                $this->items[$product_key]['rank'] = 11;
            } else {
                $this->checkRank($this->items[$product_key]['rank'] - 1, $this->items[$product_key]['rank']);
                $this->items[$product_key]['rank']--;
            }
        }
    }
    ######## Rank UP : End #########

    ######## Rank Down : Start #########
    public function rankDown($product_id, $type = 'Product')
    {
        $product_key = null;

        array_map(function ($item) use ($product_id, $type, &$product_key) {
            if ($item['id'] == $product_id && $item['type'] == $type) {
                $product_key = array_search($item, $this->items);
            }
        }, $this->items);

        $this->checkRank($this->items[$product_key]['rank'] + 1, $this->items[$product_key]['rank']);

        if ($this->items[$product_key]['rank'] < 12) {
            if ($this->items[$product_key]['rank'] == 11) {
                $this->items[$product_key]['rank'] = 127;
            } else {
                $this->items[$product_key]['rank']++;
            }
        }
    }
    ######## Rank Down : End #########

    ######## Remove Product :: Start #########
    public function removeProduct($product_id)
    {
        try {
            $product_key = null;

            array_map(function ($item) use ($product_id, &$product_key) {
                if ($item['id'] == $product_id && $item['type'] == 'Product') {
                    $product_key = array_search($item, $this->items);
                }
            }, $this->items);

            unset($this->items[$product_key]);

            $this->dispatch('swalDone', text: __('admin/sitePages.Product has been removed from list successfully'),
                icon: 'success');

            $this->dispatch('listUpdated', ['selected_products' => $this->items])->to('admin.homepage.sections.section-form');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/sitePages.Product hasn't been removed from list"),
                icon: 'error');
        }
    }
    ######## Remove Product :: End #########

    ######## Remove Collection :: Start #########
    public function removeCollection($collection_id)
    {
        try {
            $collection_key = null;

            array_map(function ($item) use ($collection_id, &$collection_key) {
                if ($item['id'] == $collection_id && $item['type'] == 'Collection') {
                    $collection_key = array_search($item, $this->items);
                }
            }, $this->items);

            unset($this->items[$collection_key]);

            $this->dispatch('swalDone', text: __('admin/sitePages.Product has been removed from list successfully'),
                icon: 'success');

            $this->dispatch('listUpdated', ['selected_products' => $this->items])->to('admin.homepage.sections.section-form');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/sitePages.Product hasn't been removed from list"),
                icon: 'error');
        }
    }
    ######## Remove Collection :: End #########

    ######## Update : Start #########
    public function save()
    {
        // dd($this->items);
        DB::beginTransaction();

        try {
            $this->section->products()->detach();
            $this->section->collections()->detach();

            foreach ($this->items as $item) {
                if ($item['type'] == 'Product') {
                    $this->section->products()->attach($item['id'], ['rank' => $item['rank']]);
                } elseif ($item['type'] == 'Collection') {
                    $this->section->collections()->attach($item['id'], ['rank' => $item['rank']]);
                }
            }

            DB::commit();

            Session::flash('success', __('admin/sitePages.Section updated successfully'));
            redirect()->route('admin.setting.homepage');
        } catch (\Throwable $th) {
            DB::rollback();

            Session::flash('error', __("admin/sitePages.Section hasn't been updated"));
            redirect()->route('admin.setting.homepage');
        }
    }
    ######## Update : End #########

}
