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
    public $collectionsIds = [];
    public $selectAllCollections = false;

    protected $listeners = [
        'unselectAll',
    ];

    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');
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

            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage, ['*'], 'CollectionsPage');

            $this->collectionsIds = $collections->pluck('id')->toArray();

            $this->checkAllCollectionsSelected();
        
        return view('livewire.admin.collections.collection-list-datatable', compact('collections'));
    }

    // reset pagination after new search
    public function updatingSearch()
    {
        $this->resetPage('CollectionsPage');
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

    public function updatedSelectAllCollections($value)
    {
        if ($value) {
            $this->selectedCollections = array_merge($this->selectedCollections, $this->collectionsIds);
        } else {
            $this->selectedCollections = array_diff($this->selectedCollections, $this->collectionsIds);
        }

        $this->emit('selectedCollectionsUpdated', $this->selectedCollections);
    }

    private function checkAllCollectionsSelected()
    {
        $this->selectAllCollections = count(array_diff($this->collectionsIds, $this->selectedCollections)) == 0 && count($this->collectionsIds) > 0 ? true : false;
    }

}
