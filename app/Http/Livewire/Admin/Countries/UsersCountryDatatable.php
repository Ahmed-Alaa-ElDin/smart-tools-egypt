<?php

namespace App\Http\Livewire\Admin\Countries;

use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UsersCountryDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;
    public $country_id;

    public $search = "";

    protected $listeners = ['softDeleteUser', 'editRoles'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'f_name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $users  = Country::withTrashed()->with('users')->findOrFail($this->country_id)
            ->users()->with('phones','roles')
            ->where(function ($query) {
                return $query
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
            ->distinct()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.countries.users-country-datatable', compact('users'));
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

    ######## Soft Delete #########
    public function deleteConfirm($user_id)
    {
        $this->dispatchBrowserEvent('swalConfirmSoftDelete', [
            "text" => __('admin/usersPages.Are you sure, you want to delete this user ?'),
            'confirmButtonText' => __('admin/usersPages.Delete'),
            'denyButtonText' => __('admin/usersPages.Cancel'),
            'user_id' => $user_id,
        ]);
    }

    public function softDeleteUser($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $user->delete();

            $this->dispatchBrowserEvent('swalUserDeleted', [
                "text" => __('admin/usersPages.User has been deleted successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalUserDeleted', [
                "text" => __("admin/usersPages.User hasn't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Soft Delete #########


    ######## Edit User Roles #########
    public function editRolesSelect($user_id)
    {
        $this->dispatchBrowserEvent('swalEditRolesSelect', [
            'title' => __('admin/usersPages.Select User Role'),
            'confirmButtonText' => __('admin/usersPages.Update'),
            'denyButtonText' => __('admin/usersPages.Cancel'),
            'data' => json_encode(Role::get()->pluck('name', 'name')),
            'selected' => User::findOrFail($user_id)->roles->first()->name ?? 'Customer',
            'user_id' => $user_id,
        ]);
    }

    public function editRoles($user_id, $role_name)
    {
        try {
            User::findOrFail($user_id)->syncRoles($role_name);

            $this->dispatchBrowserEvent('swalUserRoleChanged', [
                "text" => __('admin/usersPages.New role assigned successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalUserRoleChanged', [
                "text" => __('admin/usersPages.New role hasn\'n been assigned'),
                'icon' => 'error'
            ]);
        }
    }
    ######## Edit User Roles #########
}
