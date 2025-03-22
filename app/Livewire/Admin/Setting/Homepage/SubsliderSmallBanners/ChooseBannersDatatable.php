<?php

namespace App\Livewire\Admin\Setting\Homepage\SubsliderSmallBanners;

use App\Models\Banner;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SubsliderSmallBanner;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class ChooseBannersDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;
    public $search = "";

    public $selected = [];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'banner_name';
    }

    public function render()
    {
        $banners = Banner::withCount(["mainSliderBanner", "subsliderBanner", "subsliderSmallBanner"])
            ->where(fn ($q) => $q->where('banner_name', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%")
                ->orWhere('link', 'like', "%{$this->search}%"))
            ->whereDoesntHave("subSliderSmallBanner")
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.setting.homepage.subslider-small-banners.choose-banners-datatable', compact('banners'));
    }

    // reset pagination after new search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Add conditions of sorting
    public function setSortBy($field)
    {
        if ($this->sortDirection == 'ASC') {
            $this->sortDirection = 'DESC';
        } else {
            $this->sortDirection = 'ASC';
        }
        return $this->sortBy = $field;
    }

    public function save()
    {
        try {
            $this->validate([
                'selected' => 'required|array',
            ]);

            foreach ($this->selected as $banner_id) {
                if (SubsliderSmallBanner::count() < 5) {
                    $banner = Banner::find($banner_id);
                    $banner->subsliderSmallBanner()->create();
                }
            }

            Session::flash('success', __('admin/sitePages.Banners added successfully'));
            redirect()->route('admin.setting.homepage.subslider-small-banners.index');
        } catch (\Throwable $th) {
            Session::flash('error', __("admin/sitePages.Banners haven't been added"));
            redirect()->route('admin.setting.homepage.subslider-small-banners.index');
        }
    }
}
