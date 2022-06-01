<?php

namespace App\Http\Livewire\Front\Homepage;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductsList extends Component
{
    public $section;
    public $key;
    public $amount;

    ############## Mount :: Start ##############
    public function mount()
    {
        $this->products = $this->section->products;
    }
    ############## Mount :: End ##############

    ############## Render Section :: Start ##############
    public function render()
    {
        return view('livewire.front.homepage.products-list');
    }
    ############## Render Section :: End ##############

    ############## Add TO Cart :: Start ##############
    public function addToCart($product_id)
    {
        $product = Product::publishedproduct()->findOrFail($product_id);

        ############ Get Best Offer for all products :: Start ############
        // Get All Product's Prices -- Start with Product's Final Price
        $all_prices = [$product->final_price];

        // Get All Product's Points -- Start with Product's Points
        $all_points = [$product->points];

        // Get Free Shipping
        $free_shipping = $product->free_shipping;

        // Get All Subcategories
        $subcategories = $product->subcategories ? $product->subcategories->map(fn ($subcategory) => $subcategory->id) : [];

        // Get All Categories
        $categories = $subcategories ? $product->subcategories->map(fn ($subcategory) => $subcategory->category) : [];

        // Get All Supercategories
        $supercategories = $categories ? $categories->map(fn ($category) => $category->supercategory) : [];

        // Get Final Prices From Direct Offers
        $direct_offers = $product->offers->map(fn ($offer) => ['free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type, 'number' => $offer->pivot->number]);
        foreach ($direct_offers as $offer) {
            if ($offer['free_shipping']) {
                $free_shipping = 1;
            }

            if ($offer['type'] == 0) {
                $all_prices[] = round($product->final_price - (($offer['value'] / 100) * $product->final_price), 2);
            } elseif ($offer['type'] == 1) {
                if ($product->final_price >  $offer['value']) {
                    $all_prices[] = round($product->final_price - $offer['value'], 2);
                } else {
                    $all_prices[] = 0;
                }
            } elseif ($offer['type'] == 2) {
                $all_points[] = $offer['value'];
            }
        }

        // Get Final Prices From Offers Through Subcategories
        $subcategories_offers = $product->subcategories ? $product->subcategories->map(fn ($subcategory) => $subcategory->offers->map(fn ($offer) => ['free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type, 'number' => $offer->pivot->number]))->toArray() : [];
        foreach ($subcategories_offers as $subcategory) {
            foreach ($subcategory as $offer) {
                if ($offer['free_shipping']) {
                    $free_shipping = 1;
                }

                if ($offer['type'] == 0) {
                    $all_prices[] = round($product->final_price - (($offer['value'] / 100) * $product->final_price), 2);
                } elseif ($offer['type'] == 1) {
                    if ($product->final_price >  $offer['value']) {
                        $all_prices[] = round($product->final_price - $offer['value'], 2);
                    } else {
                        $all_prices[] = 0;
                    }
                } elseif ($offer['type'] == 2) {
                    $all_points[] = $offer['value'];
                }
            }
        }

        // Get Final Prices From Offers Through Categories
        $categories_offers = $categories ? $categories->map(fn ($category) => $category->offers->map(fn ($offer) => ['free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type, 'number' => $offer->pivot->number]))->toArray() : [];
        foreach ($categories_offers as $category) {
            foreach ($category as $offer) {
                if ($offer['free_shipping']) {
                    $free_shipping = 1;
                }

                if ($offer['type'] == 0) {
                    $all_prices[] = round($product->final_price - (($offer['value'] / 100) * $product->final_price), 2);
                } elseif ($offer['type'] == 1) {
                    if ($product->final_price >  $offer['value']) {
                        $all_prices[] = round($product->final_price - $offer['value'], 2);
                    } else {
                        $all_prices[] = 0;
                    }
                } elseif ($offer['type'] == 2) {
                    $all_points[] = $offer['value'];
                }
            }
        }

        // Get Final Prices From Offers Through Supercategories
        $supercategories_offers = $supercategories ? $supercategories->map(fn ($supercategory) => $supercategory->offers->map(fn ($offer) => ['free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type, 'number' => $offer->pivot->number])) : [];
        foreach ($supercategories_offers as $supercategory) {
            foreach ($supercategory as $offer) {
                if ($offer['free_shipping']) {
                    $free_shipping = 1;
                }

                if ($offer['type'] == 0) {
                    $all_prices[] = round($product->final_price - (($offer['value'] / 100) * $product->final_price), 2);
                } elseif ($offer['type'] == 1) {
                    if ($product->final_price >  $offer['value']) {
                        $all_prices[] = round($product->final_price - $offer['value'], 2);
                    } else {
                        $all_prices[] = 0;
                    }
                } elseif ($offer['type'] == 2) {
                    $all_points[] = $offer['value'];
                }
            }
        }

        // Get Final Prices From Offers Through Brands
        $brand_offers = $product->brand ? $product->brand->offers->map(fn ($offer) => ['free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type, 'number' => $offer->pivot->number]) : [];
        foreach ($brand_offers as $offer) {
            if ($offer['free_shipping']) {
                $free_shipping = 1;
            }

            if ($offer['type'] == 0) {
                $all_prices[] = round($product->final_price - (($offer['value'] / 100) * $product->final_price), 2);
            } elseif ($offer['type'] == 1) {
                if ($product->final_price >  $offer['value']) {
                    $all_prices[] = round($product->final_price - $offer['value'], 2);
                } else {
                    $all_prices[] = 0;
                }
            } elseif ($offer['type'] == 2) {
                $all_points[] = $offer['value'];
            }
        }

        // Get the Best Price
        $product->best_price = min($all_prices);

        // Get the Best Points
        $product->best_points = max($all_points);

        $product->free_shipping = $free_shipping;
        ############ Get Best Offer for all products :: End ############

        ############ Add Product to Cart :: Start ############
        Cart::instance('cart')->add($product->id, [
            'en' => $product->getTranslation('name', 'en'),
            'ar' => $product->getTranslation('name', 'ar'),
        ], 1, $product->best_price, ['thumbnail' => $product->thumbnail ?? null])->associate(Product::class);

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::user()->id);
        }

        // todo
        // Cart::destroy();

        ############ Add Product to Cart :: End ############

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('product_added_to_cart', ['key' => $this->key]);
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Add TO Cart :: End ##############

    ############## Add One tem To Cart :: Start ##############
    public function AddOneToCart($rowId, $quantity)
    {
        Cart::instance('cart')->update($rowId, $quantity);
        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('product_added_to_cart', ['key' => $this->key]);
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Add One tem To Cart :: End ##############

    ############## Remove From Cart :: Start ##############
    public function RemoveOneFromCart($rowId, $quantity)
    {
        Cart::instance('cart')->update($rowId, $quantity);
        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('product_added_to_cart', ['key' => $this->key]);
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Remove From Cart :: End ##############

    ############## Update Cart :: Start ##############
    public function CartUpdated($rowId, $quantity)
    {
        Cart::instance('cart')->update($rowId, $quantity);
        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('product_added_to_cart', ['key' => $this->key]);
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Update Cart :: End ##############

    ############## Remove From Cart :: Start ##############
    public function RemoveFromCart($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('product_added_to_cart', ['key' => $this->key]);
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Remove From Cart :: End ##############
}
