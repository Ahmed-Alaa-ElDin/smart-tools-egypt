<?php

namespace App\Http\Livewire\Admin\Setting\General\Banners;

use App\Models\Banner;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Config;

class BannersDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;
    public $search = "";

    protected $listeners = [
        'deleteBanner'
    ];


    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'banner_name';
    }

    // Render With each update
    public function render()
    {
        $banners = Banner::withCount("mainSliderBanner", "subsliderBanner")
            ->where('banner_name', 'like', "%{$this->search}%")
            ->orWhere('description', 'like', "%{$this->search}%")
            ->orWhere('link', 'like', "%{$this->search}%")
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.setting.general.banners.banners-datatable', compact('banners'));
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

    ######## Deleted #########
    public function deleteConfirm($banner_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to delete this banner ?'),
            'confirmButtonText' => __('admin/productsPages.Delete'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'deleteBanner',
            'id' => $banner_id,
        ]);
    }

    public function deleteBanner($banner_id)
    {
        try {
            $banner = Banner::findOrFail($banner_id);
            $banner->delete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Banner has been deleted successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Banner hasn't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Deleted #########
}
