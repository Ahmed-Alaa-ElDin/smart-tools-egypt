<?php

namespace App\Http\Livewire\Admin\Homepage\Banners;

use App\Models\Banner;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class BannersDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";
    public $preview_ids = [];

    protected $listeners = [
        'deleteBanner'
    ];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'rank';
    }

    // Render With each update
    public function render()
    {
        $banners = Banner::where(function ($query) {
            return $query
                ->where('description->ar', 'like', '%' . $this->search . '%')
                ->orWhere('description->en', 'like', '%' . $this->search . '%')
                ->orWhere('link', 'like', '%' . $this->search . '%');
        })->where("top_banner", 0)
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.homepage.banners.banners-datatable', compact('banners'));
    }

    // reset pagination after new search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Add conditions of sorting
    public function sortBy($field)
    {
        if ($this->sortDirection == 'ASC') {
            $this->sortDirection = 'DESC';
        } else {
            $this->sortDirection = 'ASC';
        }

        if ($field == 'description') {
            return $this->sortBy = 'description->' . session('locale');
        }

        return $this->sortBy = $field;
    }

    public function checkRank($rank, $old_rank)
    {
        $banner = Banner::where('rank', $rank)->first();

        if ($banner) {
            $banner->rank = $old_rank;
            $banner->save();
        } else {
            return 0;
        }
    }

    ######## Rank UP : Start #########
    public function rankUp($banner_id)
    {
        $banner = Banner::findOrFail($banner_id);

        if ($banner->rank > 1) {
            if ($banner->rank == 127) {
                $this->checkRank(10, $banner->rank);
                $banner->rank = 10;
            } else {
                $this->checkRank($banner->rank - 1, $banner->rank);
                $banner->rank--;
            }
            $banner->save();
        }
    }
    ######## Rank UP : End #########

    ######## Rank Down : Start #########
    public function rankDown($banner_id)
    {
        $banner = Banner::findOrFail($banner_id);

        $this->checkRank($banner->rank + 1, $banner->rank);

        if ($banner->rank < 11) {
            if ($banner->rank == 10) {
                $banner->rank = 127;
            } else {
                $banner->rank++;
            }
            $banner->save();
        }
    }
    ######## Rank Down : End #########

    ######## Toggle Preview : Start #########
    public function togglePreview($banner_id)
    {
        if (($key = array_search($banner_id, $this->preview_ids)) !== false) {
            unset($this->preview_ids[$key]);
        } else {
            array_push($this->preview_ids, $banner_id);
        }
    }
    ######## Toggle Preview : End #########

    ######## Deleted #########
    public function deleteConfirm($banner_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/sitePages.Are you sure, you want to delete this banner ?'),
            'confirmButtonText' => __('admin/sitePages.Delete'),
            'denyButtonText' => __('admin/sitePages.Cancel'),
            'confirmButtonColor' => 'red',
            'func' => 'deleteBanner',
            'banner_id' => $banner_id,
        ]);
    }

    public function deleteBanner($banner_id)
    {
        try {
            $banner = Banner::findOrFail($banner_id);

            $banner->delete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/sitePages.Banner has been deleted successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/sitePages.Banner hasn't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Deleted #########
}
