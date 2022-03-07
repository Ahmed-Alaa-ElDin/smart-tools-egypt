<?php

namespace App\Http\Livewire\Admin\Roles;

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
        $this->perPage = Config::get('constants.constants.PAGINATION');
    }

    // Render With each update
    public function render()
    {
        $roles = Role::with('users')->with('permissions')
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
    public function sortBy($field)
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
        $this->dispatchBrowserEvent('swalConfirmDelete', [
            "text" => __('admin/usersPages.Are you sure, you want to delete this role ?'),
            'confirmButtonText' => __('admin/usersPages.Delete'),
            'denyButtonText' => __('admin/usersPages.Cancel'),
            'role_id' => $role_id,
        ]);
    }

    public function deleteRole($role_id)
    {
        try {
            $role = Role::findOrFail($role_id);
            $role->delete();

            $this->dispatchBrowserEvent('swalRoleDeleted', [
                "text" => __('admin/usersPages.Role has been deleted successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalRoleDeleted', [
                "text" => __("admin/usersPages.Role hasn't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Delete #########


}
