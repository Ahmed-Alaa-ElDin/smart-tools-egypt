<?php

use App\Models\Coupon;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

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
        throw $th;
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
function getBestOfferForProduct($product_id)
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

    // Get Final Prices Fromi Direct Offers
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
    $product->best_points = array_sum($all_points);

    $product->free_shipping = $free_shipping;
    ############ Get Best Offer for all products :: End ############

    return $product;
}

// Get the best offer for a list of products (best price, best points, free shipping)
function getBestOfferForProducts($products_id)
{
    $products = Product::publishedproducts($products_id)->get();

    foreach ($products as $key => $product) {
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

            // Percentage Offer
            if ($offer['type'] == 0) {
                $all_prices[] = round($product->final_price - (($offer['value'] / 100) * $product->final_price), 2);
            }
            // Fixed Offer
            elseif ($offer['type'] == 1) {
                if ($product->final_price >  $offer['value']) {
                    $all_prices[] = round($product->final_price - $offer['value'], 2);
                } else {
                    $all_prices[] = 0;
                }
            }
            // Points Offer
            elseif ($offer['type'] == 2) {
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
        $product->best_points = array_sum($all_points);

        // Get the Free Shipping
        $product->free_shipping = $free_shipping;

        $reviews = $product->reviews;

        $product->avg_rating = $reviews->avg('rating');

        $product->reviews_count = $reviews->count();
        ############ Get Best Offer for all products :: End ############

        $products[$key] = $product;
    }

    return $products;
}

// get the average rating of a product
function get_product_rating($product_id)
{
    $all_product_reviews = Review::with([
        'user' => fn ($q) => $q->select('id', 'f_name', 'l_name', 'profile_photo_path')
    ])
        ->where('product_id', $product_id)
        ->approved()->get();

    return $all_product_reviews;
}

################ BOSTA :: START ##################
// create bosta Order
function createBostaOrder($order)
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
            "secondPhone"   =>      $order->user->phones->where('default', 0)->count() ? $order->user->phones->where('default', 0)->first()->phone : '',
            "firstName"     =>      $order->user->f_name,
            "lastName"      =>      $order->user->l_name ? $order->user->l_name : $order->user->f_name,
            "email"         =>      $order->user->email ?? '',
        ],
        "businessReference" => "$order->id",
        "type"      =>      10,
        "notes"     =>      $order->notes ? $order->notes . ($order->user->phones->where('default', 0)->count() > 1 ? " - " . implode(' - ', $order->user->phones->where('default', 0)->pluck('phone')->toArray()) : '') : ($order->user->phones->where('default', 0)->count() > 1 ? implode(' - ', $order->user->phones->where('default', 0)->pluck('phone')->toArray()) : ''),
        "cod"       =>      $order->payment_method == 1 ? $order->should_pay : 0.00,
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
function editBostaOrder($order,$old_order_id)
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
        "businessReference" => "$old_order_id",
        "notes"     =>      $order->notes ?? '',
        "cod"       =>      $order->payment_method == 1 ? $order->subtotal_final + $order->delivery_fees : 0.00,
        "allowToOpenPackage" => true,
    ];

    // create bosta order
    $bosta_response = Http::withHeaders([
        'Authorization'     =>  env('BOSTA_API_KEY'),
        'Content-Type'      =>  'application/json',
        'Accept'            =>  'application/json'
    ])->patch('https://app.bosta.co/api/v0/deliveries/' . $order->order_delivery_id, $order_data);

    // $decoded_bosta_response = $bosta_response->json();

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
function payByPaymob($payment)
{
    $order = $payment->order;

    try {
        // create paymob auth token
        $first_step = Http::acceptJson()->post('https://accept.paymob.com/api/auth/tokens', [
            "api_key" => env('PAYMOB_TOKEN')
        ])->json();

        $auth_token = $first_step['token'];

        // create paymob order
        $second_step = Http::acceptJson()->post('https://accept.paymob.com/api/ecommerce/orders', [
            "auth_token" =>  $auth_token,
            "delivery_needed" => "false",
            "amount_cents" => number_format(($order->should_pay) * 100, 0, '', ''),
            "currency" => "EGP",
            "items" => []
        ])->json();

        $order_id = $second_step['id'];

        $payment->update([
            'paymob_order_id' => $order_id,
        ]);

        // create paymob transaction
        $third_step = Http::acceptJson()->post('https://accept.paymob.com/api/acceptance/payment_keys', [
            "auth_token" => $auth_token,
            "amount_cents" => number_format(($order->should_pay) * 100, 0, '', ''),
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
            "integration_id" => $order->payment_method == 3 ? env('PAYMOB_CLIENT_ID_INSTALLMENTS') : env('PAYMOB_CLIENT_ID_CARD_TEST'),
        ])->json();

        $payment_key = $third_step['token'];

        return $payment_key;
    } catch (\Throwable $th) {
        return false;
    }
}

