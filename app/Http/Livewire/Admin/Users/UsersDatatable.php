<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Phone;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UsersDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = ['softDeleteUser', 'editRoles', 'addPoints'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'f_name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $users = User::with('phones', 'roles')
            ->whereHas("roles", function ($q) {
                $q->where("id", "!=", 1);
            })
            ->where(
                fn ($q) => $q
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
                    })
            )
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.users.users-datatable', compact('users'));
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

    ######## Deleted #########
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
    ######## Deleted #########


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

    ######## Add Points #########
    public function addPointsForm($user_id)
    {
        $this->dispatchBrowserEvent('swalAddPointsForm', [
            'title' => __('admin/usersPages.Enter the points you want to add'),
            'confirmButtonText' => __('admin/usersPages.Add'),
            'denyButtonText' => __('admin/usersPages.Cancel'),
            'user_id' => $user_id,
        ]);
    }

    public function addPoints($user_id, $points)
    {
        try {
            $user = User::findOrFail($user_id);

            $user->points = $user->points + $points;

            $user->save();

            $this->dispatchBrowserEvent('swalUserRoleChanged', [
                "text" => __('admin/usersPages.Points added successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalUserRoleChanged', [
                "text" => __("admin/usersPages.Points haven't been added"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Add Points #########

}
