<?php

namespace App\Http\Livewire\Admin\Roles;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddRoleForm extends Component
{
    public $name, $allPermissions, $selectedPermissions = [];

    // validation rules
    public function rules()
    {
        return [
            'name' => 'required|string|max:50|unique:roles,name',
            'selectedPermissions' => 'array|nullable',
            'selectedPermissions.*' => 'exists:permissions,name',
        ];
    }

    // Called Once at the beginning
    public function mount()
    {
        // get all permissions
        $this->allPermissions = Permission::get();
    }

    // Called with every update
    public function render()
    {
        return view('livewire.admin.roles.add-role-form');
    }

    // Real Time Validation
    public function updated($field)
    {
        $this->validateOnly($field);
    }

    // Select All Permissions
    public function selectAll()
    {
        $this->selectedPermissions = Permission::get()->pluck('name')->toArray();
    }

    // Deselect All Permissions
    public function deselectAll()
    {
        $this->selectedPermissions = [];
    }

    // Final Validate and add to database
    public function save($new = false)
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // create new role
            $role = Role::create([
                'name' => $this->name
            ]);

            // assign selected permissions to new role
            $role->givePermissionTo($this->selectedPermissions);

            // Save and End Transaction
            DB::commit();

            // redirect with success message
            if ($new) {
                Session::flash('success', __('admin/usersPages.Role added successfully'));
                redirect()->route('admin.roles.create');
            } else {
                Session::flash('success', __('admin/usersPages.Role added successfully'));
                redirect()->route('admin.roles.index');
            }
        } catch (\Throwable $th) {
            DB::rollback();

            Session::flash('error', __("admin/usersPages.Role hasn't been added"));
            redirect()->route('admin.roles.index');
        }
    }
}
