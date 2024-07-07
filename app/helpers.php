<?php

use App\Models\User;
use App\Models\Coupon;
use App\Models\Review;
use App\Models\Product;
use App\Models\Collection;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use App\Enums\OrderStatus;

// todo :: compare between this function and lower one
// Upload single photo and get link
function imageUpload($photo, $image_f_name, $folder_name)
{
    $image_name = $image_f_name . time() . '-' . rand() . '.' . $photo->getClientOriginalExtension();

    // Crop and resize photo
    try {
        $manager = new ImageManager();

        File::isDirectory('storage/images/' . $folder_name . '/cropped100/') || File::makeDirectory('storage/images/' . $folder_name . '/cropped100/', 0777, true, true);

        $manager->make($photo)->resize(100, null, function ($constraint) {
            $constraint->aspectRatio();
        })->crop(100, 100)->save('storage/images/' . $folder_name . '/cropped100/' . $image_name);
    } catch (\Throwable $th) {
    }

    // Upload photo and get link
    $photo->storeAs('original', $image_name, $folder_name);

    return ['temporaryUrl' => $photo->temporaryUrl(), "image_name" => $image_name];
}

// Upload single photo and get link
function singleImageUpload($photo, $image_f_name, $folder_name)
{
    $image_name = $image_f_name . time() . '-' . rand();
    // Crop and resize photo
    try {
        $manager = new ImageManager();

        File::isDirectory('storage/images/' . $folder_name . '/cropped100/') || File::makeDirectory('storage/images/' . $folder_name . '/cropped100/', 0777, true, true);
        File::isDirectory('storage/images/' . $folder_name . '/original/') || File::makeDirectory('storage/images/' . $folder_name . '/original/', 0777, true, true);

        // Save cropped Size
        $manager->make($photo)->encode('webp')->resize(100, null, function ($constraint) {
            $constraint->aspectRatio();
        })->crop(100, 100)->save('storage/images/' . $folder_name . '/cropped100/' . $image_name);

        // Save Original Size
        $manager->make($photo)->encode('webp')->save('storage/images/' . $folder_name . '/original/' . $image_name);
    } catch (\Throwable $th) {
        // todo :: delete throw
        // throw $th;
    }

    return $image_name;
}

// Delete image from storage
function imageDelete($image_f_name, $folder_name)
{
    Storage::disk($folder_name)->delete('original/' . $image_f_name);
    Storage::disk($folder_name)->delete('cropped100/' . $image_f_name);
}

// Get the best offer for a product (best price, best points, free shipping)
// function getBestOfferForProduct($product_id)
// {
//     $product = Product::publishedproduct()->findOrFail($product_id);

//     ############ Get Best Offer for all products :: Start ############
//     // Get All Product's Prices -- Start with Product's Final Price
//     $offers_discount = [0];

//     // Get All Product's Points -- Start with Product's Points
//     $offers_points = [0];

//     // Get Free Shipping
//     $free_shipping = $product->free_shipping;

//     // Get All Subcategories
//     $subcategories = $product->subcategories ? $product->subcategories->map(fn ($subcategory) => $subcategory->id) : [];

//     // Get All Categories
//     $categories = $product->categories ? $product->categories->map(fn ($category) => $category->id) : [];

//     // Get All Supercategories
//     $supercategories = $product->supercategories ? $product->supercategories->map(fn ($supercategory) => $supercategory->id) : [];

//     // Get Final Prices Fromi Direct Offers
//     $direct_offers = $product->offers->map(fn ($offer) => ['free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type]);
//     foreach ($direct_offers as $offer) {
//         if ($offer['free_shipping']) {
//             $free_shipping = 1;
//         }

//         if ($offer['type'] == 0) {
//             $offers_discount[] = round(($offer['value'] / 100) * $product->final_price, 2);
//         } elseif ($offer['type'] == 1) {
//             $offers_discount[] = $product->final_price >  $offer['value'] ? round($offer['value'], 2) : $product->final_price;
//         } elseif ($offer['type'] == 2) {
//             $offers_points[] = $offer['value'];
//         }
//     }

//     // Get Final Prices From Offers Through Subcategories
//     $subcategories_offers = $subcategories ? $product->subcategories->map(fn ($subcategory) => $subcategory->offers->map(fn ($offer) => ['free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type]))->toArray() : [];
//     foreach ($subcategories_offers as $subcategory) {
//         foreach ($subcategory as $offer) {
//             if ($offer['free_shipping']) {
//                 $free_shipping = 1;
//             }

//             if ($offer['type'] == 0) {
//                 $offers_discount[] = round(($offer['value'] / 100) * $product->final_price, 2);
//             } elseif ($offer['type'] == 1) {
//                 $offers_discount[] = $product->final_price >  $offer['value'] ? round($offer['value'], 2) : $product->final_price;
//             } elseif ($offer['type'] == 2) {
//                 $offers_points[] = $offer['value'];
//             }
//         }
//     }

//     // Get Final Prices From Offers Through Categories
//     $categories_offers = $categories ? $product->categories->map(fn ($category) => $category->offers->map(fn ($offer) => ['free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type]))->toArray() : [];
//     foreach ($categories_offers as $category) {
//         foreach ($category as $offer) {
//             if ($offer['free_shipping']) {
//                 $free_shipping = 1;
//             }

