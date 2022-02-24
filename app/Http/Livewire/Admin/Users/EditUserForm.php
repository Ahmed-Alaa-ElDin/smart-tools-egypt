<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

class EditUserForm extends Component
{
    use WithFileUploads;

    public $user_id;
    public $user;
    public $roles;
    public $photo;
    public $temp_path;
    public $image_name;

    public $f_name = ['ar' => '', 'en' => ''], $l_name = ['ar' => '', 'en' => ''], $email, $phone, $gender = '0', $role, $birth_date, $country, $governorate, $city, $details, $special_marque;

    protected $listeners = ['choseCountry', 'choseGovernorate', 'choseCity', 'details', 'specialMarque', 'userNotFound'];

    protected $rules = [
        'f_name.ar' => 'required|string|max:20|min:3',
        'f_name.en' => 'nullable|string|max:20|min:3',
        'l_name.ar' => 'required|string|max:20|min:3',
        'l_name.en' => 'nullable|string|max:20|min:3',
        'email' => 'required_without:phone|email|max:50|min:3|unique:users',
        'phone' => 'required_without:email|digits_between:3,20',
        'gender' => 'in:0,1',
        'role' => 'exists:roles,id',
        'birth_date' => 'date|before:today',
        'photo' => 'nullable|mimes:jpg,jpeg,png|max:2048',
    ];

    // Called Once at the beginning
    public function mount($user_id)
    {
        $this->user_id = $user_id;

        // All Roles
        $this->roles = Role::get();

        // Set User Data
        $this->user = User::findOrFail($this->user_id);

        // First name
        $this->f_name = [
            'ar' => $this->user->getTranslation('f_name', 'ar'),
            'en' => $this->user->getTranslation('f_name', 'en')
        ];

        // Last name
        $this->l_name = [
            'ar' => $this->user->getTranslation('l_name', 'ar') ?? '',
            'en' => $this->user->getTranslation('l_name', 'en') ?? ''
        ];

        // Email
        $this->email = $this->user->email ?? '';

        // Phone
        $this->phone = $this->user->phones->where('default', 1)->first()->phone ?? '';

        // Gender
        $this->gender = $this->user->gender ?? 0;

        // Role
        $this->role = $this->user->roles->first()->id ?? '';

        // Birth date
        $this->birth_date = $this->user->birth_date ?? '';
    }


    public function render()
    {
        return view('livewire.admin.users.edit-user-form');
    }

}
