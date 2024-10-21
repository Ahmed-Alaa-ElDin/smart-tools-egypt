<?php

namespace App\Livewire\Admin\Setting\General\Banners;

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
            'banner'                        =>      'nullable|mimes:jpg,jpeg,png,webp|max:2048',
            'banner_name'                   =>      'required',
            "description"                   =>      "array",
            "description.ar"                =>      "required|string|max:100",
            "description.en"                =>      "required|string|max:100",
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
        // Validate the input
        $this->validateOnly("banner");

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
            ]);

            DB::commit();

            foreach ($this->deletedImages as $deletedImage) {
                imageDelete($deletedImage, 'banners');
            }

            if ($new) {
                Session::flash('success', __('admin/sitePages.Banner added successfully'));
                redirect()->route('admin.setting.general.banners.create');
            } else {
                Session::flash('success', __('admin/sitePages.Banner added successfully'));
                redirect()->route('admin.setting.general.banners.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('error', __("admin/sitePages.Banner has not been added"));
            redirect()->route('admin.setting.general.banners.index');
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
            ]);

            DB::commit();

            foreach ($this->deletedImages as $key => $deletedImage) {
                imageDelete($deletedImage, 'banners');
            }

            Session::flash('success', __('admin/sitePages.Banner updated successfully'));
            redirect()->route('admin.setting.general.banners.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('error', __("admin/sitePages.Banner has not been updated"));
            redirect()->route('admin.setting.general.banners.index');
        }
    }
    ######################## Save Updated Banner : End ############################
}