//             if ($offer['type'] == 0) {
//                 $offers_discount[] = round(($offer['value'] / 100) * $product->final_price, 2);
//             } elseif ($offer['type'] == 1) {
//                 $offers_discount[] = $product->final_price >  $offer['value'] ? round($offer['value'], 2) : $product->final_price;
//             } elseif ($offer['type'] == 2) {
//                 $offers_points[] = $offer['value'];
//             }
//         }
//     }

//     // Get Final Prices From Offers Through Supercategories
//     $supercategories_offers = $supercategories ? $product->supercategories->map(fn ($supercategory) => $supercategory->offers->map(fn ($offer) => ['free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type])) : [];
//     foreach ($supercategories_offers as $supercategory) {
//         foreach ($supercategory as $offer) {
//             if ($offer['free_shipping']) {
//                 $free_shipping = 1;
//             }

//             if ($offer['type'] == 0) {
//                 $offers_discount[] = round(($offer['value'] / 100) * $product->final_price, 2);
//             } elseif ($offer['type'] == 1) {
//                 $offers_discount[] = $product->final_price >  $offer['value'] ? round($offer['value'], 2) : $product->final_price;
//             } elseif ($offer['type'] == 2) {
//                 $offers_points[] = $offer['value'];
//             }
//         }
//     }

//     // Get Final Prices From Offers Through Brands
//     $brand_offers = $product->brand ? $product->brand->offers->map(fn ($offer) => ['free_shipping' => $offer->free_shipping, 'value' => $offer->pivot->value, 'type' => $offer->pivot->type]) : [];
//     foreach ($brand_offers as $offer) {
//         if ($offer['free_shipping']) {
//             $free_shipping = 1;
//         }

//         if ($offer['type'] == 0) {
//             $offers_discount[] = round(($offer['value'] / 100) * $product->final_price, 2);
//         } elseif ($offer['type'] == 1) {
//             $offers_discount[] = $product->final_price >  $offer['value'] ? round($offer['value'], 2) : $product->final_price;
//         } elseif ($offer['type'] == 2) {
//             $offers_points[] = $offer['value'];
//         }
//     }

//     // Get the Best Price
//     $product->offer_discount = max($offers_discount);
//     $product->best_price = $product->final_price - $product->offer_discount;

//     // Get the Best Points
//     $product->offer_points = max($offers_points);
//     $product->best_points = $product->points + $product->offer_points;

//     $product->offer_free_shipping = $free_shipping;
//     ############ Get Best Offer for all products :: End ############

//     return $product;
// }

// Get the best offer for a product (best price, best points, free shipping)
function getBestOfferForProduct($product_id)
{
    $product = Product::publishedproduct()->findOrFail($product_id);

    // Collect all offers for the product and its related entities
    $offers = collect();

    // Add product's offers
    $offers = $offers->merge($product->offers);

    // Add offers from subcategories
    $product->subcategories?->each(function ($subcategory) use (&$offers) {
        $offers = $offers->merge($subcategory->offers);
    });

    // Add offers from categories
    $product->categories?->each(function ($category) use (&$offers) {
        $offers = $offers->merge($category->offers);
    });

    // Add offers from supercategories
    $product->supercategories?->each(function ($supercategory) use (&$offers) {
        $offers = $offers->merge($supercategory->offers);
    });

    // Add offers from the brand
    $offers = $offers->merge($product->brand?->offers);

    // Calculate the best discount
    $bestDiscount = $offers->map(function ($offer) use ($product) {
        $value = $offer->pivot->type === 0 ? ($offer->pivot->value / 100) * $product->final_price : $offer->pivot->value;
        return $product->final_price > $value ? round($value, 2) : $product->final_price;
    })->max();

    // Calculate the best points
    $bestPoints = $offers->where('pivot.type', 2)->max('pivot.value');

    // Check if there is a free shipping offer
    $bestFreeShipping = $offers->contains('free_shipping', true);

    $product->offer_discount = $bestDiscount;
    $product->best_price = $product->final_price - $bestDiscount;

    $product->offer_points = $bestPoints;
    $product->best_points = $product->points + $bestPoints;

    $product->offer_free_shipping = $bestFreeShipping;

    return $product;
}

// Get the best offer for a list of products (best price, best points, free shipping)
function getBestOfferForProducts($products_id, bool $publishedOnly = true)
{
    $products = $publishedOnly ? Product::publishedproducts($products_id)->get() : Product::productsDetails($products_id)->get();

    foreach ($products as $key => $product) {
        ############ Get Best Offer for all products :: Start ############
        // Collect all offers for the product and its related entities
        $offers = collect();

        // Add product's offers
        $offers = $offers->merge($product->offers);

        // Add offers from subcategories
        $product->subcategories?->each(function ($subcategory) use (&$offers) {
            $offers = $offers->merge($subcategory->offers);
        });

        // Add offers from categories
        $product->categories?->each(function ($category) use (&$offers) {
            $offers = $offers->merge($category->offers);
        });

        // Add offers from supercategories
        $product->supercategories?->each(function ($supercategory) use (&$offers) {
            $offers = $offers->merge($supercategory->offers);
        });

        // Add offers from the brand
        $offers = $offers->merge($product->brand?->offers);

        // Calculate the best discount
        $bestDiscount = $offers->map(function ($offer) use ($product) {
            $value = $offer->pivot->type === 0 ? ($offer->pivot->value / 100) * $product->final_price : $offer->pivot->value;
            return $product->final_price > $value ? round($value, 2) : $product->final_price;
        })->max();

        // Calculate the best points
        $bestPoints = $offers->where('pivot.type', 2)->max('pivot.value');

        // Check if there is a free shipping offer
        $bestFreeShipping = $offers->contains('free_shipping', true);

        $product->offer_discount = $bestDiscount;
        $product->best_price = $product->final_price - $bestDiscount;

        $product->offer_points = $bestPoints;
        $product->best_points = $product->points + $bestPoints;

        $product->offer_free_shipping = $bestFreeShipping;

        $reviews = $product->reviews;

        $product->avg_rating = $reviews->avg('rating');

        $product->reviews_count = $reviews->count();

        $products[$key] = $product;
    }

    return $products;
}

