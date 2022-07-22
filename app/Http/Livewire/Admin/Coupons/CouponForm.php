<?php

namespace App\Http\Livewire\Admin\Coupons;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Supercategory;
use App\Rules\Maxif;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CouponForm extends Component
{
    public $coupon_id;

    public $code, $type = 0, $value = 0, $expire_at, $number, $on_orders = 0, $free_shipping = 0;

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
            'items.*.type'                      =>      "required|in:0,1,2,3",
            'items.*.value'                     =>      ["required", "numeric", "min:0", "exclude_unless:items.*.type,0 | max:100"],
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

        if ($this->coupon_id) {
            $coupon = Coupon::with([
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
            ])->findOrFail($this->coupon_id);

            $this->coupon = $coupon;

            $this->code = $coupon->code;
            $this->type = $coupon->type;
            $this->value = $coupon->value;
            $this->expire_at = $coupon->expire_at;
            $this->number = $coupon->number;
            $this->on_orders = $coupon->on_orders;
            $this->free_shipping = $coupon->free_shipping;

            if ($coupon->supercategories->count()) {
                $this->oldSupercategories = $coupon->supercategories->toArray();
                $this->deleteSupercategories_id = [];
                $this->oldSupercategories_id = $coupon->supercategories->pluck('id')->toArray();
            }
            if ($coupon->categories->count()) {
                $this->oldCategories = $coupon->categories->toArray();
                $this->deleteCategories_id = [];
                $this->oldCategories_id = $coupon->categories->pluck('id')->toArray();
            }
            if ($coupon->subcategories->count()) {
                $this->oldSubcategories = $coupon->subcategories->toArray();
                $this->deleteSubcategories_id = [];
                $this->oldSubcategories_id = $coupon->subcategories->pluck('id')->toArray();
            }
            if ($coupon->brands->count()) {
                $this->oldBrands = $coupon->brands->toArray();
                $this->deleteBrands_id = [];
                $this->oldBrands_id = $coupon->brands->pluck('id')->toArray();
            }
            if ($coupon->products->count()) {
                $this->oldProducts = $coupon->products->toArray();
                $this->deleteProducts_id = [];
                $this->oldProducts_id = $coupon->products->pluck('id')->toArray();
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
            'value' => 0.0,
            'type' => 0,
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
        ];
    }
    // Add Item : End


    // Delete Item : Start
    public function deleteItem($item_key)
    {
        unset($this->items[$item_key]);
    }
    // Delete Item : End

    // Free Shipping : End
    public function freeShipping()
    {
        $this->free_shipping = !$this->free_shipping;
    }
    // Free Shipping : End


    ######################## Save New Coupon : Start ############################
    public function save($new = false)
    {
        $this->validate();


        DB::beginTransaction();

        try {
            $coupon = Coupon::create([
                'code' => $this->code,
                'expire_at' => $this->expire_at,
                'number'  => !is_null($this->number) ? $this->number : null,
                'type' => $this->type ?? 0,
                'value' => $this->value ?? 0,
                'free_shipping' => $this->free_shipping ? 1 : 0
            ]);

            foreach ($this->items as $item) {
                if ($item['item_type'] == 'category') {
                    if ($item['supercategory_id'] == 'all') {
                        $supercategories = Supercategory::select('id')->get()->pluck('id');
                        $coupon->supercategories()->attach($supercategories, [
                            'type' => $item['type'],
                            'value' => $item['value'],
                        ]);
                    } elseif ($item['category_id'] == 'all') {
                        $categories = Category::select('id', 'supercategory_id')->where('supercategory_id', $item['supercategory_id'])->get()->pluck('id');
                        $coupon->categories()->attach($categories, [
                            'type' => $item['type'],
                            'value' => $item['value'],
                        ]);
                    } elseif ($item['subcategory_id'] == 'all') {
                        $subcategories = Subcategory::select('id', 'category_id')->where('category_id', $item['category_id'])->get()->pluck('id');
                        $coupon->subcategories()->attach($subcategories, [
                            'type' => $item['type'],
                            'value' => $item['value'],
                        ]);
                    } else {
                        $coupon->products()->attach($item['products_id'], [
                            'type' => $item['type'],
                            'value' => $item['value'],
                        ]);
                    }
                } elseif ($item['item_type'] == 'brand') {
                    if ($item['brand_id'] == 'all') {
                        $brands = Brand::select('id')->get()->pluck('id');
                        $coupon->brands()->attach($brands, [
                            'type' => $item['type'],
                            'value' => $item['value'],
                        ]);
                    } else {
                        $coupon->products()->attach($item['products_id'], [
                            'type' => $item['type'],
                            'value' => $item['value'],
                        ]);
                    }
                } elseif ($item['item_type'] == 'order') {
                    $coupon->on_orders = 1;
                    $coupon->save();
                }
            }

            DB::commit();

            if ($new) {
                Session::flash('success', __('admin/offersPages.Coupon added successfully'));
                redirect()->route('admin.coupons.create');
            } else {
                Session::flash('success', __('admin/offersPages.Coupon added successfully'));
                redirect()->route('admin.coupons.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            Session::flash('error', __("admin/offersPages.Coupon hasn't been added"));
            redirect()->route('admin.coupons.index');
        }
    }
    ######################## Save New Coupon : End ############################

    ######################## Save Updated Coupon : Start ############################
    public function update()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $this->coupon->update([
                'code' => $this->code,
                'expire_at' => $this->expire_at,
                'number'  => !is_null($this->number) ? $this->number : null,
                'type' => $this->type ?? 0,
                'value' => $this->value ?? 0,
                'free_shipping' => $this->free_shipping ? 1 : 0,
                'on_orders' => $this->on_orders ?? 0,
            ]);

            if (isset($this->deleteSupercategories_id)) {
                $this->coupon->supercategories()->detach($this->deleteSupercategories_id);
            }

            if (isset($this->deleteCategories_id)) {
                $this->coupon->categories()->detach($this->deleteCategories_id);
            }

            if (isset($this->deleteSubcategories_id)) {
                $this->coupon->subcategories()->detach($this->deleteSubcategories_id);
            }

            if (isset($this->deleteBrands_id)) {
                $this->coupon->brands()->detach($this->deleteBrands_id);
            }

            if (isset($this->deleteProducts_id)) {
                $this->coupon->products()->detach($this->deleteProducts_id);
            }


            foreach ($this->items as $item) {
                if ($item['item_type'] == 'category') {
                    if ($item['supercategory_id'] == 'all') {
                        $supercategories = Supercategory::select('id')->get()->pluck('id');
                        $this->coupon->supercategories()->attach($supercategories, [
                            'type' => $item['type'],
                            'value' => $item['value'],
                        ]);
                    } elseif ($item['category_id'] == 'all') {
                        $categories = Category::select('id', 'supercategory_id')->where('supercategory_id', $item['supercategory_id'])->get()->pluck('id');
                        $this->coupon->categories()->attach($categories, [
                            'type' => $item['type'],
                            'value' => $item['value'],
                        ]);
                    } elseif ($item['subcategory_id'] == 'all') {
                        $subcategories = Subcategory::select('id', 'category_id')->where('category_id', $item['category_id'])->get()->pluck('id');
                        $this->coupon->subcategories()->attach($subcategories, [
                            'type' => $item['type'],
                            'value' => $item['value'],
                        ]);
                    } else {
                        $this->coupon->products()->attach($item['products_id'], [
                            'type' => $item['type'],
                            'value' => $item['value'],
                        ]);
                    }
                } elseif ($item['item_type'] == 'brand') {
                    if ($item['brand_id'] == 'all') {
                        $brands = Brand::select('id')->get()->pluck('id');
                        $this->coupon->brands()->attach($brands, [
                            'type' => $item['type'],
                            'value' => $item['value'],
                        ]);
                    } else {
                        $this->coupon->products()->attach($item['products_id'], [
                            'type' => $item['type'],
                            'value' => $item['value'],
                        ]);
                    }
                } elseif ($item['item_type'] == 'order') {
                    $this->coupon->on_orders = 1;
                    $this->coupon->save();
                }
            }

            DB::commit();

            Session::flash('success', __('admin/offersPages.Coupon updated successfully'));
            redirect()->route('admin.coupons.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            Session::flash('error', __("admin/offersPages.Coupon hasn't been updated"));
            redirect()->route('admin.coupons.index');
        }
    }
    ######################## Save Updated Coupon : End ############################
}
