<?php

namespace App\Livewire\Admin\Customers;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class DeletedCustomersDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = ['forceDeleteUser', 'restoreUser', 'forceDeleteAllUsers', 'restoreAllUsers'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'f_name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $users = User::onlyTrashed()->with('phones', 'roles')
            ->where(fn ($q) =>
            $q->whereHas("roles", function ($q) {
                $q->where("id", "=", 1);
            })->orWhereDoesntHave("roles"))

            ->where(function ($query) {
                $query
                    ->where('f_name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('f_name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('l_name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('l_name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereHas('phones', function ($query) {
                        $query->where('phone', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('roles', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.customers.deleted-customers-datatable', compact('users'));
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

        if ($field == 'f_name') {
            return $this->sortBy = 'f_name->' . session('locale');
        }
        return $this->sortBy = $field;
    }

    ######## Force Delete #########
    public function forceDeleteConfirm($user_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/usersPages.Are you sure, you want to delete this customer permanently ?'),
            confirmButtonText: __('admin/usersPages.Delete'),
            denyButtonText: __('admin/usersPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'forceDeleteUser',
            id: $user_id);
    }

    public function forceDeleteUser($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);

            $user->forceDelete();

            $this->dispatch('swalDone', text: __('admin/usersPages.Customer has been deleted successfully'),
                icon: 'info');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/usersPages.Customer has not been deleted"),
                icon: 'error');
        }
    }
    ######## Force Delete #########

    ######## Restore #########
    public function restoreConfirm($user_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/usersPages.Are you sure, you want to restore this customer ?'),
            confirmButtonText: __('admin/usersPages.Confirm'),
            denyButtonText: __('admin/usersPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: false,
            icon: 'warning',
            method: 'restoreUser',
            id: $user_id);
    }

    public function restoreUser($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);

            $user->restore();

            $this->dispatch('swalDone', text: __('admin/usersPages.Customer has been restored successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/usersPages.Customer has not been restored"),
                icon: 'error');
        }
    }
    ######## Restore #########


    ######## Force Delete All #########
    public function forceDeleteAllConfirm()
    {
        $this->dispatch('swalConfirm', text: __('admin/usersPages.Are you sure, you want to delete all customers permanently ?'),
            confirmButtonText: __('admin/usersPages.Delete'),
            denyButtonText: __('admin/usersPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'forceDeleteAllUsers',
            id: '');
    }

    public function forceDeleteAllUsers($user_id)
    {
        try {
            User::with('roles')
                ->whereHas('roles', fn ($q) => $q->where('id', 1))
                ->orWhereDoesntHave('roles')
                ->onlyTrashed()
                ->forceDelete();

            $this->dispatch('swalDone', text: __('admin/usersPages.All customers have been deleted successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __('admin/usersPages.All customers haven\'t been deleted'),
                icon: 'error');
        }
    }
    ######## Force Delete All #########

    ######## Restore All #########

    public function restoreAllConfirm()
    {
        $this->dispatch('swalConfirm', text: __('admin/usersPages.Are you sure, you want to restore all customers ?'),
            confirmButtonText: __('admin/usersPages.Confirm'),
            denyButtonText: __('admin/usersPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: false,
            icon: 'warning',
            method: 'restoreAllUsers',
            id: '');
    }

    public function restoreAllUsers()
    {
        try {
            User::with('roles')
                ->whereHas('roles', fn ($q) => $q->where('id', 1))
                ->orWhereDoesntHave('roles')
                ->onlyTrashed()
                ->restore();

            $this->dispatch('swalDone', text: __('admin/usersPages.All customers have been restored successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __('admin/usersPages.All customers haven\'t been restored'),
                icon: 'error');
        }
    }
    ######## Restore All #########
}