// Get the best offer for a collection (best price, best points, free shipping)
function getBestOfferForCollection($collection_id)
{
    $collection = Collection::publishedCollection()->findOrFail($collection_id);

    $offers = $collection->offers;

    $bestDiscount = $offers->map(function ($offer) use ($collection) {
        $value = $offer->pivot->type === 0 ? ($offer->pivot->value / 100) * $collection->final_price : $offer->pivot->value;
        return $collection->final_price > $value ? round($value, 2) : $collection->final_price;
    })->max();

    $bestPoints = $offers->where('pivot.type', 2)->max('pivot.value');

    $bestFreeShipping = $offers->contains('free_shipping', true);

    $collection->offer_discount = $bestDiscount;
    $collection->best_price = $collection->final_price - $bestDiscount;

    $collection->offer_points = $bestPoints;
    $collection->best_points = $collection->points + $bestPoints;

    $collection->offer_free_shipping = $bestFreeShipping;

    return $collection;
}

// Get the best offer for a list of collections (best price, best points, free shipping)
function getBestOfferForCollections($collections_id)
{
    $collections = Collection::publishedCollections($collections_id)->get();

    foreach ($collections as $key => $collection) {
        $offers = $collection->offers;

        $bestDiscount = $offers->map(function ($offer) use ($collection) {
            $value = $offer->pivot->type === 0 ? ($offer->pivot->value / 100) * $collection->final_price : $offer->pivot->value;
            return $collection->final_price > $value ? round($value, 2) : $collection->final_price;
        })->max();

        $bestPoints = $offers->where('pivot.type', 2)->max('pivot.value');

        $bestFreeShipping = $offers->contains('free_shipping', true);

        $collection->offer_discount = $bestDiscount;
        $collection->best_price = $collection->final_price - $bestDiscount;

        $collection->offer_points = $bestPoints;
        $collection->best_points = $collection->points + $bestPoints;

        $collection->offer_free_shipping = $bestFreeShipping;

        $reviews = $collection->reviews;

        $collection->avg_rating = $reviews->avg('rating');

        $collection->reviews_count = $reviews->count();
        ############ Get Best Offer for all collections :: End ############

        $collections[$key] = $collection;
    }

    return $collections;
}

// get the average rating of a product
function get_item_rating($product_id, $type = 'Product')
{
    $all_product_reviews = Review::with([
        'user' => fn ($q) => $q->select('id', 'f_name', 'l_name', 'profile_photo_path')
    ])
        ->where('reviewable_id', $product_id)
        ->where('reviewable_type', 'App\\Models\\' . $type)
        ->approved()->get();

    return $all_product_reviews;
}

################ BOSTA :: START ##################
// create bosta Order
function createBostaOrder($order)
{
    $unpaidTransactions = $order->transactions()->where('payment_status_id', PaymentStatus::Pending->value)->get();

    $paymentAmount = $unpaidTransactions->where('payment_method_id', PaymentMethod::Cash->value)->sum('payment_amount') ?? 0;

    $order_data = [
        "specs" => [
            "packageDetails"    =>      [
                "itemsCount"    => $order->num_of_items,
                "description"   => $order->package_desc,
            ],
            "size"              =>      "SMALL",
            "weight"            =>      $order->total_weight,
        ],
        "dropOffAddress" => [
            "city"          =>      $order->address->governorate->getTranslation('name', 'en'),
            "district"      =>      $order->address->city->getTranslation('name', 'en'),
            "firstLine"     =>      $order->address->details ?? $order->address->city->getTranslation('name', 'en'),
            "secondLine"    =>      $order->address->landmarks ?? '',
        ],
        "receiver" => [
            "phone"         =>      $order->user->phones->where('default', 1)->first()->phone,
            "secondPhone"   =>      $order->user->phones->where('default', 0)->count() ? $order->user->phones->where('default', 0)->first()->phone : '',
            "firstName"     =>      $order->user->f_name,
            "lastName"      =>      $order->user->l_name ? $order->user->l_name : $order->user->f_name,
            "email"         =>      $order->user->email ?? '',
        ],
        "businessReference" => "$order->id",
        "type"      =>      10,
        "notes"     =>      $order->notes ? $order->notes . ($order->user->phones->where('default', 0)->count() > 1 ? " - " . implode(' - ', $order->user->phones->where('default', 0)->pluck('phone')->toArray()) : '') : ($order->user->phones->where('default', 0)->count() > 1 ? implode(' - ', $order->user->phones->where('default', 0)->pluck('phone')->toArray()) : ''),
        "cod"       =>      $paymentAmount ? ceil($paymentAmount) : 0.00,
        "allowToOpenPackage" => true,
        "webhookUrl" => "https://www.smarttoolsegypt.com/api/orders/update-status",
    ];

    // create bosta order
    $bosta_response = Http::withHeaders([
        'Authorization'     =>  env('BOSTA_API_KEY'),
        'Content-Type'      =>  'application/json',
        'Accept'            =>  'application/json'
    ])->post('https://app.bosta.co/api/v0/deliveries', $order_data);

    $decoded_bosta_response = $bosta_response->json();

    if ($bosta_response->successful()) {

        // update order in database
        $order->update([
            'tracking_number' => $decoded_bosta_response['trackingNumber'],
            'order_delivery_id' => $decoded_bosta_response['_id'],
            'status_id' => OrderStatus::PickupRequested->value,
        ]);

        $order->statuses()->attach(OrderStatus::PickupRequested->value);
        
        return [
            'status'    =>  true,
            'data'      =>  $decoded_bosta_response,
        ];
    } else {
        return [
            'status'    =>  false,
            'data'      =>  $decoded_bosta_response,
        ];
    }
}

