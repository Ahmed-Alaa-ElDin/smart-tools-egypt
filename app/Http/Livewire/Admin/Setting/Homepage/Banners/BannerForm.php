<?php

namespace App\Http\Livewire\Admin\Setting\Homepage\Banners;

use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class BannerForm extends Component
{
    use WithFileUploads;

    public $banner_id;

    public $banner;
    public $banner_model;
    public $banner_name;
    public $deletedImages = [];
    public $description = ['en' => '', 'ar' => ''];
    public $link = '';
    public $rank = 0;

    public function rules()
    {
        return [
            'banner'                        =>      'nullable|mimes:jpg,jpeg,png|max:2048',
            'banner_name'                   =>      'required',
            "description"                   =>      "array",
            "description.ar"                =>      "required|string|max:100",
            "description.en"                =>      "required|string|max:100",
            "rank"                          =>      "required|min:0|max:127|exclude_if:rank,127|unique:banners,rank," . $this->banner_id,
            'link'                          =>      "nullable",
        ];
    }

    public function messages()
    {
        return [];
    }

    // Called Once at the beginning
    public function mount()
    {
        if ($this->banner_id) {
            $banner = Banner::findOrFail($this->banner_id);

            $this->banner_model = $banner;

            $this->description = [
                'en' => $banner->getTranslation('description', 'en'),
                'ar' => $banner->getTranslation('description', 'ar')
            ];

            $this->link = $banner->link;
            $this->rank = $banner->rank;
            $this->banner_name = $banner->banner_name;
        }
    }

    // Validate inputs on blur : Start
    public function updated($field)
    {
        $this->validateOnly($field);
    }
    // Validate inputs on blur : End

    ######################## Banner Image : Start ############################
    // validate and upload photo
    public function updatedBanner($banner)
    {
        // Crop and resize photo
        try {
            $this->banner_name = singleImageUpload($banner, 'banner-', 'banners');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteBanner()
    {
        $this->deletedImages[] = $this->banner_name;

        $this->banner = null;
        $this->banner_name = null;
    }
    ######################## Banner Image : Start ############################

    ######################## Save New Banner : Start ############################
    public function save($new = false)
    {

        // dd($this->description);
        $this->validate();

        DB::beginTransaction();

        try {
            Banner::create([
                'description' => [
                    'en' => $this->description['en'],
                    'ar' => $this->description['ar']
                ],
                'link' => $this->link ?? null,
                'banner_name' => $this->banner_name ?? null,
                'rank' => $this->rank == 0 || $this->rank > 10 || !$this->rank  ? 127 : $this->rank,
            ]);


            DB::commit();

            foreach ($this->deletedImages as $deletedImage) {
                imageDelete($deletedImage, 'banners');
            }

            if ($new) {
                Session::flash('success', __('admin/sitePages.Banner added successfully'));
                redirect()->route('admin.setting.homepage.banners.create');
            } else {
                Session::flash('success', __('admin/sitePages.Banner added successfully'));
                redirect()->route('admin.setting.homepage.banners.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            Session::flash('error', __("admin/sitePages.Banner hasn't been added"));
            redirect()->route('admin.setting.homepage.banners.index');
        }
    }
    ######################## Save New Banner : End ############################

    ######################## Save Updated Banner : Start ############################
    public function update()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $this->banner_model->update([
                'description' => [
                    'en' => $this->description['en'],
                    'ar' => $this->description['ar']
                ],
                'link' => $this->link ?? null,
                'banner_name' => $this->banner_name ?? null,
                'rank' => $this->rank == 0 || $this->rank > 10 || !$this->rank  ? 127 : $this->rank,
            ]);

            DB::commit();

            foreach ($this->deletedImages as $key => $deletedImage) {
                imageDelete($deletedImage, 'banners');
            }

            Session::flash('success', __('admin/sitePages.Banner updated successfully'));
            redirect()->route('admin.setting.homepage.banners.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            Session::flash('error', __("admin/sitePages.Banner hasn't been updated"));
            redirect()->route('admin.setting.homepage.banners.index');
        }
    }
    ######################## Save Updated Banner : End ############################
}
