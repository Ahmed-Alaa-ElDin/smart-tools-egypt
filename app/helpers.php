<?php

use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\File;
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
        $product->best_points = max($all_points);

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
