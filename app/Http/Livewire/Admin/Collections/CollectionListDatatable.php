<?php

namespace App\Http\Livewire\Admin\Collections;

use Livewire\Component;
use App\Models\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Config;

class CollectionListDatatable extends Component
{
    use WithPagination;

    public $search = "";
    public $perPage;
    public $sortBy;
    public $sortDirection;
    public $selectedCollections = [];
    public $subcategory_id = "%";
    public $brand_id = "%";
    public $excludedCollections = [];

    protected $listeners = [
        'unselectAll',
    ];

    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');
        $this->sortBy = 'collections.name->' . session('locale');
        $this->sortDirection = 'ASC';
    }

    public function render()
    {
        $collections = Collection::select([
            'collections.id',
            'collections.name',
            'slug',
            'original_price',
            'base_price',
            'final_price',
            'points',
            'publish',
            'under_reviewing',
        ])
            ->withCount('products')
            ->with([
                'thumbnail',
                'products' => fn ($q) => $q->select('products.id')
            ])
            ->where(function ($q) {
                $q
                    ->where('collections.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('collections.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('collections.base_price', 'like', '%' . $this->search . '%')
                    ->orWhere('collections.final_price', 'like', '%' . $this->search . '%');
            })
            ->where('publish', 1)
            ->whereNotIn('collections.id', $this->excludedCollections)

            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage, ['*'], 'CollectionsPage');

        return view('livewire.admin.collections.collection-list-datatable', compact('collections'));
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
        if ($field == 'name') {
            return $this->sortBy = 'name->' . session('locale');
        }
        return $this->sortBy = $field;
    }

    public function updatedSelectedCollections()
    {
        $this->emit('selectedCollectionsUpdated', $this->selectedCollections);
    }

    public function unselectAll()
    {
        $this->selectedCollections = [];
    }
}
