<?php

namespace App\Livewire\Admin\Customers;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Config;

class CustomersDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $city_id = null;
    public $governorate_id = null;
    public $country_id = null;

    public $search = "";

    protected $listeners = ['softDeleteUser', 'addPoints', 'editRoles'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'f_name->' . session('locale');
    }

    public function render()
    {
        $users = User::with([
            'phones',
            'roles',
            'cities',
            'countries',
            'points'
        ])
            ->where(
                fn($q) => $q
                    ->whereDoesntHave('roles')
                    ->orwhereHas("roles", function ($q) {
                        $q->where("id", 1);
                    })
            )
            ->where(
                fn($q) => $q
                    ->where('f_name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('f_name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('l_name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('l_name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereHas('phones', function ($query) {
                        $query->where('phone', 'like', '%' . $this->search . '%');
                    })
            )
            ->where(function ($q) {
                if ($this->city_id) {
                    return $q->whereHas('cities', fn($q) => $q->where('cities.id', $this->city_id));
                } elseif ($this->governorate_id) {
                    return $q->whereHas('governorates', fn($q) => $q->where('governorates.id', $this->governorate_id));
                } elseif ($this->country_id) {
                    return $q->whereHas('countries', fn($q) => $q->where('countries.id', $this->country_id));
                }
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.customers.customers-datatable', compact('users'));
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
        if ($field == 'f_name') {
            return $this->sortBy = 'f_name->' . session('locale');
        }
        return $this->sortBy = $field;
    }

    ######## Edit User Roles #########
    public function editRolesSelect($user_id)
    {
        $this->dispatch(
            'swalEditRolesSelect',
            title: __('admin/usersPages.Select User Role'),
            confirmButtonText: __('admin/usersPages.Update'),
            denyButtonText: __('admin/usersPages.Cancel'),
            data: json_encode(Role::get()->pluck('name', 'name')),
            selected: User::findOrFail($user_id)->roles->first()->name ?? 'Customer',
            user_id: $user_id
        );
    }

    public function editRoles($user_id, $role_name)
    {
        try {
            User::findOrFail($user_id)->syncRoles($role_name);

            $this->dispatch(
                'swalUserRoleChanged',
                text: __('admin/usersPages.New role assigned successfully'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalUserRoleChanged',
                text: __('admin/usersPages.New role hasn\'n been assigned'),
                icon: 'error'
            );
        }
    }
    ######## Edit User Roles #########

    ######## Deleted #########
    public function deleteConfirm($user_id)
    {
        $this->dispatch(
            'swalConfirm',
            text: __('admin/usersPages.Are you sure, you want to delete this customer ?'),
            confirmButtonText: __('admin/usersPages.Delete'),
            denyButtonText: __('admin/usersPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'softDeleteUser',
            id: $user_id
        );
    }

    public function softDeleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            $this->dispatch(
                'swalDone',
                text: __('admin/usersPages.Customer has been deleted successfully'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/usersPages.Customer has not been deleted"),
                icon: 'error'
            );
        }
    }
    ######## Deleted #########



    ######## Add Points #########
    public function addPointsForm($user_id)
    {
        $this->dispatch(
            'swalAddPointsForm',
            title: __('admin/usersPages.Enter the points you want to add'),
            confirmButtonText: __('admin/usersPages.Add'),
            denyButtonText: __('admin/usersPages.Cancel'),
            user_id: $user_id
        );
    }

    public function addPoints($user_id, $points)
    {
        try {
            $user = User::findOrFail($user_id);

            $user->points = $user->points + $points;

            $user->save();

            $this->dispatch(
                'swalDone',
                text: __('admin/usersPages.Points added successfully'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalUserRoleChanged',
                text: __("admin/usersPages.Points haven't been added"),
                icon: 'error'
            );
        }
    }
    ######## Add Points #########

    ######## Banning User : Start ########
    public function banning($user_id)
    {
        try {
            $user = User::findOrFail($user_id);

            $user->banned = $user->banned ? 0 : 1;

            $user->save();

            if ($user->banned) {
                $this->dispatch(
                    'swalDone',
                    text: __('admin/usersPages.Customer has been banned successfully'),
                    icon: 'success'
                );
            } else {
                $this->dispatch(
                    'swalDone',
                    text: __('admin/usersPages.Customer has been unbanned successfully'),
                    icon: 'success'
                );
            }
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/usersPages.Customer has not been banned"),
                icon: 'error'
            );
        }
    }
    ######## Banning User : End ########
}
