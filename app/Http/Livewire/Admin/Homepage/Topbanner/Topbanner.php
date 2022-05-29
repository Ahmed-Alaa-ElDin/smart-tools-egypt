<?php

namespace App\Http\Livewire\Admin\Homepage\Topbanner;

use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class Topbanner extends Component
{
    use WithFileUploads;

    public $banner_id;

    public $banner;
    public $banner_model;
    public $banner_name;
    public $deletedImages = [];
    public $description = ['en' => '', 'ar' => ''];
    public $link = '';

    public function rules()
    {
        return [
            'banner'                        =>      'nullable|mimes:jpg,jpeg,png|max:2048',
            'banner_name'                   =>      'required',
            "description"                   =>      "array",
            "description.ar"                =>      "required|string|max:100",
            "description.en"                =>      "required|string|max:100",
            'link'                          =>      "nullable|url",
        ];
    }

    // Called Once at the beginning
    public function mount()
    {
        $banner = Banner::where('top_banner', 1)->get()->first();

        if ($banner) {
            $this->banner_model = $banner;

            $this->description = [
                'en' => $banner->getTranslation('description', 'en'),
                'ar' => $banner->getTranslation('description', 'ar')
            ];

            $this->link = $banner->link;
            $this->banner_name = $banner->banner_name;
        }
    }

    public function render()
    {
        return view('livewire.admin.homepage.topbanner.topbanner');
    }

    // Validate inputs on blur : Start
    public function updated($field)
    {
        $this->validateOnly($field);
    }
    // Validate inputs on blur : End


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
            redirect()->route('admin.homepage');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            Session::flash('error', __("admin/sitePages.Banner hasn't been updated"));
            redirect()->route('admin.homepage');
        }
    }
    ######################## Save Updated Banner : End ############################
}
