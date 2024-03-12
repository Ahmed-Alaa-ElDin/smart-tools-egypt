<?php

namespace App\Livewire\Admin\Setting\Homepage\Topbrands;

use App\Models\Brand;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Topbrands extends Component
{
    public $items;

    public $brands;

    public function rules()
    {
        return [
            'items.*.superbrand_id'     =>        'required|exclude_if:items.*.superbrand_id,0|exists:supercategories,id',
            'items.*.brand_id'          =>        'required|exclude_if:items.*.brand_id,0|exists:categories,id',
        ];
    }

    ######################## Mount : Start ############################
    public function mount()
    {
        $this->brands = Brand::select([
            'id', 'name', 'top'
        ])
            ->get();

        $oldBrands = [
            0 => $this->brands->where('top', '1')->first(),
            1 => $this->brands->where('top', '2')->first(),
            2 => $this->brands->where('top', '3')->first(),
            3 => $this->brands->where('top', '4')->first(),
            4 => $this->brands->where('top', '5')->first(),
            5 => $this->brands->where('top', '6')->first(),
            6 => $this->brands->where('top', '7')->first(),
            7 => $this->brands->where('top', '8')->first(),
            8 => $this->brands->where('top', '9')->first(),
            9 => $this->brands->where('top', '10')->first(),
        ];

        $brands = $this->brands->toArray();

        $this->items = [
            0 => [
                'brands' => $brands,
                'brand_id' => $oldBrands[0] ? $oldBrands[0]->id : 0,
            ],
            1 => [
                'brands' => $brands,
                'brand_id' => $oldBrands[1] ? $oldBrands[1]->id : 0,
            ],
            2 => [
                'brands' => $brands,
                'brand_id' => $oldBrands[2] ? $oldBrands[2]->id : 0,
            ],
            3 => [
                'brands' => $brands,
                'brand_id' => $oldBrands[3] ? $oldBrands[3]->id : 0,
            ],
            4 => [
                'brands' => $brands,
                'brand_id' => $oldBrands[4] ? $oldBrands[4]->id : 0,
            ],
            5 => [
                'brands' => $brands,
                'brand_id' => $oldBrands[5] ? $oldBrands[5]->id : 0,
            ],
            6 => [
                'brands' => $brands,
                'brand_id' => $oldBrands[6] ? $oldBrands[6]->id : 0,
            ],
            7 => [
                'brands' => $brands,
                'brand_id' => $oldBrands[7] ? $oldBrands[7]->id : 0,
            ],
            8 => [
                'brands' => $brands,
                'brand_id' => $oldBrands[8] ? $oldBrands[8]->id : 0,
            ],
            9 => [
                'brands' => $brands,
                'brand_id' => $oldBrands[9] ? $oldBrands[9]->id : 0,
            ],
        ];
    }
    ######################## Mount : End ############################

    public function render()
    {
        return view('livewire.admin.setting.homepage.topbrands.topbrands');
    }

    ######################## Real Time Validation : Start ############################
    public function updated($field)
    {
        $this->validateOnly($field);
    }
    ######################## Real Time Validation : End ############################

    ######################## Save : Start ############################
    public function save()
    {
        DB::beginTransaction();

        try {
            Brand::select('id', 'top')->update(['top' => 0]);

            foreach ($this->items as $key => $item) {
                if ($item['brand_id'] > 0) {
                    $updatedBrand = Brand::select('id', 'top')->findOrFail($item['brand_id']);

                    $updatedBrand->update([
                        'top' => $key + 1
                    ]);
                }
            }

            DB::commit();

            Session::flash('success', __('admin/sitePages.Top Brands has been updated successfully'));
            redirect()->route('admin.setting.homepage');
        } catch (\Throwable $th) {
            DB::rollBack();

            Session::flash('error', __("admin/sitePages.Top Brands hasn't been updated"));
            redirect()->route('admin.setting.homepage');
        }
    }
    ######################## Save : End ############################
}
