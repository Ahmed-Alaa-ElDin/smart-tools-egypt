<?php

namespace App\Livewire\Admin\Setting\Homepage\Sections;

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

        return view('livewire.admin.setting.homepage.sections.banners-list-form', compact('banners'));
    }

    ######## Search : Start ########
    public function updatedSearchBanner()
    {
        $this->banners_list = Banner::select(['id', 'banner_name', 'description', 'link'])
            ->whereNotIn('id', array_map(fn($banner) => $banner['id'], $this->banners))
            ->where(function ($q) {
                $q->where('description->ar', 'like', '%' . $this->searchBanner . '%')
                    ->orWhere('description->en', 'like', '%' . $this->searchBanner . '%');
            })
            ->get();

        $this->showResult = 1;
    }
    ######## Search : End ########

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

        $this->dispatch('listUpdated', ['selected_banners' => $this->banners])->to('admin.setting.homepage.sections.section-form');
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

        $this->dispatch('listUpdated', ['selected_banners' => $this->banners])->to('admin.setting.homepage.sections.section-form');
    }
    ######## Rank Down : End #########

    ######## Deleted #########
    public function removeBanner($banner_id)
    {
        try {
            $banner_key = array_search($banner_id, array_column($this->banners, 'id'));

            unset($this->banners[$banner_key]);

            $this->dispatch(
                'swalDone',
                text: __('admin/sitePages.Banner has been removed from list successfully'),
                icon: 'success'
            );

            $this->dispatch('listUpdated', ['selected_banners' => $this->banners])->to('admin.setting.homepage.sections.section-form');
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/sitePages.Banner has not been removed from list"),
                icon: 'error'
            );
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

            $this->dispatch(
                'swalDone',
                text: __('admin/sitePages.Banner has been added to the list successfully'),
                icon: 'success'
            );

            $this->dispatch('listUpdated', ['selected_banners' => $this->banners])->to('admin.setting.homepage.sections.section-form');
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/sitePages.Banner has not been added to the list"),
                icon: 'error'
            );
        }
    }
    ######## Add Banner to List : End #########
}
