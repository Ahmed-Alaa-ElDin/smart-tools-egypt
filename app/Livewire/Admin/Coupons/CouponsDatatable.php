<?php

namespace App\Livewire\Admin\Coupons;

use App\Models\Coupon;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class CouponsDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = ['softDeleteCoupon'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'code';
    }

    // Render With each update
    public function render()
    {
        $coupons = Coupon::where(function ($query) {
            return $query
                ->where('code', 'like', '%' . $this->search . '%')
                ->orWhere('value', 'like', '%' . $this->search . '%')
                ->orWhere('expire_at', 'like', '%' . $this->search . '%')
                ->orWhere('number', 'like', '%' . $this->search . '%');
        })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.coupons.coupons-datatable', compact('coupons'));
    }

    // reset pagination after new search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Add conditions of sorting
    public function setSortBy($field)
    {
        if ($this->sortDirection == 'ASC') {
            $this->sortDirection = 'DESC';
        } else {
            $this->sortDirection = 'ASC';
        }

        return $this->sortBy = $field;
    }

    ######## Deleted #########
    public function deleteConfirm($coupon_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/offersPages.Are you sure, you want to delete this coupon ?'),
            confirmButtonText: __('admin/offersPages.Delete'),
            denyButtonText: __('admin/offersPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'deleteCoupon',
            id: $coupon_id);
    }

    public function deleteCoupon($id)
    {
        try {
            $user = Coupon::findOrFail($id);
            $user->delete();

            $this->dispatch('swalDone', text: __('admin/offersPages.Coupon has been deleted successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/offersPages.Coupon has not been deleted"),
                icon: 'error');
        }
    }
    ######## Deleted #########

}
