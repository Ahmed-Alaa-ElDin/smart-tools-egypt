<?php

namespace App\Livewire\Admin\Setting\Homepage\Sections;

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
    public $section;

    public $selected_offer, $selected_products, $selected_banners;

    protected $listeners = ['listUpdated'];

    public function rules()
    {
        return [
            'title.ar'                  =>      'required|string|max:100|min:3',
            'title.en'                  =>      'required|string|max:100|min:3',
            'active'                    =>      'boolean',
            'type'                      =>      'required|in:0,1,2,3',
            'selected_offer'            =>      'exclude_unless:type,1,2|required|exists:offers,id',
            "selected_products"         =>      'exclude_unless:type,0|required|array|min:1',
            "selected_banners"          =>      'exclude_unless:type,3|required|array|min:3|max:3',
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

    ############ Mount :: Start ############
    public function mount()
    {
        if ($this->section_id) {
            $this->section = Section::with([
                'products' =>
                fn ($q) => $q->select([
                    'products.id',
                    'name',
                    'original_price',
                    'base_price',
                    'final_price',
                    'points',
                    'under_reviewing'
                ])->with([
                    'thumbnail'
                ])->withPivot('rank'),
                'collections' =>
                fn ($q) => $q->select(['collections.id', 'name', 'original_price', 'base_price', 'final_price', 'points', 'under_reviewing'])->with(['thumbnail'])->withPivot('rank'),
                'offer',
                'banners' => fn ($q) => $q->select(['banners.id', 'banner_name', 'description', 'link'])->withPivot('rank')
            ])->findOrFail($this->section_id);

            $this->title = [
                'ar' => $this->section->getTranslation('title', 'ar'),
                'en' => $this->section->getTranslation('title', 'en')
            ];

            $this->active = $this->section->active;

            $this->type = $this->section->type;

            if ($this->type == 0) {
                $products = $this->section->products->toArray();
                foreach ($products as &$product) {
                    $product['rank'] = $product['pivot']['rank'];
                    $product['type'] = 'Product';
                }

                $collections = $this->section->collections->toArray();
                foreach ($collections as &$collection) {
                    $collection['rank'] = $collection['pivot']['rank'];
                    $collection['type'] = 'Collection';
                }
                $this->selected_products = array_merge($products, $collections);
            } elseif ($this->type == 1 || $this->type == 2) {
                $this->selected_offer = $this->section->offer ? $this->section->offer->id : '';
            } elseif ($this->type == 3) {
                $this->selected_banners = $this->section->banners->toArray();
                foreach ($this->selected_banners as &$banner) {
                    $banner['rank'] = $banner['pivot']['rank'];
                }
            }
        }
    }
    ############ Mount :: End ############

    ############ Render :: Start ############
    public function render()
    {
        return view('livewire.admin.setting.homepage.sections.section-form');
    }
    ############ Render :: End ############

    ############ Realtime Validation :: Start ############
    public function updated($field)
    {
        $this->validateOnly($field);
    }
    ############ Realtime Validation :: End ############

    ############ Reset variables on type change :: Start ############
    public function updatedType()
    {
        $this->selected_products = [];
        $this->selected_offer = null;
        $this->selected_banners = [];

        $this->validateOnly('selected_products');
        $this->validateOnly('selected_offer');
        $this->validateOnly('selected_banners');
    }
    ############ Reset variables on type change :: End ############

    ############ Get Data from Subcomponents :: Start ############
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
    ############ Get Data from Subcomponents :: End ############

    ############ Save :: Start ############
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
                "offer_id" => $this->selected_offer ?? null,
            ]);

            if ($this->selected_products != null) {
                foreach ($this->selected_products as $item) {
                    if ($item['type'] == 'Product') {
                        $section->products()->attach($item['id'], ['rank' => $item['rank']]);
                    } elseif ($item['type'] == 'Collection') {
                        $section->collections()->attach($item['id'], ['rank' => $item['rank']]);
                    }
                }
            } elseif ($this->selected_banners != null) {
                foreach ($this->selected_banners as $banner) {
                    $section->banners()->attach($banner['id'], ['rank' => $banner['rank']]);
                }
            }

            DB::commit();

            if ($new) {
                Session::flash('success', __('admin/sitePages.Section added successfully'));
                redirect()->route('admin.setting.homepage.create');
            } else {
                Session::flash('success', __('admin/sitePages.Section added successfully'));
                redirect()->route('admin.setting.homepage');
            }
        } catch (\Throwable $th) {
            DB::rollback();

            Session::flash('error', __("admin/sitePages.Section hasn't been added"));
            redirect()->route('admin.setting.homepage');
        }
    }
    ############ Save :: End ############

    ############ Update :: Start ############
    public function update()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $this->section->update([
                "title" => [
                    'ar' => $this->title['ar'],
                    'en' => $this->title['en']
                ],
                "type" => $this->type,
                "active" => $this->active ? 1 : 0,
                "offer_id" => $this->selected_offer ?? null,
            ]);

            $this->section->products()->detach();
            $this->section->collections()->detach();
            $this->section->banners()->detach();

            if ($this->selected_products != null) {
                foreach ($this->selected_products as $item) {
                    if ($item['type'] == 'Product') {
                        $this->section->products()->attach($item['id'], ['rank' => $item['rank']]);
                    } elseif ($item['type'] == 'Collection') {
                        $this->section->collections()->attach($item['id'], ['rank' => $item['rank']]);
                    }
                }
            } elseif ($this->selected_banners != null) {
                foreach ($this->selected_banners as $banner) {
                    $this->section->banners()->attach($banner['id'], ['rank' => $banner['rank']]);
                }
            }

            DB::commit();

            Session::flash('success', __('admin/sitePages.Section updated successfully'));
            redirect()->route('admin.setting.homepage');
        } catch (\Throwable $th) {
            DB::rollback();

            Session::flash('error', __("admin/sitePages.Section hasn't been updated"));
            redirect()->route('admin.setting.homepage');
        }
    }
    ############ Update :: End ############

}
