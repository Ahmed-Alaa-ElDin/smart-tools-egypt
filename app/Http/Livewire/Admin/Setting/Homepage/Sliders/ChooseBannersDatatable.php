<?php

namespace App\Http\Livewire\Admin\Setting\Homepage\Sliders;

use App\Models\Banner;
use Livewire\Component;
use Livewire\WithPagination;
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
        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'banner_name';
    }

    // Render With each update
    public function render()
    {
        $banners = Banner::withCount("mainSliderBanner")
            ->where(fn ($q) => $q->where('banner_name', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%")
                ->orWhere('link', 'like', "%{$this->search}%"))
            ->whereDoesntHave("mainSliderBanner")
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.setting.homepage.sliders.choose-banners-datatable', compact('banners'));
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
        return $this->sortBy = $field;
    }

    public function save()
    {
        try {
            $this->validate([
                'selected' => 'required|array',
            ]);
    
            foreach ($this->selected as $banner_id) {
                $banner = Banner::find($banner_id);
                $banner->mainSliderBanner()->create();
            }

            Session::flash('success', __('admin/sitePages.Banners added successfully'));
            redirect()->route('admin.setting.homepage.sliders.index');
        } catch (\Throwable $th) {
            Session::flash('error', __("admin/sitePages.Banners haven't been added"));
            redirect()->route('admin.setting.homepage.sliders.index');
        }
    }
}
