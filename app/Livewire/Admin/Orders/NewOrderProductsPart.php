<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use App\Models\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;

class NewOrderProductsPart extends Component
{
    public $search = "";
    public $product_id;
    public $products = [];
    public $products_list;
    public $customerId;
    public $initialProducts = [];


    protected $listeners = [
        'clearSearch',
    ];

    public function mount()
    {
        $this->products_list = [];

        if (!empty($this->initialProducts)) {
            foreach ($this->initialProducts as $item) {
                $this->addProduct($item['id'], $item['type'], $item['amount'], true);
            }
        } elseif ($this->customerId) {
            $cart = Cart::where('identifier', $this->customerId)
                ->where('instance', 'cart')
                ->first();

            if ($cart) {
                foreach ($cart->content as $item) {
                    $productCollection = $item->associatedModel == Product::class ? "Product" : "Collection";

                    $this->addProduct($item->id, $productCollection, $item->qty);
                }
            }
        }
    }


    public function render()
    {
        return view('livewire.admin.orders.new-order-products-part');
    }

    public function updatedSearch()
    {
        $search = trim($this->search);

        if (blank($search)) {
            $this->products_list = collect([]);
            return;
        }

        $selectedProducts = array_column(array_filter($this->products, fn($product) => $product['type'] == "Product"), 'id');
        $selectedCollections = array_column(array_filter($this->products, fn($product) => $product['type'] == "Collection"), 'id');

        // Search in Products
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
            ->with(['brand','thumbnail'])
            ->whereNotIn('id', $selectedProducts)
            ->where('publish', 1)
            ->where('quantity', '>', 0)
            ->where(function ($q) use ($search) {
                $this->searchCallback($q);
                $q->orWhereHas('brand', fn($q) => $q->where('brands.name', 'like', "%{$search}%"));
            })
            ->limit(10)
            ->get();

        // Search in Collections
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
        ])
            ->with('thumbnail')
            ->whereNotIn('id', $selectedCollections)
            ->where('publish', 1)
            ->where(function ($q) use ($search) {
                $this->searchCallback($q);
            })
            ->limit(10)
            ->get();

        // Combine results
        $this->products_list = $collections->concat($products)->toArray();
    }

    private function searchCallback(Builder $query): Builder
    {
        return $query->where('name', 'like', "%{$this->search}%")
            ->orWhere('barcode', 'like', "%{$this->search}%")
            ->orWhere('original_price', 'like', "%{$this->search}%")
            ->orWhere('base_price', 'like', "%{$this->search}%")
            ->orWhere('final_price', 'like', "%{$this->search}%")
            ->orWhere('description', 'like', "%{$this->search}%")
            ->orWhere('model', 'like', "%{$this->search}%");
    }


    public function clearSearch()
    {
        $this->search = null;
        $this->products_list = collect([]);
    }

    public function addProduct($product_id, $product_collection, $amount = 1, $force = false)
    {
        if ($product_collection == 'Product') {
            $product = Product::withTrashed()->with('thumbnail')->findOrFail($product_id)->toArray();

            if (!$force) {
                if ($product['quantity'] <= 0) {
                    return;
                } elseif ($product['quantity'] < $amount) {
                    $amount = $product['quantity'];
                }
            }

            $product['amount'] = $amount;
            $product['type'] = 'Product';
            $this->products[] = $product;
        } elseif ($product_collection == 'Collection') {
            $collection = Collection::withTrashed()->with('thumbnail')->findOrFail($product_id)->toArray();

            if (!$force) {
                if ($collection['quantity'] <= 0) {
                    return;
                } elseif ($collection['quantity'] < $amount) {
                    $amount = $collection['quantity'];
                }
            }

            $collection['amount'] = $amount;
            $collection['type'] = 'Collection';
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

        if ($amount > 0) {
            if ($this->products[$productId]['quantity'] > 0 || !empty($this->initialProducts)) {
                $maxQty = max($this->products[$productId]['quantity'], $this->products[$productId]['amount']); // Allow at least what was already there
                $this->products[$productId]['amount'] = $amount <= $maxQty ? $amount : $maxQty;
            } else {
                $this->products[$productId]['amount'] = $amount;
            }
        } else {
            unset($this->products[$productId]);
        }

        $this->dispatch('setProductsData', [
            'products' => $this->products
        ]);
    }
}
