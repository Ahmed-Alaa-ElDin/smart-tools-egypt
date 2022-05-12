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

    public function rules()
    {
        return [
            'title.ar'           =>        'required|string|max:100|min:3',
            'title.en'           =>        'required|string|max:100|min:3',
            'active'             =>        'boolean',
            'type'               =>        'required|in:0,1,2,3',
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

            if ($this->type == 0) {
                // Type --> Product List
                $this->emitTo('admin.homepage.sections.products-list-form', 'sectionSaved', ['section_id' => $section->id]);
            } elseif ($this->type == 1) {
                // Type --> Offer

            } elseif ($this->type == 2) {
                // Type --> Flash Sale

            } elseif ($this->type == 3) {
                // Type --> Banners

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
            DB::rollBack();

            // throw $th;

            Session::flash('error', __("admin/sitePages.Section hasn't been added"));
            redirect()->route('admin.homepage');
        }
    }
}
