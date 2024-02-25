<?php

namespace App\Livewire\Admin\Setting\General\NavLinks;

use App\Models\NavLink;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PhpParser\Node\Expr\Isset_;

class NavLinksForm extends Component
{
    protected $rules = [
        'nav_links.*.name.ar' => 'required_with:nav_links.*.name.en|required_with:nav_links.*.url|required_if:nav_links.*.active,1|max:20',
        'nav_links.*.name.en' => 'required_with:nav_links.*.name.ar|required_with:nav_links.*.url|required_if:nav_links.*.active,1|max:20',
        'nav_links.*.url' => 'nullable|string',
        'nav_links.*.active' => 'in:0,1',
    ];

    protected function messages()
    {
        return [
            'nav_links.*.name.ar.required_with' => __('validation.arabic name required'),
            'nav_links.*.name.ar.required_if' => __('validation.arabic name required'),
            'nav_links.*.name.en.required_with' => __('validation.english name required'),
            'nav_links.*.name.en.required_if' => __('validation.english name required'),
            'nav_links.*.url.required_if' => __('validation.url required')
        ];
    }

    public $nav_links = [];

    public function mount()
    {
        $this->nav_links = NavLink::get()->toArray();
    }

    public function render()
    {
        return view('livewire.admin.setting.general.nav-links.nav-links-form');
    }

    public function updated()
    {
        $this->validate();
    }

    public function active($index)
    {
        if (isset($this->nav_links[$index]) && isset($this->nav_links[$index]['active'])) {
            $this->nav_links[$index]['active'] = $this->nav_links[$index]['active'] ? 0 : 1;
        } else {
            $this->nav_links[$index]['active'] = 1;
        }

        $this->validate();
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            NavLink::where('id', '>', 0)->delete();

            foreach ($this->nav_links as $nav_link) {
                if (isset($nav_link['active']) || isset($nav_link['url'])) {
                    NavLink::create($nav_link);
                }
            }

            DB::commit();

            session()->flash('success', __('admin/sitePages.Links have been updated successfully'));
            redirect()->route('admin.setting.general');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();

            session()->flash('error', __("admin/sitePages.Links haven't been updated"));
            redirect()->route('admin.setting.general');
        }
    }
}
