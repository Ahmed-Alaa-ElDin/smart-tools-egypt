<?php

namespace App\Livewire\Admin\Roles;

use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class RolesDatatable extends Component
{
    use WithPagination;

    public $sortBy = 'name';
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = ['deleteRole'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');
    }

    // Render With each update
    public function render()
    {
        $roles = Role::with('users','permissions')
            ->where('name', 'like', '%' . $this->search . '%')
            ->withCount('users')
            ->withCount('permissions')
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.roles.roles-datatable', compact('roles'));
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

    ######## Delete #########
    public function deleteConfirm($role_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/usersPages.Are you sure, you want to delete this role ?'),
            confirmButtonText: __('admin/usersPages.Delete'),
            denyButtonText: __('admin/usersPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'deleteRole',
            id: $role_id);
    }

    public function deleteRole($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            $this->dispatch('swalDone', text: __('admin/usersPages.Role has been deleted successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/usersPages.Role has not been deleted"),
                icon: 'error');
        }
    }
    ######## Delete #########


}
