<?php

namespace App\Livewire\Admin\Roles;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class RolesUsersList extends Component
{
    use WithPagination;

    public $role_id;

    public $sortBy = 'f_name';
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = ['softDeleteUser', 'editRoles'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');
    }

    // Render With each update
    public function render()
    {
        $users = User::with('phones','roles')
            ->whereHas('roles', function ($query) {
                $query->where('id', $this->role_id);
            })
            ->where(function ($query) {
                $query
                    ->where('f_name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('f_name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('l_name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('l_name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereHas('phones', function ($query) {
                        $query->where('phone', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.roles.roles-users-list', compact('users'));
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
    public function deleteConfirm($user_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/usersPages.Are you sure, you want to delete this user ?'),
            confirmButtonText: __('admin/usersPages.Delete'),
            denyButtonText: __('admin/usersPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'softDeleteUser',
            id: $user_id);
    }

    public function softDeleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            $this->dispatch('swalDone', text: __('admin/usersPages.User has been deleted successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/usersPages.User hasn't been deleted"),
                icon: 'error');
        }
    }
    ######## Deleted #########


    ######## Edit User Roles #########
    public function editRolesSelect($user_id)
    {
        $this->dispatch('swalEditRolesSelect', title: __('admin/usersPages.Select User Role'),
            confirmButtonText: __('admin/usersPages.Update'),
            denyButtonText: __('admin/usersPages.Cancel'),
            data: json_encode(Role::get()->pluck('name', 'name')),
            selected: User::findOrFail($user_id)->roles->first()->name ?? 'Customer',
            user_id: $user_id);
    }

    public function editRoles($user_id, $role_name)
    {
        try {
            User::findOrFail($user_id)->syncRoles($role_name);

            $this->dispatch('swalUserRoleChanged', text: __('admin/usersPages.New role assigned successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalUserRoleChanged', text: __('admin/usersPages.New role hasn\'n been assigned'),
                icon: 'error');
        }
    }
    ######## Edit User Roles #########
}
