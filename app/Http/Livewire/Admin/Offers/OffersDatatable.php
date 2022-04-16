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

    protected $listeners = ['softDeleteOffer'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'title->'.session('locale');
    }

    // Render With each update
    public function render()
    {
        $offers = Offer::where(function ($query) {
            return $query
                ->where('title->ar', 'like', '%' . $this->search . '%')
                ->orWhere('title->en', 'like', '%' . $this->search . '%')
                ->orWhere('value', 'like', '%' . $this->search . '%')
                ->orWhere('number', 'like', '%' . $this->search . '%')
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

        return $this->sortBy = $field;
    }

    ######## Deleted #########
    public function deleteConfirm($offer_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/offersPages.Are you sure, you want to delete this offer ?'),
            'confirmButtonText' => __('admin/offersPages.Delete'),
            'denyButtonText' => __('admin/offersPages.Cancel'),
            'confirmButtonColor' => 'red',
            'func' => 'softDeleteOffer',
            'offer_id' => $offer_id,
        ]);
    }

    public function softDeleteOffer($offer_id)
    {
        try {
            $user = Offer::findOrFail($offer_id);
            $user->delete();

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
