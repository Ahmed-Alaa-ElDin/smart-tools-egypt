<?php

namespace App\Http\Livewire\Admin\Homepage\Sections;

use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class SectionForm extends Component
{
    public $section_id, $type = 0, $active = 1, $title = [
        'ar' => '',
        'en' => ''
    ];

    public $selected_offer, $selected_products, $selected_banners;

    protected $listeners = ['listUpdated'];

    public function rules()
    {
        return [
            'title.ar'                  =>      'required|string|max:100|min:3',
            'title.en'                  =>      'required|string|max:100|min:3',
            'active'                    =>      'boolean',
            'type'                      =>      'required|in:0,1,2,3',
            'selected_offer'            =>      'required_if:type,1,2|nullable|exists:offers,id',
            "selected_products"         =>      'required_if:type,0|nullable|array|min:1',
            "selected_products.*.id"    =>      'exists:products,id',
            "selected_banners"          =>      'required_if:type,3|nullable|array|min:3|max:3',
            "selected_banners.*.id"     =>      'exists:banners,id',
        ];
    }

    public function messages()
    {
        return [
            'selected_offer.required_if' => __('validation.The offer is required.'),
            'selected_products.required_if' => __('validation.Products are required.'),
            'selected_banners.required_if' => __('validation.Banners are required.'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.homepage.sections.section-form');
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function updatedType()
    {
        $this->selected_products = null;
        $this->selected_offer = null;
        $this->selected_banners = null;

        $this->validateOnly('selected_products');
        $this->validateOnly('selected_offer');
        $this->validateOnly('selected_banners');
    }

    public function listUpdated($request)
    {
        if (array_key_exists('selected_offer', $request)) {
            $this->selected_offer = $request['selected_offer'];
            $this->validateOnly('selected_offer');
        } elseif (array_key_exists('selected_products', $request)) {
            $this->selected_products = $request['selected_products'];
            $this->validateOnly('selected_products');
        } elseif (array_key_exists('selected_banners', $request)) {
            $this->selected_banners = $request['selected_banners'];
            $this->validateOnly('selected_banners');
        };
    }

    public function save($new = false)
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $section = Section::create([
                "title" => [
                    'ar' => $this->title['ar'],
                    'en' => $this->title['en']
                ],
                "type" => $this->type,
                "active" => $this->active ? 1 : 0,
            ]);

            if ($this->selected_offer != null) {
                $section->offers()->attach($this->selected_offer);
            } elseif ($this->selected_products != null) {
                foreach ($this->selected_products as $product) {
                    $section->products()->attach($product['id'], ['rank' => $product['rank']]);
                }
            } elseif ($this->selected_banners != null) {
                foreach ($this->selected_banners as $banner) {
                    $section->banners()->attach($banner['id'], ['rank' => $banner['rank']]);
                }
            }

            DB::commit();

            if ($new) {
                Session::flash('success', __('admin/sitePages.Section added successfully'));
                redirect()->route('admin.homepage.create');
            } else {
                Session::flash('success', __('admin/sitePages.Section added successfully'));
                redirect()->route('admin.homepage');
            }
        } catch (\Throwable $th) {
            DB::rollback();

            Session::flash('error', __("admin/sitePages.Section hasn't been added"));
            redirect()->route('admin.homepage');
        }
    }
}
