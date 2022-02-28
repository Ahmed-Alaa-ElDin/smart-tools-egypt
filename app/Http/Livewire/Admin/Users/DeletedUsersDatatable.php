<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class DeletedUsersDatatable extends Component
{
    use WithPagination;

    public $sortBy = 'f_name';
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = ['forceDeleteUser', 'restoreUser','forceDeleteAllUsers','restoreAllUsers'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');
    }

    // Render With each update
    public function render()
    {
        $users = User::onlyTrashed()->with('phones')->with('roles')
            ->where(function ($query) {
                $query
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
                    });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.users.deleted-users-datatable', compact('users'));
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

        return $this->sortBy = $field;
    }

    ######## Force Delete #########
    public function forceDeleteConfirm($user_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/usersPages.Are you sure, you want to delete this user permanently ?'),
            'confirmButtonText' => __('admin/usersPages.Delete'),
            'denyButtonText' => __('admin/usersPages.Cancel'),
            'denyButtonColor' => 'gray',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'forceDeleteUser',
            'user_id' => $user_id,
        ]);
    }

    public function forceDeleteUser($user_id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($user_id);

            $user->forceDelete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/usersPages.User has been deleted successfully'),
                'icon' => 'info'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/usersPages.User hasn\'t been deleted'),
                'icon' => 'error'
            ]);
        }
    }
    ######## Force Delete #########

    ######## Restore #########
    public function restoreConfirm($user_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/usersPages.Are you sure, you want to restore this user ?'),
            'confirmButtonText' => __('admin/usersPages.Confirm'),
            'denyButtonText' => __('admin/usersPages.Cancel'),
            'denyButtonColor' => 'gray',
            'confirmButtonColor' => 'green',
            'focusDeny' => false,
            'icon' => 'warning',
            'method' => 'restoreUser',
            'user_id' => $user_id,
        ]);
    }

    public function restoreUser($user_id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($user_id);

            $user->restore();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/usersPages.User has been restored successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/usersPages.User hasn\'t been restored'),
                'icon' => 'error'
            ]);
        }
    }
    ######## Restore #########


    ######## Force Delete All #########
    public function forceDeleteAllConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/usersPages.Are you sure, you want to delete all users permanently ?'),
            'confirmButtonText' => __('admin/usersPages.Delete'),
            'denyButtonText' => __('admin/usersPages.Cancel'),
            'denyButtonColor' => 'gray',
            'confirmButtonColor' => 'red',
            'focusDeny' => false,
            'icon' => 'warning',
            'method' => 'forceDeleteAllUsers',
            'user_id' => ''
        ]);
    }

    public function forceDeleteAllUsers($user_id)
    {
        try {
            User::onlyTrashed()->forceDelete();

            $this->dispatchBrowserEvent('swalDone', [
                // todo
                "text" => __('admin/usersPages.All users have been deleted successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/usersPages.All users haven\'t been deleted'),
                'icon' => 'error'
            ]);
        }
    }
    ######## Force Delete All #########

    ######## Restore All #########

    public function restoreAllConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/usersPages.Are you sure, you want to restore all users ?'),
            'confirmButtonText' => __('admin/usersPages.Confirm'),
            'denyButtonText' => __('admin/usersPages.Cancel'),
            'denyButtonColor' => 'gray',
            'confirmButtonColor' => 'green',
            'focusDeny' => false,
            'icon' => 'warning',
            'method' => 'restoreAllUsers',
            'user_id' => '',
        ]);
    }

    public function restoreAllUsers($user_id)
    {
        try {
            $user = User::onlyTrashed()->restore();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/usersPages.All users have been restored successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/usersPages.All users haven\'t been restored'),
                'icon' => 'error'
            ]);
        }
    }
    ######## Restore All #########
}
