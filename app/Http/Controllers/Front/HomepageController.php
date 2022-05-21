<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Section;
use App\Models\Supercategory;

class HomepageController extends Controller
{
    public function index()
    {

        ############## Get All Active Sections with Relations :: Start ##############
        $sections = Section::with([
            'products' => fn ($q) => $q->publishedproduct()->withPivot('rank')->orderBy('rank'),
            'offers' => fn ($q) => $q->with([
                'directProducts' => fn ($q) => $q->where('publish', 1),
                'supercategoryProducts' => fn ($q) => $q->where('publish', 1),
                'categoryProducts' => fn ($q) => $q->where('publish', 1),
                'subcategoryProducts' => fn ($q) => $q->where('publish', 1),
                'brandProducts' => fn ($q) => $q->where('publish', 1),
            ]),
            'banners'
        ])->where('active', 1)->orderBy('rank')->get();
        ############## Get All Active Sections with Relations :: End ##############

        ############ Extract of products From Sections :: Start ############
        foreach ($sections as $section) {
            if ($section->products->count()) {
                $section->products->map(function ($product) {
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

                    return $product;
                });
            } elseif ($section->offers->count()) {
                $section->offers->map(function ($offer) {
                    $offer->directProducts->push(...$offer->supercategoryProducts, ...$offer->categoryProducts, ...$offer->subcategoryProducts, ...$offer->brandProducts);

                    $offer->uniqueProducts = $offer->directProducts->unique('id');

                    if ($offer->uniqueProducts->count() > 11) {
                        $offer->uniqueProducts = $offer->uniqueProducts->random(11)->shuffle();
                    } else {
                        $offer->uniqueProducts = $offer->uniqueProducts->random($offer->uniqueProducts->count())->shuffle();
                    }

                    $products_id = $offer->uniqueProducts->pluck('id');

                    $products = Product::whereIn('id', $products_id)->publishedproduct()->get();

                    $products->map(function ($product) {
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

                        return $product;
                    });

                    $offer->finalProducts = $products;

                    return $offer;
                });

                // dd($section->offers);
            }
        }
        ############ Extract of products From Sections :: End ############

        $homepage_sections = $sections->where("today_deals", 0);
        $today_deals_sections = $sections->where("today_deals", 1)->first();

        return view('front.homepage.homepage', compact('homepage_sections', 'today_deals_sections'));
    }
}