// edit bosta Order
function editBostaOrder($order, $old_order)
{
    $order_data = [
        "specs" => [
            "packageDetails"    =>      [
                "itemsCount"    => $order->num_of_items,
                "description"   => $order->package_desc,
            ],
            "size"              =>      "SMALL",
            "weight"            =>      $order->total_weight,
        ],
        "dropOffAddress" => [
            "city"          =>      $order->address->governorate->getTranslation('name', 'en'),
            "district"      =>      $order->address->city->getTranslation('name', 'en'),
            "firstLine"     =>      $order->address->details ?? $order->address->city->getTranslation('name', 'en'),
            "secondLine"    =>      $order->address->landmarks ?? '',
        ],
        "receiver" => [
            "phone"         =>      $order->user->phones->where('default', 1)->first()->phone,
            "firstName"     =>      $order->user->f_name,
            "lastName"      =>      $order->user->l_name ? $order->user->l_name . ($order->user->phones->where('default', 0)->count() ? " - " . implode(' - ', $order->user->phones->where('default', 0)->pluck('phone')->toArray()) : "") : ($order->user->phones->where('default', 0)->count() ? " - " . implode(' - ', $order->user->phones->where('default', 0)->pluck('phone')->toArray()) : ""),
            "email"         =>      $order->user->email ?? '',
        ],
        "businessReference" => "$old_order->id",
        "notes"     =>      $order->notes ? $order->notes . ($order->user->phones->where('default', 0)->count() > 1 ? " - " . implode(' - ', $order->user->phones->where('default', 0)->pluck('phone')->toArray()) : '') : ($order->user->phones->where('default', 0)->count() > 1 ? implode(' - ', $order->user->phones->where('default', 0)->pluck('phone')->toArray()) : ''),
        "cod"       =>      $old_order->unpaid_payment_method == PaymentMethod::Cash->value ? ceil($order->transactions()->where('payment_method_id', PaymentMethod::Cash->value)->where('payment_status_id', PaymentStatus::Pending->value)->sum('payment_amount')) : 0.00,
        "allowToOpenPackage" => true,
    ];

    // create bosta order
    $bosta_response = Http::withHeaders([
        'Authorization'     =>  env('BOSTA_API_KEY'),
        'Content-Type'      =>  'application/json',
        'Accept'            =>  'application/json'
    ])->patch('https://app.bosta.co/api/v0/deliveries/' . $order->order_delivery_id, $order_data);

    if ($bosta_response->successful()) {
        return true;
    } else {
        return false;
    }
}

// cancel bosta order
function cancelBostaOrder($order)
{
    $bosta_response = Http::withHeaders([
        'Authorization'     =>  env('BOSTA_API_KEY'),
        'Content-Type'      =>  'application/json',
        'Accept'            =>  'application/json'
    ])->delete('https://app.bosta.co/api/v0/deliveries/' . $order->tracking_number);

    if ($bosta_response->successful()) {
        $order->update([
            'tracking_number' => null,
            'order_delivery_id' => null,
        ]);

        return true;
    }

    return false;
}
################ BOSTA :: END ##################


################ PAYMOB :: START ##################
// create transaction in paymob
function payByPaymob($order, $transaction)
{
    try {
        // create paymob auth token
        $first_step = Http::acceptJson()->post('https://accept.paymob.com/api/auth/tokens', [
            "api_key" => env('PAYMOB_API_KEY')
        ])->json();

        $auth_token = $first_step['token'];

        // create paymob order
        $second_step = Http::acceptJson()->post('https://accept.paymob.com/api/ecommerce/orders', [
            "auth_token" =>  $auth_token,
            "delivery_needed" => "false",
            "amount_cents" => number_format(($transaction->payment_amount) * 100, 0, '', ''),
            "currency" => "EGP",
            "items" => []
        ])->json();

        $order_id = $second_step['id'];

        $transaction->update([
            'paymob_order_id' => $order_id,
        ]);

        // create paymob transaction
        $third_step = Http::acceptJson()->post('https://accept.paymob.com/api/acceptance/payment_keys', [
            "auth_token" => $auth_token,
            "amount_cents" => number_format(($transaction->payment_amount) * 100, 0, '', ''),
            "expiration" => 3600,
            "order_id" => $order_id,
            "billing_data" => [
                "apartment" => "NA",
                "email" => $order->user->email ?? 'test@smarttoolsegypt.com',
                "floor" => "NA",
                "first_name" => $order->user->f_name,
                "street" => "NA",
                "building" => "NA",
                "phone_number" => $order->phone1,
                "shipping_method" => "NA",
                "postal_code" => "NA",
                "city" => "NA",
                "country" => "NA",
                "last_name" => $order->user->l_name ?? $order->user->f_name,
                "state" => "NA"
            ],
            "currency" => "EGP",
            "integration_id" => $transaction->payment_method == 3 ? env('PAYMOB_CLIENT_ID_INSTALLMENTS') : env('PAYMOB_CLIENT_ID_CARD'),
        ])->json();

        $payment_key = $third_step['token'];

        return $payment_key;
    } catch (\Throwable $th) {
        return '';
    }
}

