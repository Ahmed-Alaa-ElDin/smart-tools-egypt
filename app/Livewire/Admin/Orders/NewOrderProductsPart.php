<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Product;
use Livewire\Component;

class NewOrderProductsPart extends Component
{
    public $search = "";
    public $product_id;
    public $products = [];
    public $products_list;



    protected $listeners = [
        'clearSearch',
        'getProductsData'
    ];

    public function mount()
    {
        $this->products_list = collect([]);
    }


    public function render()
    {
        return view('livewire.admin.orders.new-order-products-part');
    }

    public function updatedSearch()
    {
        if ($this->search != "") {
            $this->products_list = Product::select(['id', 'name', 'base_price', 'final_price', 'free_shipping', 'brand_id', 'points'])
                ->with(['brand' => function ($q) {
                    $q->select('id', 'name');
                }])
                ->where('under_reviewing', '=', 0)
                ->whereNotIn('id', array_map(fn ($product) => $product['id'], $this->products))
                ->where(function ($q) {
                    $q->where('name->ar', 'like', '%' . $this->search . '%')
                        ->orWhere('name->en', 'like', '%' . $this->search . '%')
                        ->orWhere('base_price', 'like', '%' . $this->search . '%')
                        ->orWhere('final_price', 'like', '%' . $this->search . '%')
                        ->orWhere('points', 'like', '%' . $this->search . '%');
                })
                ->get();
        } else {
            $this->products_list = collect([]);
        }
    }

    public function clearSearch()
    {
        $this->search = null;
        $this->products_list = collect([]);
    }

    public function addProduct($product_id)
    {
        $product = Product::with('thumbnail')->findOrFail($product_id)->toArray();
        $product['amount'] = 1;
        $this->products[$product_id] = $product;
    }

    public function clearProducts()
    {
        $this->products = [];
    }

    public function amountUpdated($product_id, $amount)
    {
        if ($amount > 0) {
            $this->products[$product_id]['amount'] = $amount <= $this->products[$product_id]['quantity'] ? $amount : $this->products[$product_id]['quantity'];
        } else {
            unset($this->products[$product_id]);
        }
    }

    public function getProductsData()
    {
        $this->dispatch('setProductsData', data: ['products' => $this->products])->to('admin.orders.order-form');
    }
}