// void transaction in paymob
function voidRequestPaymob($transaction_id)
{
    try {
        $first_step = Http::acceptJson()->post('https://accept.paymob.com/api/auth/tokens', [
            "api_key" => env('PAYMOB_TOKEN')
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
            "api_key" => env('PAYMOB_TOKEN')
        ])->json();

        $auth_token = $first_step['token'];

        $data = Http::acceptJson()->post('https://accept.paymob.com/api/acceptance/void_refund/refund', [
            "auth_token" => $auth_token,
            "transaction_id" => $transaction_id,
            "amount_cents" => $refund * 100,
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
    $user = $order->user;

    $products = $order->products;

    $user->update([
        'points' => $user->points - $order->gift_points + $order->used_points,
        'balance' => $user->balance + $order->used_balance,
    ]);

    if ($order->order_delivery_id != null) {
        foreach ($products as $product) {
            $product->update([
                'quantity' => $product->quantity + $product->pivot->quantity,
            ]);
        }
    }

    $order->update([
        'num_of_items' => 0,
        'status_id' => 9,
        'gift_points' => 0,
        'used_points' => 0,
        'used_balance' => 0.00,
    ]);

    $order->statuses()->attach(9);

    $order->products()->syncWithPivotValues(
        $products,
        ['quantity' => 0]
    );
}
################ ORDER :: END ##################


################ COUPON :: START ##################
function getCoupon($coupon, $products_ids, $products_quantities, $products_best_prices)
{
    $coupon_discount = 0;
    $coupon_points = 0;
    $coupon_shipping = null;

    // get discount on order
    if ($coupon->on_orders) {
        // percentage discount
        if ($coupon->type == 0 && $coupon->value < 100) {
            $coupon_discount += $products_best_prices * $coupon->value / 100;
        }
        // fixed discount
        elseif ($coupon->type == 1) {
            $coupon_discount += $coupon->value;
        }
        // points
        elseif ($coupon->type == 2) {
            $coupon_points += $coupon->value;
        }
    }

    // get free shipping
    $coupon_shipping = $coupon->free_shipping ? 0 : null;

    // get discount on brands
    if ($coupon->brands->count()) {
        $brands_product_from_coupon = $coupon->brands->map(function ($brand) use ($products_ids) {
            return [
                'products' => $brand->products->whereIn('id', $products_ids),
                'type' => $brand->pivot->type,
                'value' => $brand->pivot->value,
            ];
        });


        $brands_product_from_coupon->map(function ($brand) use (&$coupon_discount, &$coupon_points, $products_quantities) {
            foreach ($brand['products'] as $product) {
                $product_qty = $products_quantities[$product->id];

                if ($brand['type'] == 0 && $brand['value'] < 100) {
                    $coupon_discount += $product_qty * ($product->final_price * $brand['value'] / 100);
                } elseif ($brand['type'] == 1) {
                    $coupon_discount = $brand['value'] <= $product->final_price ? $coupon_discount + ($product_qty * $brand['value']) : ($product_qty * $product->final_price);
                } elseif ($brand['type'] == 2) {
                    $coupon_points += $product_qty * $brand['value'];
                }
            }
        });
    }

    // get discount on subcategories
    if ($coupon->subcategories->count()) {
        $subcategories_product_from_coupon = $coupon->subcategories->map(function ($subcategory) use ($products_ids) {
            return [
                'products' => $subcategory->products->whereIn('id', $products_ids),
                'type' => $subcategory->pivot->type,
                'value' => $subcategory->pivot->value,
            ];
        });

        $subcategories_product_from_coupon->map(function ($subcategory) use (&$coupon_discount, &$coupon_points, $products_quantities) {
            foreach ($subcategory['products'] as $product) {
                $product_qty = $products_quantities[$product->id];

                if ($subcategory['type'] == 0 && $subcategory['value'] < 100) {
                    $coupon_discount += $product_qty * ($product->final_price * $subcategory['value'] / 100);
                } elseif ($subcategory['type'] == 1) {
                    $coupon_discount = $subcategory['value'] <= $product->final_price ? $coupon_discount + ($product_qty * $subcategory['value']) : ($product_qty * $product->final_price);
                } elseif ($subcategory['type'] == 2) {
                    $coupon_points += $product_qty * $subcategory['value'];
                }
            }
        });
    }

    // get discount on categories
    if ($coupon->categories->count()) {
        $categories_product_from_coupon = $coupon->categories->map(function ($category) use ($products_ids) {
            return [
                'products' => $category->products->whereIn('id', $products_ids),
                'type' => $category->pivot->type,
                'value' => $category->pivot->value,
            ];
        });

        $categories_product_from_coupon->map(function ($category) use (&$coupon_discount, &$coupon_points, $products_quantities) {
            foreach ($category['products'] as $product) {
                $product_qty = $products_quantities[$product->id];

                if ($category['type'] == 0 && $category['value'] < 100) {
                    $coupon_discount += $product_qty * ($product->final_price * $category['value'] / 100);
                } elseif ($category['type'] == 1) {
                    $coupon_discount = $category['value'] <= $product->final_price ? $coupon_discount + ($product_qty * $category['value']) : ($product_qty * $product->final_price);
                } elseif ($category['type'] == 2) {
                    $coupon_points += $product_qty * $category['value'];
                }
            }
        });
    }

    // get discount on supercategories
    if ($coupon->supercategories->count()) {
        $supercategories_product_from_coupon = $coupon->supercategories->map(function ($supercategory) use ($products_ids) {
            return [
                'products' => $supercategory->products->whereIn('id', $products_ids),
                'type' => $supercategory->pivot->type,
                'value' => $supercategory->pivot->value,
            ];
        });

        $supercategories_product_from_coupon->map(function ($supercategory) use (&$coupon_discount, &$coupon_points, $products_quantities) {
            foreach ($supercategory['products'] as $product) {
                $product_qty = $products_quantities[$product->id];

                if ($supercategory['type'] == 0 && $supercategory['value'] < 100) {
                    $coupon_discount += $product_qty * ($product->final_price * $supercategory['value'] / 100);
                } elseif ($supercategory['type'] == 1) {
                    $coupon_discount = $supercategory['value'] <= $product->final_price ? $coupon_discount + ($product_qty * $supercategory['value']) : ($product_qty * $product->final_price);
                } elseif ($supercategory['type'] == 2) {
                    $coupon_points += $product_qty * $supercategory['value'];
                }
            }
        });
    }

    // get discount on products
    if ($coupon->products->count()) {
        $products_product_from_coupon = $coupon->products->map(function ($product) use ($products_ids) {
            if (in_array($product->id, $products_ids)) {
                return [
                    'product' => $product,
                    'type' => $product->pivot->type,
                    'value' => $product->pivot->value,
                ];
            }
        })->whereNotNull();

        $products_product_from_coupon->map(function ($product) use (&$coupon_discount, &$coupon_points, $products_quantities) {
            $product_qty = $products_quantities[$product['product']->id];

            if ($product['type'] == 0 && $product['value'] < 100) {
                $coupon_discount += $product_qty * ($product['product']->final_price * $product['value'] / 100);
            } elseif ($product['type'] == 1) {
                $coupon_discount = $product['value'] <= $product['product']->final_price ? $coupon_discount + ($product_qty * $product['value']) : ($product_qty * $product['product']->final_price);
            } elseif ($product['type'] == 2) {
                $coupon_points += $product_qty * $product['value'];
            }
        });

        return [
            'coupon_discount' => $coupon_discount,
            'coupon_discount_percentage' => $products_best_prices > 0 ? round($coupon_discount * 100 / $products_best_prices) : 0,
            'coupon_points' => $coupon_points,
            'coupon_shipping' => $coupon_shipping
        ];
    }
}
################ COUPON :: START ##################
