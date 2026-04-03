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
     * Map product data to Facebook standard format.
     */
    protected function formatProductData(Product $product)
    {
        $productOffer = $product->best_offer;

        // Build Product Type from categories
        $categories = $product->categories->first();
        $supercategory = $product->supercategories->first();
        $subcategory = $product->subcategories->first();
        $productType = collect([
            $supercategory->name ?? null,
            $categories->name ?? null,
            $subcategory->name ?? null,
        ])->filter()->join(' > ');

        $data = [
            'retailer_id' => (string) $product->id,
            'name' => $product->name,
            'description' => trim(html_entity_decode(strip_tags($product->description), ENT_QUOTES | ENT_HTML5, 'UTF-8')),
            'availability' => $product->quantity > 0 ? 'in stock' : 'out of stock',
            'condition' => 'new',
            'currency' => 'EGP',
            'url' => route('front.products.show', ['id' => $product->id, 'slug' => $product->slug]),
            'image_url' => $product->thumbnail ? asset("storage/images/products/cropped250/{$product->thumbnail->file_name}") : asset('assets/img/logos/smart-tools-logos.png'),
            'brand' => $product->brand->name ?? 'Smart Tools Egypt',
            'gtin' => $product->barcode ?: null,
            'manufacturer_part_number' => $product->model,
            'product_type' => $productType ?: 'Tools',
            'google_product_category' => 'Hardware > Tools',
            'gender' => 'unisex',
            'age_group' => 'adult',
            'price' => (int) round($product->base_price) * 100, // In cents if using integer
        ];

        if ($productOffer['best_price'] < $product->base_price) {
            $data['sale_price'] = (int) round($productOffer['best_price']) * 100;
        }

        return $data;
    }

    /**
     * Sync a single product to the catalog using the /products endpoint.
     */
    public function syncProduct(Product $product)
    {
        if (empty($this->catalogId)) {
            return false;
        }

        $endpoint = "https://graph.facebook.com/{$this->apiVersion}/{$this->catalogId}/products";
        $data = $this->formatProductData($product);
        
        $payload = array_merge($data, [
            'access_token' => $this->accessToken,
        ]);

        try {
            // Log payload for debugging
            Log::info('Meta Single Product Sync Payload:', $payload);

            // Use asForm() because the standard /products endpoint usually expects form-data
            $response = Http::asForm()->post($endpoint, $payload);
            Log::info('Meta Single Product Sync Response: ' . $response->body());
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Meta Single Product Sync Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Sync a batch of products to the catalog using the /items_batch endpoint.
     */
    public function syncProducts($products)
    {
        if (empty($this->catalogId)) {
            Log::warning('Meta Catalog ID is not set.');
            return false;
        }

        $requests = $products->map(function ($product) {
            $data = $this->formatProductData($product);
            $retailerId = $data['retailer_id'];
            unset($data['retailer_id']);

            return [
                'method' => 'UPDATE',
                'retailer_id' => $retailerId,
                'data' => $data,
            ];
        })->values()->toArray();

        $endpoint = "https://graph.facebook.com/{$this->apiVersion}/{$this->catalogId}/items_batch";

        $payload = [
            'requests' => $requests,
            'access_token' => $this->accessToken,
        ];

        try {
            $response = Http::post($endpoint, $payload);
            Log::info('Meta Catalog Batch Response: ' . $response->body());
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Meta Catalog Batch Exception: ' . $e->getMessage());
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

        $endpoint = "https://graph.facebook.com/{$this->apiVersion}/{$this->catalogId}/items_batch";

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
