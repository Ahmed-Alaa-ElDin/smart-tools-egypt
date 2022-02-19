<?php

namespace App\Http\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

class AddUserForm extends Component
{
    use WithFileUploads;

    public $roles;
    public $photo;
    public $path;

    public function mount()
    {
        $this->roles = Role::get();
    }

    public function render()
    {
        return view('livewire.admin.users.add-user-form');
    }

    public function updatedPhoto()
    {
        $validation = $this->validate([
            'photo' => 'image|max:1024', // 1MB Max
        ]);

        if ($validation) {
            $file_name = 'avatar-' . rand() ;

            $this->photo_path = $this->photo->storeAs('photos', $file_name);

            $this->path = $this->photo->temporaryUrl();
        }

    }
}
