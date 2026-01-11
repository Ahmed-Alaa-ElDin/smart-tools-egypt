<?php

namespace App\Livewire\Front\Product;

use App\Models\Product;
use App\Models\Collection;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ProductCardWide extends Component
{
    public $item;
    public $type;

    /**
     * Component mount
     *
     * @param array $item
     * @param string $type
     * @return void
     */
    public function mount($item, $type = 'cart')
    {
        $this->item = $item;
        $this->type = $type;
    }

    /**
     * Refresh item data when cart is updated
     *
     * @return void
     */
    #[On('cartUpdated')]
    public function refreshItem()
    {
        try {
            if ($this->type === 'cart' && isset($this->item['rowId'])) {
                $cartItem = Cart::instance('cart')->get($this->item['rowId']);
                if ($cartItem) {
                    $this->item['cartQty'] = $cartItem->qty;
                }
                // If item doesn't exist, parent will re-render and this component will be removed
            }
        } catch (\Exception $e) {
            // Silently fail - parent will handle cleanup
        }
    }

    /**
     * Calculate total price for the item row
     *
     * @return float
     */
    #[Computed]
    public function total()
    {
        return ($this->item['final_price'] ?? 0) * ($this->item['cartQty'] ?? 0);
    }

    /**
     * Increment item quantity
     *
     * @return void
     */
    public function increment()
    {
        if ($this->type !== 'cart' || !isset($this->item['rowId'])) {
            return;
        }

        $cartItem = Cart::instance('cart')->get($this->item['rowId']);
        $newQty = $cartItem->qty + 1;

        if ($this->item['type'] == "Product") {
            $itemData = Product::select('id', 'quantity')->findOrFail($this->item['id']);
        } elseif ($this->item['type'] == "Collection") {
            $itemData = Collection::select('id')->findOrFail($this->item['id']);
        }

        if ($itemData->quantity >= $newQty) {
            Cart::instance('cart')->update($this->item['rowId'], $newQty);
            $this->updateCart();
        } else {
            Cart::instance('cart')->update($this->item['rowId'], $itemData->quantity);
            $this->updateCart();
            $this->dispatch(
                'swalDone',
                text: __('front/homePage.Sorry This Product is Out Of Stock'),
                icon: 'error'
            );
        }
    }

    /**
     * Decrement item quantity
     *
     * @return void
     */
    public function decrement()
    {
        if ($this->type !== 'cart' || !isset($this->item['rowId'])) {
            return;
        }

        $cartItem = Cart::instance('cart')->get($this->item['rowId']);
        if ($cartItem && $cartItem->qty > 1) {
            Cart::instance('cart')->update($this->item['rowId'], $cartItem->qty - 1);
            $this->updateCart();
        }
    }

    /**
     * Update quantity directly
     *
     * @param int $quantity
     * @return void
     */
    public function updateQuantity($quantity)
    {
        if ($this->type !== 'cart' || !isset($this->item['rowId'])) {
            return;
        }

        $quantity = (int) $quantity;
        if ($quantity < 1) {
            $quantity = 1;
        }

        if ($this->item['type'] == "Product") {
            $itemData = Product::select('id', 'quantity')->findOrFail($this->item['id']);
        } elseif ($this->item['type'] == "Collection") {
            $itemData = Collection::select('id')->findOrFail($this->item['id']);
        }

        if ($itemData->quantity >= $quantity) {
            Cart::instance('cart')->update($this->item['rowId'], $quantity);
            $this->updateCart();
        } else {
            Cart::instance('cart')->update($this->item['rowId'], $itemData->quantity);
            $this->updateCart();
            $this->dispatch(
                'swalDone',
                text: __('front/homePage.Sorry This Product is Out Of Stock'),
                icon: 'error'
            );
        }
    }

    /**
     * Update cart state and dispatch events
     *
     * @return void
     */
    protected function updateCart()
    {
        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::user()->id);
        }

        $this->dispatch('cartUpdated');
    }

    /**
     * Remove item from cart
     *
     * @return void
     */
    public function removeFromCart()
    {
        if ($this->type !== 'cart' || !isset($this->item['rowId'])) {
            return;
        }

        $cart = Cart::instance('cart');

        // Safety check: ensure row exists before removal
        if ($cart->get($this->item['rowId'])) {
            $cart->remove($this->item['rowId']);

            if (Auth::check()) {
                $cart->store(Auth::user()->id);
            }

            $this->dispatch('cartUpdated');
            $this->dispatch(
                'swalDone',
                text: __('front/homePage.Product Removed From Your Cart Successfully'),
                icon: 'success'
            );
        } else {
            // Trigger a refresh if sync is lost
            $this->dispatch('cartUpdated');
        }
    }

    /**
     * Remove item from wishlist
     *
     * @return void
     */
    public function removeFromWishlist()
    {
        if ($this->type !== 'wishlist' || !isset($this->item['rowId'])) {
            return;
        }

        $wishlist = Cart::instance('wishlist');

        // Safety check: ensure row exists before removal
        if ($wishlist->get($this->item['rowId'])) {
            $wishlist->remove($this->item['rowId']);

            if (Auth::check()) {
                $wishlist->store(Auth::user()->id);
            }

            $this->dispatch('cartUpdated');
            $this->dispatch(
                'swalDone',
                text: __('front/homePage.Product Removed From Your Wishlist Successfully'),
                icon: 'success'
            );
        } else {
            // Trigger a refresh if sync is lost
            $this->dispatch('cartUpdated');
        }
    }

    public function render()
    {
        return view('livewire.front.product.product-card-wide');
    }
}
