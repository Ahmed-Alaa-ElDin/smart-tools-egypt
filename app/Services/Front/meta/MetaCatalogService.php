<?php

namespace App\Services\Front\meta;

use App\Models\Product;
use App\Models\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaCatalogService
{
    protected $catalogId;
    protected $accessToken;
    protected $apiVersion;

    public function __construct()
    {
        $this->catalogId = config('services.meta_catalog.id');
        $this->accessToken = config('services.meta_catalog.access_token');
        $this->apiVersion = config('services.meta_catalog.api_version');
    }

    /**
     * Map item data (Product or Collection) to Facebook standard format.
     */
    protected function formatItemData($item)
    {
        $isCollection = ($item instanceof Collection);
        $retailerId = $isCollection ? "coll_{$item->id}" : (string) $item->id;
        
        // Get prices and offers
        // Note: Both models have best_offer accessor and base_price
        $itemOffer = $item->best_offer;
        $price = (int) round($item->base_price) * 100;
        
        // Fetch gallery images
        $additionalImageUrls = $item->images
            ->where('is_thumbnail', '!=', 1)
            ->map(function ($image) {
                return asset("storage/images/products/cropped250/{$image->file_name}");
            })->take(20)->values()->toArray();

        // Build Product Type
        $productType = 'Tools';
        if (!$isCollection) {
            $categories = $item->categories->first();
            $supercategory = $item->supercategories->first();
            $subcategory = $item->subcategories->first();
            $productType = collect([
                $supercategory->name ?? null,
                $categories->name ?? null,
                $subcategory->name ?? null,
            ])->filter()->join(' > ') ?: 'Tools';
        } else {
            $productType = 'Collections > Bundles';
        }

        $data = [
            'retailer_id' => $retailerId,
            'name' => $item->name,
            'description' => trim(html_entity_decode(strip_tags($item->description), ENT_QUOTES | ENT_HTML5, 'UTF-8')),
            'availability' => $item->quantity > 0 ? 'in stock' : 'out of stock',
            'condition' => 'new',
            'currency' => 'EGP',
            'url' => route($isCollection ? 'front.collections.show' : 'front.products.show', ['id' => $item->id, 'slug' => $item->slug]),
            'image_url' => $item->thumbnail ? asset("storage/images/products/cropped250/{{$item->thumbnail->file_name}}") : asset('assets/img/logos/smart-tools-logos.png'),
            'additional_image_urls' => $additionalImageUrls,
            'brand' => $isCollection ? 'Smart Tools Collections' : ($item->brand->name ?? 'Smart Tools Egypt'),
            'manufacturer_part_number' => $item->model,
            'product_type' => $productType,
            'gender' => 'unisex',
            'age_group' => 'adult',
            'inventory' => (int) $item->quantity,
            'price' => $price,
        ];

        if ($itemOffer && $itemOffer['best_price'] < $item->base_price) {
            $data['sale_price'] = (int) round($itemOffer['best_price']) * 100;
        }

        return $data;
    }

    /**
     * Sync a single item (Product or Collection).
     */
    public function syncItem($item)
    {
        if (empty($this->catalogId)) {
            return false;
        }

        $endpoint = "https://graph.facebook.com/{$this->apiVersion}/{$this->catalogId}/products";
        $data = $this->formatItemData($item);
        
        $payload = array_merge($data, [
            'item_type' => 'PRODUCT',
            'access_token' => $this->accessToken,
        ]);

        try {
            Log::info("Meta Single Item Sync ({$item->getTypeAttribute()}): " . $item->id);
            $response = Http::asForm()->post($endpoint, $payload);
            Log::info('Meta Single Item Sync Response: ' . $response->body());
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Meta Single Item Sync Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Legacy support for syncProduct.
     */
    public function syncProduct(Product $product)
    {
        return $this->syncItem($product);
    }

    /**
     * Sync a single collection.
     */
    public function syncCollection(Collection $collection)
    {
        return $this->syncItem($collection);
    }

    /**
     * Sync a batch of items (Products or Collections).
     */
    public function syncItems($items)
    {
        if (empty($this->catalogId) || $items->isEmpty()) {
            return false;
        }

        $requests = $items->map(function ($item) {
            $data = $this->formatItemData($item);
            $retailerId = $data['retailer_id'];
            unset($data['retailer_id']);

            return [
                'method' => 'UPDATE',
                'retailer_id' => $retailerId,
                'data' => $data,
            ];
        })->values()->toArray();

        $endpoint = "https://graph.facebook.com/{$this->apiVersion}/{$this->catalogId}/batch";

        $payload = [
            'requests' => $requests,
            'access_token' => $this->accessToken,
        ];

        try {
            $response = Http::post($endpoint, $payload);
            Log::info('Meta Catalog Batch Sync Response: ' . $response->body());
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Meta Catalog Batch Sync Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete an item from the catalog.
     */
    public function deleteItem($id, $isCollection = false)
    {
        if (empty($this->catalogId)) {
            return false;
        }

        $retailerId = $isCollection ? "coll_{$id}" : (string) $id;

        $endpoint = "https://graph.facebook.com/{$this->apiVersion}/{$this->catalogId}/batch";

        $payload = [
            'requests' => [
                [
                    'method' => 'DELETE',
                    'retailer_id' => $retailerId,
                ]
            ],
            'access_token' => $this->accessToken,
        ];

        return Http::post($endpoint, $payload)->successful();
    }
}
