<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class UsersDatatable extends Component
{
    use WithPagination;

    public $sortBy = 'f_name';
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.pagination');
    }

    // Render With each update
    public function render()
    {
        $users = User::with('roles')
        ->where('f_name->en', 'like', '%' . $this->search . '%')
        ->orWhere('f_name->ar','like', '%' . $this->search . '%')
        ->orWhere('l_name->en','like', '%' . $this->search . '%')
        ->orWhere('l_name->ar','like', '%' . $this->search . '%')
        ->orWhere('email','like', '%' . $this->search . '%')
        ->orWhere('phone','like', '%' . $this->search . '%')
        ->orWhereHas('roles', function ($query) {
            $query->where('name', 'like', '%'.$this->search.'%');
        })
        ->orderBy($this->sortBy, $this->sortDirection)
        ->paginate($this->perPage);

        return view('livewire.admin.users.users-datatable', compact('users'));
    }

    // reset pagination after new search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Add coditions of sorting
    public function sortBy($field)
    {
        if ($this->sortDirection == 'ASC') {
            $this->sortDirection = 'DESC';
        } else {
            $this->sortDirection = 'ASC';
        }

        // $this->reset(['selectedUsers', 'selectedAllUsers']);

        return $this->sortBy = $field;
    }
}
