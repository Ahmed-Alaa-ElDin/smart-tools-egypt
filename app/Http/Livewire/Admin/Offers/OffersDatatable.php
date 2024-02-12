<?php

namespace App\Http\Livewire\Admin\Offers;

use App\Models\Offer;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class OffersDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = ['deleteOffer'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'title->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $offers = Offer::where(function ($query) {
            return $query
                ->where('title->ar', 'like', '%' . $this->search . '%')
                ->orWhere('title->en', 'like', '%' . $this->search . '%')
                ->orWhere('value', 'like', '%' . $this->search . '%')
                ->orWhere('start_at', 'like', '%' . $this->search . '%')
                ->orWhere('expire_at', 'like', '%' . $this->search . '%');
        })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.offers.offers-datatable', compact('offers'));
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

        if ($field == 'title') {
            return $this->sortBy = 'title->' . session('locale');
        }

        return $this->sortBy = $field;
    }

    ######## Deleted #########
    public function deleteConfirm($offer_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/offersPages.Are you sure, you want to delete this offer ?'),
            'confirmButtonText' => __('admin/offersPages.Delete'),
            'denyButtonText' => __('admin/offersPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'deleteOffer',
            'id' => $offer_id,
        ]);
    }

    public function deleteOffer($offer_id)
    {
        try {
            $offer = Offer::with('sections')->findOrFail($offer_id);
            $offer->sections()->delete();
            $offer->delete();

            $this->dispatchBrowserEvent('swalOfferDeleted', [
                "text" => __('admin/offersPages.Offer has been deleted successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalOfferDeleted', [
                "text" => __("admin/offersPages.Offer hasn't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Deleted #########
}
