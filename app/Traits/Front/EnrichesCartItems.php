<?php

namespace App\Traits\Front;

use Gloudemans\Shoppingcart\Facades\Cart;

trait EnrichesCartItems
{
    /**
     * Get enriched items from a cart instance.
     *
     * @param string $instance
     * @return array
     */
    protected function getEnrichedItems(string $instance): array
    {
        $content = Cart::instance($instance)->content();

        $productIds = $content->where('options.type', 'Product')->pluck('id')->unique()->toArray();
        $collectionIds = $content->where('options.type', 'Collection')->pluck('id')->unique()->toArray();

        // Fetch enriched data from database and key by ID
        $products = !empty($productIds) ? getBestOfferForProducts($productIds)->keyBy('id') : collect();
        $collections = !empty($collectionIds) ? getBestOfferForCollections($collectionIds)->keyBy('id') : collect();

        return $content->map(function ($cartItem) use ($products, $collections) {
            $type = $cartItem->options->type;
            $id = $cartItem->id;

            // Get enriched data from DB results
            $enriched = $type === 'Product'
                ? ($products->get($id) ? $products->get($id)->toArray() : [])
                : ($collections->get($id) ? $collections->get($id)->toArray() : []);

            // Fallback if DB data is missing
            if (empty($enriched)) {
                $enriched = [
                    'id' => $id,
                    'type' => $type,
                    'name' => $cartItem->name,
                    'base_price' => $cartItem->price,
                    'final_price' => $cartItem->price,
                    'thumbnail' => $cartItem->options->thumbnail
                ];
            }

            // Sync with current cart row data
            $enriched['rowId'] = $cartItem->rowId;
            $enriched['cartQty'] = $cartItem->qty;
            $enriched['type'] = $type;

            return $enriched;
        })->values()->toArray();
    }
}
