<?php

namespace App\Http\Livewire\Admin\Setting\Homepage;

use App\Models\Section;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class SectionsList extends Component
{
    use WithPagination;

    public $sortDirection = 'ASC';
    public $perPage;
    public $search;

    protected $listeners = ['softDeleteSection'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');
    }

    public function render()
    {
        $sections = Section::where('title->en', '!=', "Today's Deal")
            ->where(fn ($q) => $q
                ->where('title->en', 'like', "%" . $this->search . "%")
                ->orWhere('title->ar', 'like', "%" . $this->search . "%"))
            ->orderBy('rank', $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.setting.homepage.sections-list', compact('sections'));
    }

    ######## reset pagination after new search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    ######## Check Rank : Start ########
    public function checkRank($rank, $old_rank)
    {
        $section = Section::where('rank', $rank)->first();

        if ($section) {
            $section->rank = $old_rank;
            $section->save();
        } else {
            return 0;
        }
    }
    ######## Check Rank : End ########

    ######## Rank UP : Start #########
    public function rankUp($section_id)
    {
        $section = Section::findOrFail($section_id);

        if ($section->rank > 1) {
            if ($section->rank == 127) {
                $this->checkRank(10, $section->rank);
                $section->rank = 10;
            } else {
                $this->checkRank($section->rank - 1, $section->rank);
                $section->rank--;
            }
            $section->save();
        }
    }
    ######## Rank UP : End #########

    ######## Rank Down : Start #########
    public function rankDown($section_id)
    {
        $section = Section::findOrFail($section_id);

        $this->checkRank($section->rank + 1, $section->rank);

        if ($section->rank < 11) {
            if ($section->rank == 10) {
                $section->rank = 127;
            } else {
                $section->rank++;
            }
            $section->save();
        }
    }
    ######## Rank Down : End #########

    ######## Activation Toggle #########
    public function activate($section_id)
    {
        $section = Section::findOrFail($section_id);

        try {

            $section->active = !$section->active;

            $section->save();

            $this->dispatchBrowserEvent('swalSectionActivated', [
                "text" => $section->active ? __('admin/sitePages.Section has been activated') : __('admin/sitePages.Section has been deactivated'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalSectionActivated', [
                "text" => $section->active ? __("admin/sitePages.Section hasn't been activated") : __("admin/sitePages.Section hasn't been deactivated"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Activation Toggle #########

    ######## Deleted #########
    public function deleteConfirm($section_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/sitePages.Are you sure, you want to delete this section ?'),
            'confirmButtonText' => __('admin/sitePages.Delete'),
            'denyButtonText' => __('admin/sitePages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'softDeleteSection',
            'id' => $section_id,
        ]);
    }

    public function softDeleteSection($section_id)
    {
        try {
            $section = Section::findOrFail($section_id);
            $section->delete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/sitePages.Section has been deleted successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/sitePages.Section hasn't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Deleted #########

}
