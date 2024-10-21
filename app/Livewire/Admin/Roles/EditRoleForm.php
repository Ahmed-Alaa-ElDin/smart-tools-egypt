<?php

namespace App\Livewire\Admin\Roles;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EditRoleForm extends Component
{
    public $role_id, $role, $name, $allPermissions, $selectedPermissions = [];

    // validation rules
    public function rules()
    {
        return [
            'name' => 'required|string|max:50|unique:roles,name,' . $this->role_id,
            'selectedPermissions' => 'array|nullable',
            'selectedPermissions.*' => 'exists:permissions,name',
        ];
    }

    // Called Once at the beginning
    public function mount()
    {
        // get all permissions
        $this->allPermissions = Permission::get();

        // get selected Permissions
        $this->role = Role::findOrFail($this->role_id);

        // get form data
        $this->name = $this->role->name;
        $this->selectedPermissions = $this->role->permissions->pluck('name')->toArray();
    }

    // Called with every update
    public function render()
    {
        return view('livewire.admin.roles.edit-role-form');
    }

    // Real Time Validation
    public function updated($field)
    {
        $this->validateOnly($field);
    }

    // Final Validate and add to database
    public function save($new = false)
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // create new role
            $this->role->update([
                'name' => $this->name
            ]);

            // assign selected permissions to new role
            $this->role->syncPermissions($this->selectedPermissions);

            // Save and End Transaction
            DB::commit();

            // redirect with success message
            Session::flash('success', __('admin/usersPages.Role edited successfully'));
            redirect()->route('admin.roles.index');
        } catch (\Throwable $th) {
            DB::rollback();

            Session::flash('error', __("admin/usersPages.Role has not been edited"));
            redirect()->route('admin.roles.index');
        }
    }
}
