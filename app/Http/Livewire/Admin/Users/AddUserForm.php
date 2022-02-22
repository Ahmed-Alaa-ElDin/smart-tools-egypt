<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Livewire\Component;
use Livewire\WithFileUploads;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;
use Throwable;

class AddUserForm extends Component
{
    use WithFileUploads;

    public $roles;
    public $photo;
    public $temp_path;
    public $image_name;

    public $f_name = ['ar' => '', 'en' => ''], $l_name = ['ar' => '', 'en' => ''], $email, $phone, $gender = '0', $role, $birth_date, $country, $governorate, $city, $details, $special_marque;

    protected $listeners = ['choosedCountry', 'choosedGovernorate', 'choosedCity', 'details', 'specialMarque'];

    protected $rules = [
        'f_name.ar' => 'required|string|max:20|min:3',
        'f_name.en' => 'nullable|string|max:20|min:3',
        'l_name.ar' => 'required|string|max:20|min:3',
        'l_name.en' => 'nullable|string|max:20|min:3',
        'email' => 'required|email|max:50|min:3|unique:users',
        'phone' => 'nullable|digits_between:3,20',
        'gender' => 'in:0,1',
        'role' => 'exists:roles,id',
        'birth_date' => 'date|before:today',
        'photo' => 'nullable|mimes:jpg,jpeg,png|max:2048',
    ];

    // Called Once at the beginning
    public function mount()
    {
        $this->roles = Role::get();
        $this->role = $this->roles->first()->id;
    }

    // Called with every update
    public function render()
    {
        return view('livewire.admin.users.add-user-form');
    }

    // Real Time Validation
    public function updated($field)
    {
        $this->validateOnly($field);
    }

    // validate and upload photo
    public function updatedPhoto($photo)
    {
        $this->validateOnly($photo);

        $this->image_name = 'profile-' . time() . '-' . rand() . '.' . $this->photo->getClientOriginalExtension();

        // Crop and resize photo
        try {
            $manager = new ImageManager();

            $manager->make($this->photo)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->crop(200, 200)->save('storage/images/profiles/cropped200/' . $this->image_name);
        } catch (\Throwable $th) {
        }

        // Upload photo and get link
        $this->photo->storeAs('original', $this->image_name, 'profiles');

        // for photo rendering
        $this->temp_path = $this->photo->temporaryUrl();
    }

    // remove image
    public function removePhoto()
    {
        $this->image_name = Null;
        $this->temp_path = Null;
    }

    // Get Country data from address component
    public function choosedCountry($choosedCountry)
    {
        $this->country = $choosedCountry;
    }

    // Get Governorate data from address component
    public function choosedGovernorate($choosedGovernorate)
    {
        $this->governorate = $choosedGovernorate;
    }

    // Get City data from address component
    public function choosedCity($choosedCity)
    {
        $this->city = $choosedCity;
    }

    // Get details
    public function details($details)
    {
        $this->details = $details;
    }

    // Get Special Marque
    public function specialMarque($special_marque)
    {
        $this->special_marque = $special_marque;
    }

    // Final Validate and add to database
    public function save($new = false)
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // Add User
            $user = User::create([
                'f_name' => [
                    'ar' => $this->f_name['ar'],
                    'en' => $this->f_name['en'],
                ],
                'l_name' => [
                    'ar' => $this->l_name['ar'],
                    'en' => $this->l_name['en'],
                ],
                'email'                 => $this->email,
                'phone'                 => $this->phone,
                'password'              => Hash::make('Password@1234'),
                'gender'                => $this->gender,
                'profile_photo_path'    =>  $this->image_name,
                'visit_num'             => 0,
                'birth_date'            => $this->birth_date
            ]);

            // Add Address if exist
            if (isset($this->country) && isset($this->governorate) && isset($this->city)) {
                Address::create([
                    'user_id' => $user->id,
                    'country_id' => $this->country,
                    'governorate_id' => $this->governorate,
                    'city_id' => $this->city,
                    'details' => $this->details,
                    'special_marque' => $this->special_marque
                ]);
            }

            // Add Role if exist
            if (isset($this->role)) {
                $user->syncRoles($this->role);
            }

            // Save and End Transaction
            DB::commit();

            if ($new) {
                alert()->success(__('admin/usersPages.User added successfully'))->persistent(false, false)->autoClose($milliseconds = 3000)->timerProgressBar();
                redirect()->route('admin.users.create');
            } else {
                redirect()->route('admin.users.index')->with('success', __('admin/usersPages.User added successfully'));
            }
        } catch (Throwable $th) {
            DB::rollback();
        }
    }
}