// void transaction in paymob
function voidRequestPaymob($transaction_id)
{
    try {
        $first_step = Http::acceptJson()->post('https://accept.paymob.com/api/auth/tokens', [
            "api_key" => env('PAYMOB_API_KEY')
        ])->json();

        $auth_token = $first_step['token'];

        $data = Http::acceptJson()->post('https://accept.paymob.com/api/acceptance/void_refund/void?token=' . $auth_token, [
            "transaction_id" => $transaction_id
        ])->json();

        if ($data['success'] == true && $data['is_void'] == true) {
            return true;
        }
        return false;
    } catch (\Throwable $th) {
        return false;
    }
}

// refund transaction in paymob
function refundRequestPaymob($transaction_id, $refund)
{
    try {
        $first_step = Http::acceptJson()->post('https://accept.paymob.com/api/auth/tokens', [
            "api_key" => env('PAYMOB_API_KEY')
        ])->json();

        $auth_token = $first_step['token'];

        $data = Http::acceptJson()->post('https://accept.paymob.com/api/acceptance/void_refund/refund', [
            "auth_token" => $auth_token,
            "transaction_id" => $transaction_id,
            "amount_cents" => (int)($refund),
        ])->json();

        if ($data['success'] == true) {
            return true;
        }
        return false;
    } catch (\Throwable $th) {
        return false;
    }
}
################ PAYMOB :: END ##################

################ ORDER :: START ##################
function returnTotalOrder($order)
{
    DB::beginTransaction();

    try {
        // Update Order Status :: Cancellation Requested
        $order->update([
            'status_id' => 301,
        ]);

        $order->statuses()->attach(301);

        // Return Products & Collections
        $order->products()->each(function ($product) {
            $product->quantity += $product->pivot->quantity;
            $product->pivot->quantity = 0;
            $product->pivot->save();
            $product->save();
        });

        $order->collections()->each(function ($collection) {
            $collection->products()->each(function ($product) use (&$collection) {
                $product->quantity += $collection->pivot->quantity * $product->pivot->quantity;
                $product->save();
            });

            $collection->pivot->quantity = 0;
            $collection->pivot->save();
        });

        // Return Gift Points
        $order->points()->delete();

        // Return Coupon
        if ($order->coupon && !is_null($order->coupon->number)) {
            $order->coupon->number += 1;
            $order->coupon->save();
        }

        // Update Order Status :: Cancellation Accepted
        $order->update([
            'status_id' => 302,
        ]);

        $order->statuses()->attach(302);

        DB::commit();

        return true;
    } catch (\Throwable $th) {
        //todo ::throw $th;
        // Update Order Status :: Cancellation Accepted
        $order->update([
            'status_id' => 303,
        ]);

        $order->statuses()->attach(303);

        DB::rollback();

        return false;
    }
}
################ ORDER :: END ##################


