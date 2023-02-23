<?php

namespace App\Http\Livewire\Admin\Setting\Homepage\Topsupercategories;

use App\Models\Supercategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class TopSuperCategories extends Component
{
    public function mount()
    {
        $this->supercategories = Supercategory::select([
            'id', 'name', 'top'
        ])->get();

        $this->selectedSupercategories = [
            0 => $this->supercategories->where('top', '=', 1)->first()->id ?? 0,
            1 => $this->supercategories->where('top', '=', 2)->first()->id ?? 0,
            2 => $this->supercategories->where('top', '=', 3)->first()->id ?? 0,
            3 => $this->supercategories->where('top', '=', 4)->first()->id ?? 0,
            4 => $this->supercategories->where('top', '=', 5)->first()->id ?? 0,
        ];
    }

    public function render()
    {
        return view('livewire.admin.setting.homepage.topsupercategories.top-super-categories');
    }

    public function save()
    {
        DB::beginTransaction();

        try {
            Supercategory::select('id', 'top')->update(['top' => 0]);

            foreach ($this->selectedSupercategories as $key => $selectedSupercategory) {
                if ($selectedSupercategory > 0) {
                    $updatedSupercategory = Supercategory::select('id', 'top')->findOrFail($selectedSupercategory);

                    $updatedSupercategory->update([
                        'top' => $key + 1
                    ]);
                }
            }

            DB::commit();

            Session::flash('success', __('admin/sitePages.Top Supercategories has been updated successfully'));
            redirect()->route('admin.setting.homepage');
        } catch (\Throwable $th) {
            DB::rollBack();

            Session::flash('error', __("admin/sitePages.Top Supercategories hasn't been updated"));
            redirect()->route('admin.setting.homepage');
        }
    }
}
