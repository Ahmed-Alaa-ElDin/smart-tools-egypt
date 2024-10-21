<?php

namespace App\Livewire\Admin\Setting\Homepage\Topsubcategories;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Supercategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Topsubcategories extends Component
{
    public $items;

    public $supercategories;

    public $subcategories;

    public function rules()
    {
        return [
            'items.*.supercategory_id'     =>        'required|exclude_if:items.*.supercategory_id,0|exists:supercategories,id',
            'items.*.category_id'          =>        'required|exclude_if:items.*.category_id,0|exists:categories,id',
            'items.*.subcategory_id'       =>        'required|exclude_if:items.*.subcategory_id,0|exists:subcategories,id',
        ];
    }


    ######################## Mount : Start ############################
    public function mount()
    {
        $this->supercategories = Supercategory::select([
            'id', 'name'
        ])->get()->toArray();

        $this->subcategories = Subcategory::select([
            'id', 'name', 'category_id', 'top'
        ])->with([
            'category' => function ($q) {
                $q->select('categories.id', 'categories.name');
            },
            'supercategory' => function ($q) {
                $q->select('supercategories.id', 'supercategories.name');
            }
        ])
            ->whereBetween('top', [1, 5])
            ->get();

        $oldSubcategories = [
            0 => $this->subcategories->where('top', '1')->first(),
            1 => $this->subcategories->where('top', '2')->first(),
            2 => $this->subcategories->where('top', '3')->first(),
            3 => $this->subcategories->where('top', '4')->first(),
            4 => $this->subcategories->where('top', '5')->first(),
        ];

        $this->items = [
            0 => [
                'supercategory_id' => $oldSubcategories[0] ? $oldSubcategories[0]->supercategory->id : 0,
                'supercategories' => $this->supercategories,
                'category_id' => $oldSubcategories[0] ? $oldSubcategories[0]->category->id : 0,
                'categories' => $oldSubcategories[0] ? Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $oldSubcategories[0]->supercategory->id)->get()->toArray() : null,
                'subcategory_id' => $oldSubcategories[0] ? $oldSubcategories[0]->id : 0,
                'subcategories' => $oldSubcategories[0] ? Subcategory::select('id', 'name', 'category_id')->where('category_id', $oldSubcategories[0]->category->id)->get()->toArray() : null,
            ],
            1 => [
                'supercategory_id' => $oldSubcategories[1] ? $oldSubcategories[1]->supercategory->id : 0,
                'supercategories' => $this->supercategories,
                'category_id' => $oldSubcategories[1] ? $oldSubcategories[1]->category->id : 0,
                'categories' => $oldSubcategories[1] ? Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $oldSubcategories[1]->supercategory->id)->get()->toArray() : null,
                'subcategory_id' => $oldSubcategories[1] ? $oldSubcategories[1]->id : 0,
                'subcategories' => $oldSubcategories[1] ? Subcategory::select('id', 'name', 'category_id')->where('category_id', $oldSubcategories[1]->category->id)->get()->toArray() : null,
            ],
            2 => [
                'supercategory_id' => $oldSubcategories[2] ? $oldSubcategories[2]->supercategory->id : 0,
                'supercategories' => $this->supercategories,
                'category_id' => $oldSubcategories[2] ? $oldSubcategories[2]->category->id : 0,
                'categories' => $oldSubcategories[2] ? Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $oldSubcategories[2]->supercategory->id)->get()->toArray() : null,
                'subcategory_id' => $oldSubcategories[2] ? $oldSubcategories[2]->id : 0,
                'subcategories' => $oldSubcategories[2] ? Subcategory::select('id', 'name', 'category_id')->where('category_id', $oldSubcategories[2]->category->id)->get()->toArray() : null,
            ],
            3 => [
                'supercategory_id' => $oldSubcategories[3] ? $oldSubcategories[3]->supercategory->id : 0,
                'supercategories' => $this->supercategories,
                'category_id' => $oldSubcategories[3] ? $oldSubcategories[3]->category->id : 0,
                'categories' => $oldSubcategories[3] ? Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $oldSubcategories[3]->supercategory->id)->get()->toArray() : null,
                'subcategory_id' => $oldSubcategories[3] ? $oldSubcategories[3]->id : 0,
                'subcategories' => $oldSubcategories[3] ? Subcategory::select('id', 'name', 'category_id')->where('category_id', $oldSubcategories[3]->category->id)->get()->toArray() : null,
            ],
            4 => [
                'supercategory_id' => $oldSubcategories[4] ? $oldSubcategories[4]->supercategory->id : 0,
                'supercategories' => $this->supercategories,
                'category_id' => $oldSubcategories[4] ? $oldSubcategories[4]->category->id : 0,
                'categories' => $oldSubcategories[4] ? Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $oldSubcategories[4]->supercategory->id)->get()->toArray() : null,
                'subcategory_id' => $oldSubcategories[4] ? $oldSubcategories[4]->id : 0,
                'subcategories' => $oldSubcategories[4] ? Subcategory::select('id', 'name', 'category_id')->where('category_id', $oldSubcategories[4]->category->id)->get()->toArray() : null,
            ]
        ];
    }
    ######################## Mount : End ############################

    public function render()
    {
        return view('livewire.admin.setting.homepage.topsubcategories.topsubcategories');
    }

    ######################## Updated Supercategory : End ############################
    public function supercategoryUpdated($key)
    {
        if ($this->items[$key]['supercategory_id'] != 0) {
            $this->items[$key]['categories'] = Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $this->items[$key]['supercategory_id'])->get()->toArray();
            $this->items[$key]['category_id'] = 0;
            $this->items[$key]['subcategories'] = null;
            $this->items[$key]['subcategory_id'] = 0;
        } else {
            $this->items[$key]['categories'] = null;
            $this->items[$key]['category_id'] = 0;
            $this->items[$key]['subcategories'] = null;
            $this->items[$key]['subcategory_id'] = 0;
        }
    }
    ######################## Updated Supercategory : End ############################

    ######################## Updated Supercategory : End ############################
    public function categoryUpdated($key)
    {
        if ($this->items[$key]['category_id'] != 0) {
            $this->items[$key]['subcategories'] = Subcategory::select('id', 'name', 'category_id')->where('category_id', $this->items[$key]['category_id'])->get()->toArray();
            $this->items[$key]['subcategory_id'] = 0;
        } else {
            $this->items[$key]['subcategories'] = null;
            $this->items[$key]['subcategory_id'] = 0;
        }
    }
    ######################## Updated Supercategory : End ############################

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
            Subcategory::select('id', 'top')->update(['top' => 0]);

            foreach ($this->items as $key => $item) {
                if ($item['subcategory_id'] > 0) {
                    $updatedSubcategory = Subcategory::select('id', 'top')->findOrFail($item['subcategory_id']);

                    $updatedSubcategory->update([
                        'top' => $key + 1
                    ]);
                }
            }

            DB::commit();

            Session::flash('success', __('admin/sitePages.Top Subcategories has been updated successfully'));
            redirect()->route('admin.setting.homepage');
        } catch (\Throwable $th) {
            DB::rollBack();

            Session::flash('error', __("admin/sitePages.Top Subcategories has not been updated"));
            redirect()->route('admin.setting.homepage');
        }
    }
    ######################## Save : End ############################

}
