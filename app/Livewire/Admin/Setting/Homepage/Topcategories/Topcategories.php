<?php

namespace App\Livewire\Admin\Setting\Homepage\Topcategories;

use App\Models\Category;
use App\Models\Supercategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Topcategories extends Component
{
    public $items;

    public function rules()
    {
        return [
            'items.*.supercategory_id'     =>        'required|exclude_if:items.*.supercategory_id,0|exists:supercategories,id',
            'items.*.category_id'          =>        'required|exclude_if:items.*.category_id,0|exists:categories,id',
        ];
    }

    ######################## Mount : Start ############################
    public function mount()
    {
        $this->supercategories = Supercategory::select([
            'id', 'name'
        ])->get()->toArray();

        $this->categories = Category::select([
            'id', 'name', 'supercategory_id', 'top'
        ])->with([
            'supercategory' => function ($q) {
                $q->select('supercategories.id', 'supercategories.name');
            }
        ])
            ->whereBetween('top', [1, 10])
            ->get();

        $oldCategories = [
            0 => $this->categories->where('top', '1')->first(),
            1 => $this->categories->where('top', '2')->first(),
            2 => $this->categories->where('top', '3')->first(),
            3 => $this->categories->where('top', '4')->first(),
            4 => $this->categories->where('top', '5')->first(),
            5 => $this->categories->where('top', '6')->first(),
            6 => $this->categories->where('top', '7')->first(),
            7 => $this->categories->where('top', '8')->first(),
            8 => $this->categories->where('top', '9')->first(),
            9 => $this->categories->where('top', '10')->first(),
        ];

        $this->items = [
            0 => [
                'supercategory_id' => $oldCategories[0] ? $oldCategories[0]->supercategory->id : 0,
                'supercategories' => $this->supercategories,
                'category_id' => $oldCategories[0] ? $oldCategories[0]->id : 0,
                'categories' => $oldCategories[0] ? Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $oldCategories[0]->supercategory->id)->get()->toArray() : null,
            ],
            1 => [
                'supercategory_id' => $oldCategories[1] ? $oldCategories[1]->supercategory->id : 0,
                'supercategories' => $this->supercategories,
                'category_id' => $oldCategories[1] ? $oldCategories[1]->id : 0,
                'categories' => $oldCategories[1] ? Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $oldCategories[1]->supercategory->id)->get()->toArray() : null,
            ],
            2 => [
                'supercategory_id' => $oldCategories[2] ? $oldCategories[2]->supercategory->id : 0,
                'supercategories' => $this->supercategories,
                'category_id' => $oldCategories[2] ? $oldCategories[2]->id : 0,
                'categories' => $oldCategories[2] ? Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $oldCategories[2]->supercategory->id)->get()->toArray() : null,
            ],
            3 => [
                'supercategory_id' => $oldCategories[3] ? $oldCategories[3]->supercategory->id : 0,
                'supercategories' => $this->supercategories,
                'category_id' => $oldCategories[3] ? $oldCategories[3]->id : 0,
                'categories' => $oldCategories[3] ? Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $oldCategories[3]->supercategory->id)->get()->toArray() : null,
            ],
            4 => [
                'supercategory_id' => $oldCategories[4] ? $oldCategories[4]->supercategory->id : 0,
                'supercategories' => $this->supercategories,
                'category_id' => $oldCategories[4] ? $oldCategories[4]->id : 0,
                'categories' => $oldCategories[4] ? Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $oldCategories[4]->supercategory->id)->get()->toArray() : null,
            ],
            5 => [
                'supercategory_id' => $oldCategories[5] ? $oldCategories[5]->supercategory->id : 0,
                'supercategories' => $this->supercategories,
                'category_id' => $oldCategories[5] ? $oldCategories[5]->id : 0,
                'categories' => $oldCategories[5] ? Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $oldCategories[5]->supercategory->id)->get()->toArray() : null,
            ],
            6 => [
                'supercategory_id' => $oldCategories[6] ? $oldCategories[6]->supercategory->id : 0,
                'supercategories' => $this->supercategories,
                'category_id' => $oldCategories[6] ? $oldCategories[6]->id : 0,
                'categories' => $oldCategories[6] ? Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $oldCategories[6]->supercategory->id)->get()->toArray() : null,
            ],
            7 => [
                'supercategory_id' => $oldCategories[7] ? $oldCategories[7]->supercategory->id : 0,
                'supercategories' => $this->supercategories,
                'category_id' => $oldCategories[7] ? $oldCategories[7]->id : 0,
                'categories' => $oldCategories[7] ? Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $oldCategories[7]->supercategory->id)->get()->toArray() : null,
            ],
            8 => [
                'supercategory_id' => $oldCategories[8] ? $oldCategories[8]->supercategory->id : 0,
                'supercategories' => $this->supercategories,
                'category_id' => $oldCategories[8] ? $oldCategories[8]->id : 0,
                'categories' => $oldCategories[8] ? Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $oldCategories[8]->supercategory->id)->get()->toArray() : null,
            ],
            9 => [
                'supercategory_id' => $oldCategories[9] ? $oldCategories[9]->supercategory->id : 0,
                'supercategories' => $this->supercategories,
                'category_id' => $oldCategories[9] ? $oldCategories[9]->id : 0,
                'categories' => $oldCategories[9] ? Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $oldCategories[9]->supercategory->id)->get()->toArray() : null,
            ],
        ];
    }
    ######################## Mount : End ############################

    public function render()
    {
        return view('livewire.admin.setting.homepage.topcategories.topcategories');
    }

    ######################## Updated Supercategory : End ############################
    public function supercategoryUpdated($key)
    {
        if ($this->items[$key]['supercategory_id'] != 0) {
            $this->items[$key]['categories'] = Category::select('id', 'name', 'supercategory_id')->where('supercategory_id', $this->items[$key]['supercategory_id'])->get()->toArray();
            $this->items[$key]['category_id'] = 0;
        } else {
            $this->items[$key]['categories'] = null;
            $this->items[$key]['category_id'] = 0;
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
            Category::select('id', 'top')->update(['top' => 0]);

            foreach ($this->items as $key => $item) {
                if ($item['category_id'] > 0) {
                    $updatedCategory = Category::select('id', 'top')->findOrFail($item['category_id']);

                    $updatedCategory->update([
                        'top' => $key + 1
                    ]);
                }
            }

            DB::commit();

            Session::flash('success', __('admin/sitePages.Top Categories has been updated successfully'));
            redirect()->route('admin.setting.homepage');
        } catch (\Throwable $th) {
            DB::rollBack();

            Session::flash('error', __("admin/sitePages.Top Categories hasn't been updated"));
            redirect()->route('admin.setting.homepage');
        }
    }
    ######################## Save : End ############################

}