################ COUPON :: START ##################
// todo: Need Update like in coupon block component (Has no use right now) --> May replace the coupon code checkCoupon function
function getRemainingProductsCoupon($coupon_id, $products_ids, $products_quantities, $products)
{
    $coupon_discount = 0;
    $coupon_points = 0;
    $products_after_coupon = [];
    $products_best_coupon = [];

    $coupon = Coupon::with([
        'supercategories' => function ($q) {
            $q->with(['products']);
        },
        'categories' => function ($q) {
            $q->with(['products']);
        },
        'subcategories' => function ($q) {
            $q->with(['products']);
        },
        'brands' => function ($q) {
            $q->with(['products']);
        },
        'products',
    ])
        ->find($coupon_id);

    // get discount on brands
    if ($coupon->brands->count()) {
        $brands_product_from_coupon = $coupon->brands->map(function ($brand) use ($products) {
            return [
                'products' => $products->whereIn('id', $brand->products->pluck('id')),
                'type' => $brand->pivot->type,
                'value' => $brand->pivot->value,
            ];
        });

        $brands_product_from_coupon->map(function ($brand) use (&$products_after_coupon) {
            foreach ($brand['products'] as $product) {

                if ($brand['type'] == 0 && $brand['value'] < 100) {
                    $product->coupon_discount = $product->best_price * $brand['value'] / 100;
                    $product->coupon_points = 0;
                    $products_after_coupon[] = $product;
                } elseif ($brand['type'] == 1) {
                    $product->coupon_discount = $brand['value'] <= $product->best_price ? $brand['value'] : $product->best_price;
                    $product->coupon_points = 0;
                    $products_after_coupon[] = $product;
                } elseif ($brand['type'] == 2) {
                    $product->coupon_discount = 0.00;
                    $product->coupon_points = $brand['value'];
                    $products_after_coupon[] = $product;
                }
            }
        });
    }

    // get discount on subcategories
    if ($coupon->subcategories->count()) {
        $subcategories_product_from_coupon = $coupon->subcategories->map(function ($subcategory) use ($products) {
            return [
                'products' => $products->whereIn('id', $subcategory->products->pluck('id')),
                'type' => $subcategory->pivot->type,
                'value' => $subcategory->pivot->value,
            ];
        });

        $subcategories_product_from_coupon->map(function ($subcategory) use (&$products_after_coupon) {
            foreach ($subcategory['products'] as $product) {
                if ($subcategory['type'] == 0 && $subcategory['value'] < 100) {
                    $product->coupon_discount = $product->best_price * $subcategory['value'] / 100;
                    $product->coupon_points = 0;
                    $products_after_coupon[] = $product;
                } elseif ($subcategory['type'] == 1) {
                    $product->coupon_discount = $subcategory['value'] <= $product->best_price ? $subcategory['value'] : $product->best_price;
                    $product->coupon_points = 0;
                    $products_after_coupon[] = $product;
                } elseif ($subcategory['type'] == 2) {
                    $product->coupon_discount = 0.00;
                    $product->coupon_points = $subcategory['value'];
                    $products_after_coupon[] = $product;
                }
            }
        });
    }

    // get discount on categories
    if ($coupon->categories->count()) {
        $categories_product_from_coupon = $coupon->categories->map(function ($category) use ($products) {
            return [
                'products' => $products->whereIn('id', $category->products->pluck('id')),
                'type' => $category->pivot->type,
                'value' => $category->pivot->value,
            ];
        });

        $categories_product_from_coupon->map(function ($category) use (&$products_after_coupon) {
            foreach ($category['products'] as $product) {
                if ($category['type'] == 0 && $category['value'] < 100) {
                    $product->coupon_discount = $product->best_price * $category['value'] / 100;
                    $product->coupon_points = 0;
                    $products_after_coupon[] = $product;
                } elseif ($category['type'] == 1) {
                    $product->coupon_discount = $category['value'] <= $product->best_price ? $category['value'] : $product->best_price;
                    $product->coupon_points = 0;
                    $products_after_coupon[] = $product;
                } elseif ($category['type'] == 2) {
                    $product->coupon_discount = 0.00;
                    $product->coupon_points = $category['value'];
                    $products_after_coupon[] = $product;
                }
            }
        });
    }

    // get discount on supercategories
    if ($coupon->supercategories->count()) {
        $supercategories_product_from_coupon = $coupon->supercategories->map(function ($supercategory) use ($products) {
            return [
                'products' => $products->whereIn('id', $supercategory->products->pluck('id')),
                'type' => $supercategory->pivot->type,
                'value' => $supercategory->pivot->value,
            ];
        });

        $supercategories_product_from_coupon->map(function ($supercategory) use (&$products_after_coupon) {
            foreach ($supercategory['products'] as $product) {

                if ($supercategory['type'] == 0 && $supercategory['value'] < 100) {
                    $product->coupon_discount = $product->best_price * $supercategory['value'] / 100;
                    $product->coupon_points = 0;
                    $products_after_coupon[] = $product;
                } elseif ($supercategory['type'] == 1) {
                    $product->coupon_discount = $supercategory['value'] <= $product->best_price ? $supercategory['value'] : $product->best_price;
                    $product->coupon_points = 0;
                    $products_after_coupon[] = $product;
                } elseif ($supercategory['type'] == 2) {
                    $product->coupon_discount = 0.00;
                    $product->coupon_points = $supercategory['value'];
                    $products_after_coupon[] = $product;
                }
            }
        });
    }

    // get discount on products
    if ($coupon->products->count()) {
        $products_product_from_coupon = $coupon->products->map(function ($product) use ($products_ids, $products) {
            if (in_array($product->id, $products_ids)) {
                return [
                    'product' => $products->find($product->id),
                    'type' => $product->pivot->type,
                    'value' => $product->pivot->value,
                ];
            }
        })->whereNotNull();

        $products_product_from_coupon->map(function ($product) use (&$products_after_coupon) {

            if ($product['type'] == 0 && $product['value'] < 100) {
                $product['product']->coupon_discount = $product['product']->best_price * $product['value'] / 100;
                $product['product']->coupon_points = 0;
                $products_after_coupon[] = $product;
            } elseif ($product['type'] == 1) {
                $product['product']->coupon_discount = $product['value'] <= $product['product']->best_price ? $product['value'] : $product['product']->best_price;
                $product['product']->coupon_points = 0;
                $products_after_coupon[] = $product['product'];
            } elseif ($product['type'] == 2) {
                $product['product']->coupon_discount = 0.00;
                $product['product']->coupon_points = $product['value'];
                $products_after_coupon[] = $product['product'];
            }
        });
    }


    // Final Products After Coupon Application
    $products_best_coupon = collect($products_after_coupon)
        ->groupBy('id')
        ->map(function ($products) use ($products_quantities) {
            $max_discount = $products->max('coupon_discount');
            $max_points = $products->max('coupon_points');
            $products_quantity = $products_quantities[$products->first()->id];
            return [
                'product' => $products->first()->id,
                'qty' => $products_quantity,
                'coupon_discount' => $max_discount,
                'total_discount' =>  $products_quantity * $max_discount,
                'coupon_points' => $max_points,
                'total_points' => $products_quantity * $max_points,
            ];
        });

    // Total Coupon Discount After Products
    $coupon_discount = $products_best_coupon->sum('total_discount');

    // Total Coupon Points After Products
    $coupon_points = $products_best_coupon->sum('total_points');

    return [
        'coupon_discount' => $coupon_discount,
        'coupon_points' => $coupon_points,
    ];
}

