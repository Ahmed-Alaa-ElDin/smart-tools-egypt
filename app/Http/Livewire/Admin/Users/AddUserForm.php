<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\Phone;
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

    public $countries = [], $governorates = [],  $cities = [];

    protected $rules = [
        'f_name.ar' => 'required|string|max:20|min:3',
        'f_name.en' => 'nullable|string|max:20|min:3',
        'l_name.ar' => 'required|string|max:20|min:3',
        'l_name.en' => 'nullable|string|max:20|min:3',
        'email' => 'nullable|required_if:role,2|required_without:phone|email|max:50|min:3|unique:users',
        'phone' => 'nullable|required_without:email|digits_between:8,11|unique:phones,phone',
        'gender' => 'in:0,1',
        'role' => 'exists:roles,id',
        'birth_date' => 'date|before:today',
        'photo' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        'country'        => 'required|exists:countries,id',
        'governorate'    => 'required|exists:governorates,id',
        'city'           => 'required|exists:cities,id',

    ];

    // Validation Custom messages
    public function messages()
    {
        return [
            'email.required_if' => __('validation.The Email Address is required when role is admin.'),
        ];
    }

    // Called Once at the beginning
    public function mount()
    {
        // Get all roles
        $this->roles = Role::get();

        // Select first role in database (customer)
        $this->role = $this->roles->first()->id;

        // get all countries
        $this->countries = Country::orderBy('name')->get();

        // Choose first country
        $this->country = $this->countries->first()->id ?? Null;

        // get all governorates
        if ($this->country != Null) {
            $this->governorates = Governorate::where('country_id', $this->country)->orderBy('name')->get();

            // Choose first governorate
            $this->governorate = $this->governorates->count() ? $this->governorates->first()->id : Null;

            // get all cities
            $this->cities = City::where('governorate_id', $this->governorate)->orderBy('name')->get();
            $this->city = $this->cities->first()->id ?? Null;
        }
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

        if ($field == 'phone') {
            $this->validateOnly('email');
        }

        if ($field == 'email') {
            $this->validateOnly('phone');
        }

        if ($field == 'role') {
            $this->validateOnly('email');
        }
    }

    // Call when Choose new Country
    public function updatedCountry()
    {
        $this->governorates = Governorate::where('country_id', $this->country)->get();

        if (!$this->governorates->count()) {
            $this->cities = [];
        } else {
            $this->governorate = $this->governorates->first()->id;

            $this->cities = City::where('governorate_id', $this->governorate)->orderBy('name')->get();
        }
    }

    // Call when Choose new Governorate
    public function updatedGovernorate()
    {
        $this->cities = City::where('governorate_id', $this->governorate)->orderBy('name')->get();

        if (!$this->cities->count()) {
            $this->cities = [];
        } else {
            $this->city = $this->cities->first()->id;
        }
    }

    ######################## Profile Image : Start ############################
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
    ######################## Profile Image : End ############################

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
                'password'              => Hash::make('Password@1234'),
                'gender'                => $this->gender,
                'profile_photo_path'    =>  $this->image_name,
                'visit_num'             => 0,
                'birth_date'            => $this->birth_date
            ]);

            // Add Email if exists
            if ($this->email != Null) {
                $user->email = $this->email;
                $user->save();
            }

            // Add Phone if exists
            if (isset($this->phone)) {
                Phone::create([
                    'user_id' => $user->id,
                    'phone' => $this->phone,
                    'default' => 1
                ]);
            }

            // Add Address if exists
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
            redirect()->route('admin.users.index')->with('error', __('admin/usersPages.User hasn\'t been added'));
        }
    }
}
