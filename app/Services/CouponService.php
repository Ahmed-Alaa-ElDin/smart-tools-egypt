<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Support\Collection;

class CouponService
{
    private const PERCENTAGE_DISCOUNT = 0;
    private const FIXED_DISCOUNT = 1;
    private const POINTS_REWARD = 2;
    private const MAX_PERCENTAGE = 100;


    private Collection $products;
    private Collection $collections;
    private Coupon $coupon;
    private array $discountedProducts = [];
    private array $discountedCollections = [];
    private float $price = 0;

    private float $itemsDiscount = 0;
    private int $itemsPoints = 0;
    private float $orderDiscount = 0;
    private int $orderPoints = 0;

    public function __construct(?Collection $products = null, ?Collection $collections = null)
    {
        $this->products = $products ?? collect();
        $this->collections = $collections ?? collect();
    }

    public function calculateDiscount(int $couponId, float $price)
    {
        $this->price = $price;
        $this->loadCoupon($couponId);
        $this->calculateDiscounts();

        $this->itemsDiscount = $this->calculateItemsDiscount();
        $this->itemsPoints = $this->calculateItemsPoints();
        $this->orderDiscount = $this->calculateOrderDiscount();
        $this->orderPoints = $this->calculateOrderPoints();

        return [
            'products_best_coupon' => $this->products,
            'collections_best_coupon' => $this->collections,
            'coupon_items_discount' => $this->itemsDiscount,
            'coupon_items_points' => $this->itemsPoints,
            'coupon_order_discount' => $this->orderDiscount,
            'coupon_order_points' => $this->orderPoints,
            'coupon_free_shipping' => $this->coupon->free_shipping
        ];
    }

    private function loadCoupon(int $couponId): void
    {
        $this->coupon = Coupon::with([
            'supercategories.products' => fn($q) => $q->select('products.id'),
            'categories.products' => fn($q) => $q->select('products.id'),
            'subcategories.products' => fn($q) => $q->select('products.id'),
            'brands.products' => fn($q) => $q->select('brands.id', 'products.id', 'brand_id'),
            'products' => fn($q) => $q->select('products.id'),
            'collections' => fn($q) => $q->select('collections.id')
        ])->findOrFail($couponId);
    }

    private function calculateDiscounts(): void
    {
        $this->processDiscountRules([
            'brands' => fn($item) => $item->products->pluck('id'),
            'subcategories' => fn($item) => $item->products->pluck('id'),
            'categories' => fn($item) => $item->products->pluck('id'),
            'supercategories' => fn($item) => $item->products->pluck('id')
        ]);

        $this->processDirectProductDiscounts();
        $this->processCollectionDiscounts();
    }

    private function processDiscountRules(array $relations): void
    {
        foreach ($relations as $relation => $productIdGetter) {
            if ($this->coupon->$relation->isEmpty()) {
                continue;
            }

            foreach ($this->coupon->$relation as $item) {
                $matchingProducts = $this->products->whereIn('id', $productIdGetter($item));
                foreach ($matchingProducts as $product) {
                    $this->addDiscountedProduct(
                        $product,
                        $item->pivot->type,
                        $item->pivot->value
                    );
                }
            }
        }
    }

    private function processDirectProductDiscounts(): void
    {
        $productIds = $this->products->pluck('id')->toArray();

        $this->coupon->products
            ->filter(fn($product) => in_array($product->id, $productIds))
            ->each(fn($product) => $this->addDiscountedProduct(
                $this->products->where('id', '=', $product->id)->first(),
                $product->pivot->type,
                $product->pivot->value
            ));
    }

    private function processCollectionDiscounts(): void
    {
        $collectionIds = $this->collections->pluck('id')->toArray();

        $this->coupon->collections
            ->filter(fn($collection) => in_array($collection->id, $collectionIds))
            ->each(fn($collection) => $this->addDiscountedCollection(
                $this->collections->where('id', '=', $collection->id)->first(),
                $collection->pivot->type,
                $collection->pivot->value
            ));
    }

