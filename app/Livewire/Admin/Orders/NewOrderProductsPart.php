<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Product;
use Livewire\Component;
use App\Models\Collection;

class NewOrderProductsPart extends Component
{
    public $search = "";
    public $product_id;
    public $products = [];
    public $products_list;



    protected $listeners = [
        'clearSearch',
    ];

    public function mount()
    {
        $this->products_list = [];
    }


    public function render()
    {
        return view('livewire.admin.orders.new-order-products-part');
    }

    public function updatedSearch()
    {
        // Early return if search is empty
        if (empty($this->search)) {
            $this->products_list = collect([]);
            return;
        }

        $selectedProducts = array_column(array_filter($this->products, fn($product) => $product['type'] == "Product"), 'id');
        $selectedCollections = array_column(array_filter($this->products, fn($product) => $product['type'] == "Collection"), 'id');

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
        ])
            ->with(
                'brand',
            )
            ->whereNotIn('id', $selectedProducts)
            ->where(
                fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('barcode', 'like', '%' . $this->search . '%')
                    ->orWhere('original_price', 'like', '%' . $this->search . '%')
                    ->orWhere('base_price', 'like', '%' . $this->search . '%')
                    ->orWhere('final_price', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('model', 'like', '%' . $this->search . '%')
                    ->orWhereHas('brand', fn($q) => $q->where('brands.name', 'like', '%' . $this->search . '%'))
            )
            ->where("publish",  1)
            ->where("quantity", ">", 0)
            ->get();

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
            'model',
        ])
            ->whereNotIn('id', $selectedCollections)
            ->where(
                fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('barcode', 'like', '%' . $this->search . '%')
                    ->orWhere('original_price', 'like', '%' . $this->search . '%')
                    ->orWhere('base_price', 'like', '%' . $this->search . '%')
                    ->orWhere('final_price', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('model', 'like', '%' . $this->search . '%')
            )
            ->where("publish",  1)
            ->get()
            ->filter(fn($collection) => $collection->quantity > 0);

        $this->products_list = $collections->concat($products)->toArray();
    }

    public function clearSearch()
    {
        $this->search = null;
        $this->products_list = collect([]);
    }

    public function addProduct($product_id, $product_collection)
    {
        if ($product_collection == 'Product') {
            $product = Product::with('thumbnail')->findOrFail($product_id)->toArray();
            $product['amount'] = 1;
            $this->products[] = $product;
        } elseif ($product_collection == 'Collection') {
            $collection = Collection::with('thumbnail')->findOrFail($product_id)->toArray();
            $collection['amount'] = 1;
            $this->products[] = $collection;
        }

        $this->dispatch('setProductsData', [
            'products' => $this->products
        ]);

        $this->clearSearch();
    }

    public function clearProducts()
    {
        $this->products = [];
    }

    public function amountUpdated($product_id, $product_collection, $amount)
    {
        $productId = array_key_first(array_filter($this->products, fn($product) => $product['id'] == $product_id && $product['type'] == $product_collection));

        if ($amount > 0 && $this->products[$productId]['quantity'] > 0) {
            $this->products[$productId]['amount'] = $amount <= $this->products[$productId]['quantity'] ? $amount : $this->products[$productId]['quantity'];
        } else {
            unset($this->products[$productId]);
        }

        $this->dispatch('setProductsData', [
            'products' => $this->products
        ]);
    }
}
