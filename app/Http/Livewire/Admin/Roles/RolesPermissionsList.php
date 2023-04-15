<?php

namespace App\Http\Livewire\Admin\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsList extends Component
{
    public $role_id;

    public function render()
    {
        $role = Role::findOrFail($this->role_id);
        $rolesPermissions = $role->permissions()->pluck('id')->toArray();
        $allPermissions = Permission::get();

        return view('livewire.admin.roles.roles-permissions-list' , compact('role','rolesPermissions','allPermissions'));
    }
}
