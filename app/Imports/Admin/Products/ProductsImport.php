<?php

namespace App\Imports\Admin\Products;

use App\Models\Product;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Find product by ID
        $product = Product::find($row[0]);

        // If product found, update it
        if ($product) {
            $product->update([
                'name' => [
                    'ar' => $row[2],
                    'en' => $row[1] ?? $row[2],
                ],
                'slug' => [
                    'ar' => Str::slug($row[2], '-'),
                    'en' => Str::slug($row[1] ?? $row[2], '-'),
                ],
                'barcode' => $row[4],
                'weight' => $row[16] ?? 0,
                'quantity' => $row[17] ?? 0,
                'low_stock' => $row[18] ?? 0,
                'original_price' => $row[10] ?? 0,
                'base_price' => $row[11] ?? 0,
                'final_price' => $row[12] ?? 0,
                'points' => $row[14] ?? 0,
                'model' => $row[3],
                'refundable' => $row[19] == 'Yes' ? 1 : 0,
                'publish' => $row[20] == 'Yes' ? 1 : 0,
                'under_reviewing' => $row[15] == 'Yes' ? 1 : 0,
            ]);
        }

        return null;
    }
}
