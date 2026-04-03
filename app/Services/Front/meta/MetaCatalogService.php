<?php

namespace App\Services\Front\meta;

use App\Models\Product;
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
     * Sync a single product to the catalog.
     */
    public function syncProduct(Product $product)
    {
        return $this->syncProducts(collect([$product]));
    }

    /**
     * Sync a batch of products to the catalog.
     */
    public function syncProducts($products)
    {
        if (empty($this->catalogId)) {
            Log::warning('Meta Catalog ID is not set.');
            return false;
        }

        $requests = $products->map(function ($product) {
            $productOffer = $product->best_offer;

            $priceData = [
                'price' => (int) round($product->base_price) * 100,
            ];

            if ($productOffer['best_price'] < $product->base_price) {
                $priceData['sale_price'] = (int) round($productOffer['best_price']) * 100;
            }

            return [
                'method' => 'UPDATE',
                'retailer_id' => (string) $product->id,
                'data' => array_merge([
                    'name' => $product->name,
                    'description' => trim(html_entity_decode(strip_tags($product->description), ENT_QUOTES | ENT_HTML5, 'UTF-8')),
                    'availability' => $product->quantity > 0 ? 'in stock' : 'out of stock',
                    'condition' => 'new',
                    'currency' => 'EGP',
                    'url' => route('front.products.show', ['id' => $product->id, 'slug' => $product->slug]),
                    'image_url' => $product->thumbnail ? asset("storage/images/products/cropped250/{$product->thumbnail->file_name}") : asset('assets/img/logos/smart-tools-logos.png'),
                    'brand' => $product->brand->name ?? 'Smart Tools Egypt',
                ], $priceData),
            ];
        })->values()->toArray();

        $endpoint = "https://graph.facebook.com/{$this->apiVersion}/{$this->catalogId}/batch";

        $payload = [
            'requests' => $requests,
            'access_token' => $this->accessToken,
        ];

        try {
            $response = Http::post($endpoint, $payload);
            
            // Log for debugging
            Log::info('Meta Catalog Sync Response: ' . $response->body());

            if ($response->successful()) {
                return true;
            }

            Log::error('Meta Catalog Sync Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Meta Catalog Sync Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a product from the catalog.
     */
    public function deleteProduct($productId)
    {
        if (empty($this->catalogId)) {
            return false;
        }

        $endpoint = "https://graph.facebook.com/{$this->apiVersion}/{$this->catalogId}/batch";

        $payload = [
            'requests' => [
                [
                    'method' => 'DELETE',
                    'retailer_id' => (string) $productId,
                ]
            ],
            'access_token' => $this->accessToken,
        ];

        return Http::post($endpoint, $payload)->successful();
    }
}
