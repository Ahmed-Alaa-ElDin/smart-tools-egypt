<?php

namespace App\Http\Livewire\Admin\Offers;

use Livewire\Component;

class OfferForm extends Component
{
    public $offer_id;

    public $name = ['en' => '', 'ar' => ''], $expire_at, $number, $on_orders = 0;

    protected $listeners = ["brandUpdated", "supercategoryUpdated", "categoryUpdated", "subcategoryUpdated"];

    public function rules()
    {
        return [
            "code"                              =>      "required|string",
            "type"                              =>      "required|in:0,1,2,3",
            "value"                             =>      ["required", "numeric", new Maxif($this->type), "min:0"],
            'expire_at'                         =>      "date",
            'number'                            =>      "nullable|numeric|min:0",
            'items.*.brand_id'                  =>      "exclude_if:items.*.brand_id,all|nullable|exists:brands,id",
            'items.*.supercategory_id'          =>      "exclude_if:items.*.supercategory_id,all|nullable|exists:supercategories,id",
            'items.*.category_id'               =>      "exclude_if:items.*.category_id,all|nullable|exists:categories,id",
            'items.*.subcategory_id'            =>      "exclude_if:items.*.subcategory_id,all|nullable|exists:subcategories,id",
            'items.*.products_id.*'             =>      "nullable|exists:products,id",
            'on_orders'                         =>      "nullable"
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
            $offer = Coupon::with([
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

            $this->code = $offer->code;
            $this->type = $offer->type;
            $this->value = $offer->value;
            $this->expire_at = $offer->expire_at;
            $this->number = $offer->number;
            $this->on_orders = $offer->on_orders;

            if ($offer->supercategories->count()) {
                $this->oldSupercategories = $offer->supercategories->toArray();
                $this->oldSupercategories_id = $offer->supercategories->pluck('id')->toArray();
            }
            if ($offer->categories->count()) {
                $this->oldCategories = $offer->categories->toArray();
                $this->oldCategories_id = $offer->categories->pluck('id')->toArray();
            }
            if ($offer->subcategories->count()) {
                $this->oldSubcategories = $offer->subcategories->toArray();
                $this->oldSubcategories_id = $offer->subcategories->pluck('id')->toArray();
            }
            if ($offer->brands->count()) {
                $this->oldBrands = $offer->brands->toArray();
                $this->oldBrands_id = $offer->brands->pluck('id')->toArray();
            }
            if ($offer->products->count()) {
                $this->oldProducts = $offer->products->toArray();
                $this->oldProducts_id = $offer->products->pluck('id')->toArray();
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
        ];
    }
    // Add Item : End


    // Delete Item : Start
    public function deleteItem($item_key)
    {
        unset($this->items[$item_key]);
    }
    // Delete Item : End


    ######################## Save New Coupon : Start ############################
    public function save($new = false)
    {
        $this->validate();


        DB::beginTransaction();

        try {
            $offer = Coupon::create([
                'code' => $this->code,
                'type' => $this->type,
                'value' => $this->value,
                'expire_at' => $this->expire_at,
                'number'  => $this->number ?? null,
            ]);

            foreach ($this->items as $item) {
                if ($item['item_type'] == 'category') {
                    if ($item['supercategory_id'] == 'all') {
                        $supercategories = Supercategory::select('id')->get()->pluck('id');
                        $offer->supercategories()->attach($supercategories);
                    } elseif ($item['category_id'] == 'all') {
                        $categories = Category::select('id', 'supercategory_id')->where('supercategory_id', $item['supercategory_id'])->get()->pluck('id');
                        $offer->categories()->attach($categories);
                    } elseif ($item['subcategory_id'] == 'all') {
                        $subcategories = Subcategory::select('id', 'category_id')->where('category_id', $item['category_id'])->get()->pluck('id');
                        $offer->subcategories()->attach($subcategories);
                    } else {
                        $offer->products()->attach($item['products_id']);
                    }
                } elseif ($item['item_type'] == 'brand') {
                    if ($item['brand_id'] == 'all') {
                        $brands = Brand::select('id')->get()->pluck('id');
                        $offer->brands()->attach($brands);
                    } else {
                        $offer->products()->attach($item['products_id']);
                    }
                } elseif ($item['item_type'] == 'order') {
                    $offer->on_orders = 1;
                    $offer->save();
                }
            }

            DB::commit();

            if ($new) {
                Session::flash('success', __('admin/offersPages.Coupon added successfully'));
                redirect()->route('admin.offers.create');
            } else {
                Session::flash('success', __('admin/offersPages.Coupon added successfully'));
                redirect()->route('admin.offers.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            Session::flash('error', __("admin/offersPages.Coupon hasn't been added"));
            redirect()->route('admin.offers.index');
        }
    }
    ######################## Save New Coupon : End ############################

    ######################## Save Updated Coupon : Start ############################
    public function update()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $this->offer->update([
                'code' => $this->code,
                'type' => $this->type,
                'value' => $this->value,
                'expire_at' => $this->expire_at,
                'number'  => $this->number ?? null,
                'on_orders'  => $this->on_orders ?? 0,
            ]);

            if (isset($this->oldSupercategories_id)) {
                $this->offer->supercategories()->sync($this->oldSupercategories_id);
            }

            if (isset($this->oldCategories_id)) {
                $this->offer->categories()->sync($this->oldCategories_id);
            }

            if (isset($this->oldSubcategories_id)) {
                $this->offer->subcategories()->sync($this->oldSubcategories_id);
            }

            if (isset($this->oldBrands_id)) {
                $this->offer->brands()->sync($this->oldBrands_id);
            }

            if (isset($this->oldProducts_id)) {
                $this->offer->products()->sync($this->oldProducts_id);
            }

            foreach ($this->items as $item) {
                if ($item['item_type'] == 'category') {
                    if ($item['supercategory_id'] == 'all') {
                        $supercategories = Supercategory::select('id')->get()->pluck('id');
                        $this->offer->supercategories()->attach($supercategories);
                    } elseif ($item['category_id'] == 'all') {
                        $categories = Category::select('id', 'supercategory_id')->where('supercategory_id', $item['supercategory_id'])->get()->pluck('id');
                        $this->offer->categories()->attach($categories);
                    } elseif ($item['subcategory_id'] == 'all') {
                        $subcategories = Subcategory::select('id', 'category_id')->where('category_id', $item['category_id'])->get()->pluck('id');
                        $this->offer->subcategories()->attach($subcategories);
                    } else {
                        $this->offer->products()->attach($item['products_id']);
                    }
                } elseif ($item['item_type'] == 'brand') {
                    if ($item['brand_id'] == 'all') {
                        $brands = Brand::select('id')->get()->pluck('id');
                        $this->offer->brands()->attach($brands);
                    } else {
                        $this->offer->products()->attach($item['products_id']);
                    }
                } elseif ($item['item_type'] == 'order') {
                    $this->offer->on_orders = 1;
                    $this->offer->save();
                }
            }

            DB::commit();

            Session::flash('success', __('admin/offersPages.Coupon updated successfully'));
            redirect()->route('admin.offers.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            Session::flash('error', __("admin/offersPages.Coupon hasn't been updated"));
            redirect()->route('admin.offers.index');
        }
    }
    ######################## Save Updated Coupon : End ############################
}
