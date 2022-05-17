<?php

namespace App\Http\Livewire\Admin\Homepage\Sections;

use App\Models\Banner;
use Livewire\Component;

class BannersListForm extends Component
{

    public $addBanner = 0;

    public $banner_id;
    public $banners_list, $searchBanner = '', $showResult = 1;
    public $banners = [];

    protected $listeners = ['showResults'];

    public function render()
    {
        if (count($this->banners) >= 3) {
            $this->addBanner = 0;
        }

        $banners = usort($this->banners, function ($a, $b) {
            return $a['rank'] <=> $b['rank'];
        });

        $this->banners_list = Banner::select(['id', 'banner_name', 'description', 'link'])
            ->whereNotIn('id', array_map(fn ($banner) => $banner['id'], $this->banners))
            ->where(function ($q) {
                $q->where('description->ar', 'like', '%' . $this->searchBanner . '%')
                    ->orWhere('description->en', 'like', '%' . $this->searchBanner . '%');
            })
            ->get();

        return view('livewire.admin.homepage.sections.banners-list-form', compact('banners'));
    }

    ######## Check Rank : Start ########
    public function checkRank($rank, $old_rank)
    {
        $banner_key = array_search($rank, array_column($this->banners, 'rank'));

        if ($banner_key !== false) {
            $this->banners[$banner_key]['rank'] = $old_rank;
        }
    }
    ######## Check Rank : End ########

    ######## Rank UP : Start #########
    public function rankUp($banner_id)
    {
        $banner_key = array_search($banner_id, array_column($this->banners, 'id'));

        if ($this->banners[$banner_key]['rank'] > 1) {
            $this->checkRank($this->banners[$banner_key]['rank'] - 1, $this->banners[$banner_key]['rank']);
            $this->banners[$banner_key]['rank']--;
        }

        $this->emitTo('admin.homepage.sections.section-form', 'listUpdated', ['selected_banners' => $this->banners]);
    }
    ######## Rank UP : End #########

    ######## Rank Down : Start #########
    public function rankDown($banner_id)
    {
        $banner_key = array_search($banner_id, array_column($this->banners, 'id'));

        if ($this->banners[$banner_key]['rank'] < 3) {
            $this->checkRank($this->banners[$banner_key]['rank'] + 1, $this->banners[$banner_key]['rank']);
            $this->banners[$banner_key]['rank']++;
        }

        $this->emitTo('admin.homepage.sections.section-form', 'listUpdated', ['selected_banners' => $this->banners]);
    }
    ######## Rank Down : End #########

    ######## Deleted #########
    public function removeBanner($banner_id)
    {
        try {
            $banner_key = array_search($banner_id, array_column($this->banners, 'id'));

            unset($this->banners[$banner_key]);

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/sitePages.Banner has been removed from list successfully'),
                'icon' => 'success'
            ]);

            $this->emitTo('admin.homepage.sections.section-form', 'listUpdated', ['selected_banners' => $this->banners]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/sitePages.Banner hasn't been removed from list"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Deleted #########

    ######## Banner Selected : Start ########
    public function bannerSelected($banner_id, $banner_name)
    {
        $this->searchBanner = $banner_name;
        $this->banner_id = $banner_id;
        $this->showResult = 0;
    }
    ######## Banner Selected : End ########

    ######## Show Results : Start ########
    public function showResults($status)
    {
        $this->showResult = $status;
    }
    ######## Show Results : End ########

    ######## Add Banner to List : Start #########
    public function add()
    {
        try {
            $banner = Banner::select(['id', 'banner_name', 'description', 'link'])->findOrFail($this->banner_id)->toArray();
            $banner['rank'] = 3;

            $this->searchBanner = null;
            $this->banner_id = null;

            $this->banners[] = $banner;

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/sitePages.Banner has been added to the list successfully'),
                'icon' => 'success'
            ]);

            $this->emitTo('admin.homepage.sections.section-form', 'listUpdated', ['selected_banners' => $this->banners]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/sitePages.Banner hasn't been added to the list"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Add Banner to List : End #########
}
