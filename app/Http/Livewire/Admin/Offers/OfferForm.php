<?php

namespace App\Http\Livewire\Admin\Offers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Supercategory;
use App\Rules\Maxif;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class OfferForm extends Component
{
    use WithFileUploads;

    public $offer_id;

    public $banner, $banner_name, $deletedImages = [];
    public $date_range = ['start' => '', 'end' => ''];
    public $title = ['en' => '', 'ar' => ''], $free_shipping = false, $on_orders = 0, $type, $value = 0, $offer_number;

    protected $listeners = ["daterangeUpdated"];

    public function rules()
    {
        return [
            'banner'                        =>      'nullable|mimes:jpg,jpeg,png|max:2048',
            "title.ar"                      =>      "required|string|max:100",
            "title.en"                      =>      "required|string|max:100",
            'date_range.start'              =>      "required|date",
            'date_range.end'                =>      "required|date",
            "type"                          =>      "nullable|in:0,1,2,3",
            "value"                         =>      ["nullable", "numeric", new Maxif($this->type), "min:0"],
            'offer_number'                  =>      "nullable|numeric|min:0",
            'items.*.brand_id'              =>      "exclude_if:items.*.brand_id,all|nullable|exists:brands,id",
            'items.*.supercategory_id'      =>      "exclude_if:items.*.supercategory_id,all|nullable|exists:supercategories,id",
            'items.*.category_id'           =>      "exclude_if:items.*.category_id,all|nullable|exists:categories,id",
            'items.*.subcategory_id'        =>      "exclude_if:items.*.subcategory_id,all|nullable|exists:subcategories,id",
            'items.*.products_id.*'         =>      "nullable|exists:products,id",
            'items.*.type'                  =>      "required|in:0,1,2,3",
            'items.*.value'                 =>      ["required", "numeric", "min:0", "exclude_unless:items.*.type,0 | max:100"],
            'items.*.offer_number'          =>      "nullable|numeric|min:0",
            'on_orders'                     =>      "nullable"
        ];
    }

    public function messages()
    {
        return [];
    }

    // Called Once at the beginning
    public function mount()
    {

        $this->brands = Brand::get();

        $this->supercategories = Supercategory::select('id', 'name')->get()->toArray();

        if ($this->offer_id) {
            $offer = Offer::with([
                'products' => function ($q) {
                    $q->select('products.id', 'products.name', 'brand_id');
                },
                'brands' => function ($q) {
                    $q->select('brands.id', 'brands.name');
                },
                'supercategories' => function ($q) {
                    $q->select('supercategories.id', 'supercategories.name');
                },
                'categories' => function ($q) {
                    $q->select('categories.id', 'categories.name', 'supercategory_id');
                },
                'subcategories' => function ($q) {
                    $q->select('subcategories.id', 'subcategories.name', 'category_id');
                }
            ])->findOrFail($this->offer_id);

            $this->offer = $offer;

            $this->title = [
                'en' => $offer->getTranslation('title', 'en'),
                'ar' => $offer->getTranslation('title', 'ar')
            ];
            $this->type = $offer->type;
            $this->value = $offer->value;
            $this->offer_number = $offer->number;
            $this->date_range = [
                'start' => $offer->start_at,
                'end' => $offer->expire_at,
            ];
            $this->on_orders = $offer->on_orders;
            $this->free_shipping = $offer->free_shipping;
            $this->banner_name = $offer->banner;

            if ($offer->supercategories->count()) {
                $this->oldSupercategories = $offer->supercategories->toArray();
                $this->deleteSupercategories_id = [];
            }
            if ($offer->categories->count()) {
                $this->oldCategories = $offer->categories->toArray();
                $this->deleteCategories_id = [];
            }
            if ($offer->subcategories->count()) {
                $this->oldSubcategories = $offer->subcategories->toArray();
                $this->deleteSubcategories_id = [];
            }
            if ($offer->brands->count()) {
                $this->oldBrands = $offer->brands->toArray();
                $this->deleteBrands_id = [];
            }
            if ($offer->products->count()) {
                $this->oldProducts = $offer->products->toArray();
                $this->deleteProducts_id = [];
            }

            $this->items = [];
        } else {
            $this->items = [[
                'item_type' => 'category',
                'supercategory_id' => 'all',
                'category_id' => 'all',
                'categories' => [],
                'subcategory_id' => 'all',
                'subcategories' => [],
                'brand_id' => 'all',
                'products' => [],
                'products_id' => [],
                'value' => 0.0,
                'type' => 0,
                'offer_number' => null,
            ]];
        }
    }

    // Validate inputs on blur : Start
    public function updated($field)
    {
        $this->validateOnly($field);

        if ($field == 'type') {
            $this->validateOnly('value');
        }
    }
    // Validate inputs on blur : End

    ######################## Banner Image : Start ############################
    // validate and upload photo
    public function updatedBanner($banner)
    {
        // Crop and resize photo
        try {
            $this->banner_name = singleImageUpload($banner, 'offer-', 'banners');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteBanner()
    {
        $this->deletedImages[] = $this->banner_name;

        $this->banner = null;
        $this->banner_name = null;
    }
    ######################## Banner Image : Start ############################

    ######################## Get Selected Datetime Range : Start ############################
    public function daterangeUpdated($start, $end)
    {
        $this->validateOnly($this->date_range['start']);
        $this->validateOnly($this->date_range['end']);

        $this->date_range = [
            'start' => $start,
            'end' => $end
        ];
    }
    ######################## Get Selected Datetime Range : End ############################

    // Select All Products : Start
    public function selectAll($item_key)
    {
        array_map(function ($value) use ($item_key) {
            array_push($this->items[$item_key]['products_id'], $value['id']);
        }, $this->items[$item_key]['products']);
    }
    // Select All Products : End


    // Deselect All Products : Start
    public function deselectAll($item_key)
    {
        $this->items[$item_key]['products_id'] = [];
    }
    // Deselect All Products : End


    // Restore the default values of item : Start
    public function modelChanged($item_key)
    {
        $item_type = $this->items[$item_key]['item_type'];

        $this->items[$item_key] = [
            'item_type' => $item_type,
            'supercategory_id' => 'all',
            'category_id' => 'all',
            'categories' => [],
            'subcategory_id' => 'all',
            'subcategories' => [],
            'brand_id' => 'all',
            'products' => [],
            'products_id' => [],
            'value' => 0.0,
            'type' => 0,
            'offer_number' => null,
        ];
    }
    // Restore the default values of item : End


    // Get the Categories of selected Supercategory : Start
    public function supercategoryUpdated($item_key)
    {
        $supercategory_id = $this->items[$item_key]['supercategory_id'];

        $this->items[$item_key]['categories'] = Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $supercategory_id)->get()->toArray();

        $this->items[$item_key]['category_id'] = 'all';
        $this->items[$item_key]['subcategory_id'] = 'all';
        $this->items[$item_key]['subcategories'] = [];
        $this->items[$item_key]['products'] = [];
        $this->items[$item_key]['products_id'] = [];
    }
    // Get the Categories of selected Supercategory : End


    // Get the Subcategories of selected Category : Start
    public function categoryUpdated($item_key)
    {
        $category_id = $this->items[$item_key]['category_id'];

        $this->items[$item_key]['subcategories'] = Subcategory::select('id', 'name', 'category_id')->where('category_id', $category_id)->get()->toArray();

        $this->items[$item_key]['subcategory_id'] = 'all';
        $this->items[$item_key]['products'] = [];
        $this->items[$item_key]['products_id'] = [];
    }
    // Get the Subcategories of selected Category : End


    // Get the Products of selected Subcategory : Start
    public function subcategoryUpdated($item_key)
    {
        $subcategory_id = $this->items[$item_key]['subcategory_id'];

        $this->items[$item_key]['products'] = Product::select('id', 'name')
            ->whereHas('subcategories', function ($q) use ($subcategory_id) {
                $q->where('subcategories.id', $subcategory_id);
            })->get()->toArray();

        $this->items[$item_key]['products_id'] = [];
    }
    // Get the Products of selected Subcategory : End


    // Get the products of selected brand
    public function brandUpdated($item_key)
    {
        $brand_id = $this->items[$item_key]['brand_id'];

        $this->items[$item_key]['products'] = Product::select('id', 'name', 'brand_id')->where('brand_id', $brand_id)->get()->toArray();

        $this->items[$item_key]['products_id'] = [];
    }
    // Get the products of selected brand


    // Add Item : Start
    public function addItem()
    {
        $this->items[] = [
            'item_type' => 'category',
            'supercategory_id' => 'all',
            'category_id' => 'all',
            'categories' => [],
            'subcategory_id' => 'all',
            'subcategories' => [],
            'brand_id' => 'all',
            'products' => [],
            'products_id' => [],
            'value' => 0.0,
            'type' => 0,
            'offer_number' => null,
        ];
    }
    // Add Item : End


    // Delete Item : Start
    public function deleteItem($item_key)
    {
        unset($this->items[$item_key]);
    }
    // Delete Item : End

    public function freeShipping()
    {
        $this->free_shipping = !$this->free_shipping;
    }

    ######################## Save New Offer : Start ############################
    public function save($new = false)
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $offer = Offer::create([
                'title' => [
                    'en' => $this->title['en'],
                    'ar' => $this->title['ar'],
                ],
                'start_at' => $this->date_range['start'],
                'expire_at' => $this->date_range['end'],
                'type' => $this->type ?? 0,
                'value' => $this->value ?? 0,
                'number'  => !is_null($this->offer_number) ? $this->offer_number : null,
                'banner' => $this->banner_name ?? null,
                'free_shipping' => $this->free_shipping ? 1 : 0
            ]);

            foreach ($this->items as $item) {
                if ($item['item_type'] == 'category') {
                    if ($item['supercategory_id'] == 'all') {
                        $supercategories = Supercategory::select('id')->with([
                            'subcategories' => fn ($q) => $q->select('subcategories.id')->with([
                                'products' => fn ($q) => $q->select('products.id')
                            ])
                        ])->get();

                        $offer->supercategories()->attach($supercategories, [
                            'type' => $item['type'],
                            'value' => $item['value'],
                            'number' => !is_null($item['offer_number']) ? $item['offer_number'] : null,
                        ]);
                    } elseif ($item['category_id'] == 'all') {
                        $categories = Category::select('id', 'supercategory_id')->where('supercategory_id', $item['supercategory_id'])->get()->pluck('id');
                        $offer->categories()->attach($categories, [
                            'type' => $item['type'],
                            'value' => $item['value'],
                            'number' => !is_null($item['offer_number']) ? $item['offer_number'] : null,
                        ]);
                    } elseif ($item['subcategory_id'] == 'all') {
                        $subcategories = Subcategory::select('id', 'category_id')->where('category_id', $item['category_id'])->get()->pluck('id');
                        $offer->subcategories()->attach($subcategories, [
                            'type' => $item['type'],
                            'value' => $item['value'],
                            'number' => !is_null($item['offer_number']) ? $item['offer_number'] : null,
                        ]);
                    } else {
                        $offer->products()->attach($item['products_id'], [
                            'type' => $item['type'],
                            'value' => $item['value'],
                            'number' => !is_null($item['offer_number']) ? $item['offer_number'] : null,
                        ]);
                    }
                } elseif ($item['item_type'] == 'brand') {
                    if ($item['brand_id'] == 'all') {
                        $brands = Brand::select('id')->get()->pluck('id');
                        $offer->brands()->attach($brands, [
                            'type' => $item['type'],
                            'value' => $item['value'],
                            'number' => !is_null($item['offer_number']) ? $item['offer_number'] : null,
                        ]);
                    } else {
                        $offer->products()->attach($item['products_id'], [
                            'type' => $item['type'],
                            'value' => $item['value'],
                            'number' => !is_null($item['offer_number']) ? $item['offer_number'] : null,
                        ]);
                    }
                } elseif ($item['item_type'] == 'order') {
                    $offer->on_orders = 1;
                    $offer->save();
                }
            }

            DB::commit();

            foreach ($this->deletedImages as $key => $deletedImage) {
                imageDelete($deletedImage, 'banners');
            }

            if ($new) {
                Session::flash('success', __('admin/offersPages.Offer added successfully'));
                redirect()->route('admin.offers.create');
            } else {
                Session::flash('success', __('admin/offersPages.Offer added successfully'));
                redirect()->route('admin.offers.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            dd($th);
            Session::flash('error', __("admin/offersPages.Offer hasn't been added"));
            redirect()->route('admin.offers.index');
        }
    }
    ######################## Save New Offer : End ############################

    ######################## Save Updated Offer : Start ############################
    public function update()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $this->offer->update([
                'title' => [
                    'en' => $this->title['en'],
                    'ar' => $this->title['ar'],
                ],
                'start_at' => $this->date_range['start'],
                'expire_at' => $this->date_range['end'],
                'type' => $this->type ?? 0,
                'value' => $this->value ?? 0,
                'number'  => !is_null($this->offer_number) ? $this->offer_number : null,
                'banner' => $this->banner_name ?? null,
                'free_shipping' => $this->free_shipping ? 1 : 0,
                'on_orders' => $this->on_orders
            ]);

            if (isset($this->deleteSupercategories_id)) {
                $this->offer->supercategories()->detach($this->deleteSupercategories_id);
            }

            if (isset($this->deleteCategories_id)) {
                $this->offer->categories()->detach($this->deleteCategories_id);
            }

            if (isset($this->deleteSubcategories_id)) {
                $this->offer->subcategories()->detach($this->deleteSubcategories_id);
            }

            if (isset($this->deleteBrands_id)) {
                $this->offer->brands()->detach($this->deleteBrands_id);
            }

            if (isset($this->deleteProducts_id)) {
                $this->offer->products()->detach($this->deleteProducts_id);
            }

            foreach ($this->items as $item) {
                if ($item['item_type'] == 'category') {
                    if ($item['supercategory_id'] == 'all') {
                        $supercategories = Supercategory::select('id')->with([
                            'subcategories' => fn ($q) => $q->select('subcategories.id')->with([
                                'products' => fn ($q) => $q->select('products.id')
                            ])
                        ])->get();

                        $this->offer->supercategories()->attach($supercategories, [
                            'type' => $item['type'],
                            'value' => $item['value'],
                            'number' => !is_null($item['offer_number']) ? $item['offer_number'] : null,
                        ]);
                    } elseif ($item['category_id'] == 'all') {
                        $categories = Category::select('id', 'supercategory_id')->where('supercategory_id', $item['supercategory_id'])->get()->pluck('id');
                        $this->offer->categories()->attach($categories, [
                            'type' => $item['type'],
                            'value' => $item['value'],
                            'number' => !is_null($item['offer_number']) ? $item['offer_number'] : null,
                        ]);
                    } elseif ($item['subcategory_id'] == 'all') {
                        $subcategories = Subcategory::select('id', 'category_id')->where('category_id', $item['category_id'])->get()->pluck('id');
                        $this->offer->subcategories()->attach($subcategories, [
                            'type' => $item['type'],
                            'value' => $item['value'],
                            'number' => !is_null($item['offer_number']) ? $item['offer_number'] : null,
                        ]);
                    } else {
                        $this->offer->products()->attach($item['products_id'], [
                            'type' => $item['type'],
                            'value' => $item['value'],
                            'number' => !is_null($item['offer_number']) ? $item['offer_number'] : null,
                        ]);
                    }
                } elseif ($item['item_type'] == 'brand') {
                    if ($item['brand_id'] == 'all') {
                        $brands = Brand::select('id')->get()->pluck('id');
                        $this->offer->brands()->attach($brands, [
                            'type' => $item['type'],
                            'value' => $item['value'],
                            'number' => !is_null($item['offer_number']) ? $item['offer_number'] : null,
                        ]);
                    } else {
                        $this->offer->products()->attach($item['products_id'], [
                            'type' => $item['type'],
                            'value' => $item['value'],
                            'number' => !is_null($item['offer_number']) ? $item['offer_number'] : null,
                        ]);
                    }
                } elseif ($item['item_type'] == 'order') {
                    $this->offer->on_orders = 1;
                    $this->offer->save();
                }
            }

            DB::commit();

            foreach ($this->deletedImages as $key => $deletedImage) {
                imageDelete($deletedImage, 'banners');
            }

            Session::flash('success', __('admin/offersPages.Offer updated successfully'));
            redirect()->route('admin.offers.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            Session::flash('error', __("admin/offersPages.Offer hasn't been updated"));
            redirect()->route('admin.offers.index');
        }
    }
    ######################## Save Updated Offer : End ############################
}
