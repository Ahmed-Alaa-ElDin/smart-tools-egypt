<?php

namespace App\Livewire\Admin\Setting\Homepage\Sliders;

use App\Models\Banner;
use App\Models\MainSliderBanner;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class SliderBannersDatatable extends Component
{
    use WithPagination;

    public $perPage;

    public $search = "";
    public $preview_ids = [];

    protected $listeners = [
        'deleteBanner'
    ];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');
    }

    // Render With each update
    public function render()
    {
        $banners = MainSliderBanner::with(["banner"])
            ->whereHas('banner', function ($q) {
                $q->where('description->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('description->en', 'like', '%' . $this->search . '%')
                    ->orWhere('link', 'like', '%' . $this->search . '%');
            })
            ->orderBy("rank")
            ->paginate($this->perPage);

        return view('livewire.admin.setting.homepage.sliders.slider-banners-datatable', compact('banners'));
    }

    // reset pagination after new search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function checkRank($rank, $old_rank)
    {
        $banner = MainSliderBanner::where('rank', $rank)->first();

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
        $banner = MainSliderBanner::findOrFail($banner_id);

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
        $banner = MainSliderBanner::findOrFail($banner_id);

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
        $this->dispatch('swalConfirm', text: __('admin/sitePages.Are you sure, you want to delete this banner ?'),
            confirmButtonText: __('admin/sitePages.Delete'),
            denyButtonText: __('admin/sitePages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'deleteBanner',
            id: $banner_id);
    }

    public function deleteBanner($id)
    {
        try {
            $banner = MainSliderBanner::findOrFail($id);

            $banner->delete();

            $this->dispatch('swalDone', text: __('admin/sitePages.Banner has been deleted successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/sitePages.Banner has not been deleted"),
                icon: 'error');
        }
    }
    ######## Deleted #########
}