    private function addDiscountedProduct($product, int $type, float $value): void
    {
        $discountedProduct = clone $product;

        $discountedProduct->coupon_discount = $this->calculateItemDiscount(
            $type,
            $value,
            $product->best_price
        );

        $discountedProduct->coupon_points = $type === self::POINTS_REWARD ? $value : 0;

        $this->discountedProducts[] = $discountedProduct;
    }

    private function addDiscountedCollection($collection, int $type, float $value): void
    {
        $discountedCollection = clone $collection;

        $discountedCollection->coupon_discount = $this->calculateItemDiscount(
            $type,
            $value,
            $collection->best_price
        );

        $discountedCollection->coupon_points = $type === self::POINTS_REWARD ? $value : 0;

        $this->discountedCollections[] = $discountedCollection;
    }

    private function calculateItemDiscount(int $type, float $value, float $price): float
    {
        return match ($type) {
            self::PERCENTAGE_DISCOUNT => $value <= self::MAX_PERCENTAGE ? $price * $value / 100 : 0,
            self::FIXED_DISCOUNT => min($value, $price),
            default => 0
        };
    }

    private function calculateItemsDiscount(): float
    {
        // Get the product discounts
        $productAllDiscounts = $this->getBestDiscounts($this->discountedProducts, 'id');

        // Add the discount to the main products
        $this->products->each(function ($product) use ($productAllDiscounts) {
            $product->coupon_discount = $productAllDiscounts->where('id', '=', $product->id)->first()['total_discount'] ?? 0;
        });

        // Get the total discount
        $productDiscount = $productAllDiscounts->sum('total_discount');

        // Get the collection discounts
        $collectionAllDiscounts = $this->getBestDiscounts($this->discountedCollections, 'id');

        // Add the discount to the main collections
        $this->collections->each(function ($collection) use ($collectionAllDiscounts) {
            $collection->coupon_discount = $collectionAllDiscounts->where('id', '=', $collection->id)->first()['total_discount'] ?? 0;
        });

        // Get the total discount
        $collectionDiscount = $collectionAllDiscounts->sum('total_discount');

        return $productDiscount + $collectionDiscount;
    }

    private function calculateItemsPoints(): int
    {
        // Get the product points
        $productAllPoints = $this->getBestDiscounts($this->discountedProducts, 'id');

        // Add the points to the main products
        $this->products->each(function ($product) use ($productAllPoints) {
            $product->coupon_points = $productAllPoints->where('id', '=', $product->id)->first()['total_points'] ?? 0;
        });

        // Get the total points
        $productPoints = $productAllPoints->sum('total_points');

        // Get the collection points
        $collectionPoints = $this->getBestDiscounts($this->discountedCollections, 'id');

        // Add the points to the main collections
        $this->collections->each(function ($collection) use ($collectionPoints) {
            $collection->coupon_points = $collectionPoints->where('id', '=', $collection->id)->first()['total_points'] ?? 0;
        });

        // Get the total points
        $collectionPoints = $collectionPoints->sum('total_points');

        return $productPoints + $collectionPoints;
    }

    private function getBestDiscounts(array $items, string $groupKey): Collection
    {
        return collect($items)
            ->groupBy($groupKey)
            ->map(function ($group) use ($groupKey) {
                $first = $group->first();
                $qty = $first['qty'];

                return [
                    'id' => $first[$groupKey],
                    'qty' => $qty,
                    'coupon_discount' => $group->max('coupon_discount'),
                    'total_discount' => $qty * $group->max('coupon_discount'),
                    'coupon_points' => $group->max('coupon_points'),
                    'total_points' => $qty * $group->max('coupon_points')
                ];
            });
    }

    private function calculateOrderDiscount(): float
    {
        if ($this->coupon->on_orders) {
            // percentage discount
            if ($this->coupon->type == 0 && $this->coupon->value <= 100) {
                return ($this->price - $this->itemsDiscount) * $this->coupon->value / 100;
            }
            // fixed discount
            elseif ($this->coupon->type == 1) {
                return $this->coupon->value <= ($this->price - $this->itemsDiscount) ? $this->coupon->value : ($this->price - $this->itemsDiscount);
            }
        }

        return 0.00;
    }

    private function calculateOrderPoints(): int
    {
        if ($this->coupon->on_orders && $this->coupon->type == 2) {
            return $this->coupon->value;
        }

        return 0;
    }
}