############## Get Coupon Data :: Start ##############
function getCouponData($items, $coupon)
{
    $items = $items;

    $products = $items->filter(fn ($item) => $item['type'] == 'Product');

    $collections = $items->filter(fn ($item) => $item['type'] == 'Collection');

    $products_after_coupon = [];

    $collections_after_coupon = [];

    // get discount on brands
    if ($coupon->brands->count()) {
        $brands_product_from_coupon = $coupon->brands->map(function ($brand) use ($products) {
            return [
                'products' => $products->filter(fn ($product) => in_array($product['id'], $brand->products->pluck('id')->toArray())),
                'type' => $brand->pivot->type,
                'value' => $brand->pivot->value,
            ];
        });

        $brands_product_from_coupon->map(function ($brand) use (&$products_after_coupon) {
            foreach ($brand['products'] as $product) {

                if ($brand['type'] == 0 && $brand['value'] <= 100) {
                    $product['coupon_discount'] = $product['best_price'] * $brand['value'] / 100;
                    $product['coupon_points'] = 0;
                    $products_after_coupon[] = $product->toArray();
                } elseif ($brand['type'] == 1) {
                    $product['coupon_discount'] = $brand['value'] <= $product['best_price'] ? $brand['value'] : $product['best_price'];
                    $product['coupon_points'] = 0;
                    $products_after_coupon[] = $product->toArray();
                } elseif ($brand['type'] == 2) {
                    $product['coupon_discount'] = 0.00;
                    $product['coupon_points'] = $brand['value'];
                    $products_after_coupon[] = $product->toArray();
                }
            }
        });
    }

    // get discount on subcategories
    if ($coupon->subcategories->count()) {
        $subcategories_product_from_coupon = $coupon->subcategories->map(function ($subcategory) use ($products) {
            return [
                'products' => $products->filter(fn ($product) => in_array($product['id'], $subcategory->products->pluck('id')->toArray())),
                'type' => $subcategory->pivot->type,
                'value' => $subcategory->pivot->value,
            ];
        });

        $subcategories_product_from_coupon->map(function ($subcategory) use (&$products_after_coupon) {
            foreach ($subcategory['products'] as $product) {
                if ($subcategory['type'] == 0 && $subcategory['value'] <= 100) {
                    $product['coupon_discount'] = $product['best_price'] * $subcategory['value'] / 100;
                    $product['coupon_points'] = 0;
                    $products_after_coupon[] = $product->toArray();
                } elseif ($subcategory['type'] == 1) {
                    $product['coupon_discount'] = $subcategory['value'] <= $product['best_price'] ? $subcategory['value'] : $product['best_price'];
                    $product['coupon_points'] = 0;
                    $products_after_coupon[] = $product->toArray();
                } elseif ($subcategory['type'] == 2) {
                    $product['coupon_discount'] = 0.00;
                    $product['coupon_points'] = $subcategory['value'];
                    $products_after_coupon[] = $product->toArray();
                }
            }
        });
    }

    // get discount on categories
    if ($coupon->categories->count()) {
        $categories_product_from_coupon = $coupon->categories->map(function ($category) use ($products) {
            return [
                'products' => $products->filter(fn ($product) => in_array($product['id'], $category->products->pluck('id')->toArray())),
                'type' => $category->pivot->type,
                'value' => $category->pivot->value,
            ];
        });

        $categories_product_from_coupon->map(function ($category) use (&$products_after_coupon) {
            foreach ($category['products'] as $product) {
                if ($category['type'] == 0 && $category['value'] <= 100) {
                    $product['coupon_discount'] = $product['best_price'] * $category['value'] / 100;
                    $product['coupon_points'] = 0;
                    $products_after_coupon[] = $product->toArray();
                } elseif ($category['type'] == 1) {
                    $product['coupon_discount'] = $category['value'] <= $product['best_price'] ? $category['value'] : $product['best_price'];
                    $product['coupon_points'] = 0;
                    $products_after_coupon[] = $product->toArray();
                } elseif ($category['type'] == 2) {
                    $product['coupon_discount'] = 0.00;
                    $product['coupon_points'] = $category['value'];
                    $products_after_coupon[] = $product->toArray();
                }
            }
        });
    }

    // get discount on supercategories
    if ($coupon->supercategories->count()) {
        $supercategories_product_from_coupon = $coupon->supercategories->map(function ($supercategory) use ($products) {
            return [
                'products' => $products->filter(fn ($product) => in_array($product['id'], $supercategory->products->pluck('id')->toArray())),
                'type' => $supercategory->pivot->type,
                'value' => $supercategory->pivot->value,
            ];
        });

        $supercategories_product_from_coupon->map(function ($supercategory) use (&$products_after_coupon) {
            foreach ($supercategory['products'] as $product) {
                if ($supercategory['type'] == 0 && $supercategory['value'] <= 100) {
                    $product['coupon_discount'] = $product['best_price'] * $supercategory['value'] / 100;
                    $product['coupon_points'] = 0;
                    $products_after_coupon[] = $product->toArray();
                } elseif ($supercategory['type'] == 1) {
                    $product['coupon_discount'] = $supercategory['value'] <= $product['best_price'] ? $supercategory['value'] : $product['best_price'];
                    $product['coupon_points'] = 0;
                    $products_after_coupon[] = $product->toArray();
                } elseif ($supercategory['type'] == 2) {
                    $product['coupon_discount'] = 0.00;
                    $product['coupon_points'] = $supercategory['value'];
                    $products_after_coupon[] = $product->toArray();
                }
            }
        });
    }

    // get discount on products
    if ($coupon->products->count()) {
        $products_ids = $products->pluck('id')->toArray();

        $products_product_from_coupon = $coupon->products->map(function ($product) use ($products_ids, $products) {
            if (in_array($product->id, $products_ids)) {
                return [
                    'product' => $products->filter(fn ($o_product) => $product['id'] == $o_product['id'])->first(),
                    'type' => $product->pivot->type,
                    'value' => $product->pivot->value,
                ];
            }
        })->whereNotNull();

        $products_product_from_coupon->map(function ($product) use (&$products_after_coupon) {
            if ($product['type'] == 0 && $product['value'] <= 100) {
                $product['product']['coupon_discount'] = $product['product']['best_price'] * $product['value'] / 100;
                $product['product']['coupon_points'] = 0;
                $products_after_coupon[] = $product['product']->toArray();
            } elseif ($product['type'] == 1) {
                $product['product']['coupon_discount'] = $product['value'] <= $product['product']['best_price'] ? $product['value'] : $product['product']['best_price'];
                $product['product']['coupon_points'] = 0;
                $products_after_coupon[] = $product['product']->toArray();
            } elseif ($product['type'] == 2) {
                $product['product']['coupon_discount'] = 0.00;
                $product['product']['coupon_points'] = $product['value'];
                $products_after_coupon[] = $product['product']->toArray();
            }
        });
    }

    // Final Products After Coupon Application
    $products_best_coupon = collect($products_after_coupon)
        ->groupBy('id')
        ->map(function ($products) {
            $max_discount = $products->max('coupon_discount');
            $max_points = $products->max('coupon_points');
            $product_qty = $products->first()['qty'];
            $product_id = $products->first()['id'];
            return [
                'product' => $product_id,
                'qty' => $product_qty,
                'coupon_discount' => $max_discount,
                'total_discount' =>  $product_qty * $max_discount,
                'coupon_points' => $max_points,
                'total_points' => $product_qty * $max_points,
            ];
        });


    // get discount on collections
    if ($coupon->collections->count()) {
        $collections_ids = $collections->pluck('id')->toArray();

        $collections_collection_from_coupon = $coupon->collections->map(function ($collection) use ($collections_ids, $collections) {
            if (in_array($collection->id, $collections_ids)) {
                return [
                    'collection' => $collections->filter(fn ($o_collection) => $collection['id'] == $o_collection['id'])->first(),
                    'type' => $collection->pivot->type,
                    'value' => $collection->pivot->value,
                ];
            }
        })->whereNotNull();

        $collections_collection_from_coupon->map(function ($collection) use (&$collections_after_coupon) {
            if ($collection['type'] == 0 && $collection['value'] <= 100) {
                $collection['collection']['coupon_discount'] = $collection['collection']['best_price'] * $collection['value'] / 100;
                $collection['collection']['coupon_points'] = 0;
                $collections_after_coupon[] = $collection['collection']->toArray();
            } elseif ($collection['type'] == 1) {
                $collection['collection']['coupon_discount'] = $collection['value'] <= $collection['collection']['best_price'] ? $collection['value'] : $collection['collection']['best_price'];
                $collection['collection']['coupon_points'] = 0;
                $collections_after_coupon[] = $collection['collection']->toArray();
            } elseif ($collection['type'] == 2) {
                $collection['collection']['coupon_discount'] = 0.00;
                $collection['collection']['coupon_points'] = $collection['value'];
                $collections_after_coupon[] = $collection['collection']->toArray();
            }
        });
    }


    // Final Collections After Coupon Application
    $collections_best_coupon = collect($collections_after_coupon)
        ->groupBy('id')
        ->map(function ($collections) {
            $max_discount = $collections->max('coupon_discount');
            $max_points = $collections->max('coupon_points');
            $collection_qty = $collections->first()['qty'];
            $collection_id = $collections->first()['id'];
            return [
                'collection_id' => $collection_id,
                'qty' => $collection_qty,
                'coupon_discount' => $max_discount,
                'total_discount' =>  $collection_qty * $max_discount,
                'coupon_points' => $max_points,
                'total_points' => $collection_qty * $max_points,
            ];
        });


    // Total Coupon Discount After Products
    $coupon_total_discount = $products_best_coupon->sum('total_discount') + $collections_best_coupon->sum('total_discount');
    // Total Coupon Points After Products
    $coupon_total_points = $products_best_coupon->sum('total_points') + $collections_best_coupon->sum('total_points');

    // get discount on order
    if ($coupon->on_orders) {
        $order_best_coupon = [
            'type' => $coupon->type,
            'value' => $coupon->value,
        ];
    } else {
        $order_best_coupon = [
            'type' => null,
            'value' => 0,
        ];
    }

    return [
        "products_best_coupon" => $products_best_coupon,
        "collections_best_coupon" => $collections_best_coupon,
        "coupon_total_discount" => $coupon_total_discount,
        "coupon_total_points" => $coupon_total_points,
        "order_best_coupon" => $order_best_coupon
    ];
}
    ############## Get Coupon Data :: End ##############

################ COUPON :: End ##################
