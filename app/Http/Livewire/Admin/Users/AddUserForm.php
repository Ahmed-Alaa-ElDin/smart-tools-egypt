<?php

namespace App\Http\Livewire\Admin\Users;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class AddUserForm extends Component
{
    public $roles;

    public function mount()
    {
        $this->roles = Role::get();
    }

    public function render()
    {
        return view('livewire.admin.users.add-user-form');
    }
}
