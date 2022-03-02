<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;
use Livewire\Component;
use Livewire\WithFileUploads;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;
use Throwable;

class EditUserForm extends Component
{
    use WithFileUploads;

    public $user_id;
    public $user;
    public $roles;
    public $photo;
    public $temp_path;
    public $oldImage;
    public $image_name;

    public $defaultPhone = 0;
    public $defaultAddress = 0;

    public $f_name = ['ar' => '', 'en' => ''], $l_name = ['ar' => '', 'en' => ''], $email, $phones = [], $addresses = [], $gender = '0', $role, $birth_date;

    public $countries = [], $governorates = [],  $cities = [];

    protected $listeners = ['countryUpdated', 'governorateUpdated','resetPassword'];

    public function rules()
    {
        return [
            'f_name.ar' => 'required|string|max:20|min:3',
            'f_name.en' => 'nullable|string|max:20|min:3',
            'l_name.ar' => 'nullable|string|max:20|min:3',
            'l_name.en' => 'nullable|string|max:20|min:3',
            'email' => 'nullable|required_if:role,2|required_without:phones.0.phone|email|max:50|min:3|unique:users,email,' . $this->user_id,
            'phones.*.phone' => 'nullable|required_without:email|digits_between:8,11|' . Rule::unique('phones')->ignore($this->user_id, 'user_id'),
            'gender' => 'in:0,1',
            'role' => 'exists:roles,id',
            'birth_date' => 'date|before:today',
            'photo' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'addresses.*.country_id'   => 'required|exists:countries,id',
            'addresses.*.governorate_id'    => 'required|exists:governorates,id',
            'addresses.*.city_id'           => 'required|exists:cities,id',
            'defaultAddress'   => 'required',
            'defaultPhone'   => 'required',
        ];
    }

    public function messages()
    {
        return [
            'phones.*.phone.digits_between' => __('validation.The phone numbers must contain digits between 8 & 11'),
            'email.required_if' => __('validation.The Email Address is required when role is admin.'),
        ];
    }

    // Called Once at the beginning
    public function mount($user_id)
    {
        $this->user_id = $user_id;

        // All Roles
        $this->roles = Role::get();

        // get User Data
        $this->user = User::with('phones')->with('addresses')->findOrFail($this->user_id);

        // get old image
        $this->oldImage = $this->user->profile_photo_path;

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

        // Phones
        $this->phones = count($this->user->phones->toArray()) ? $this->user->phones->toArray() : [
            '0' => [
                'user_id' => $this->user_id,
                'phone' => null,
                'default' => 1
            ]
        ];

        // set default phone number
        $this->defaultPhone = key(array_filter($this->phones, function ($phone) {
            return $phone['default'] == 1;
        }));

        // Gender
        $this->gender = $this->user->gender ?? 0;

        // Role
        $this->role = $this->user->roles->first()->id ?? '';

        // Birth date
        $this->birth_date = $this->user->birth_date ?? '';

        // get user addresses if present
        $this->addresses = count($this->user->addresses->toArray()) ? $this->user->addresses->toArray() : ["0" => [
            'user_id' => $this->user_id,
            'country_id' => 1,
            'governorate_id' => 1,
            'city_id' => 1,
            'details' => '',
            'special_marque' => '',
            'default' => 1
        ]];

        // all Addresses
        $this->countries = Country::orderBy('name')->get();

        if ($this->countries->count()) {
            // User Has Addresses
            foreach ($this->addresses as $index => $address) {
                $this->governorates[$index] = Governorate::where('country_id', $address['country_id'])->get()->toArray();
                $this->cities[$index] = City::where('governorate_id', $address['governorate_id'])->get()->toArray();
            }
            $this->defaultAddress = key(array_filter($this->addresses, function ($address) {
                return $address['default'] == 1;
            }));
        }
    }

    // Run with every update
    public function render()
    {
        return view('livewire.admin.users.edit-user-form');
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
        $this->oldImage = Null;
    }
    ######################## Profile Image : End ############################

    ################ Phones #####################
    public function addPhone()
    {
        array_push($this->phones, [
            "phone" => '',
            'default' => 0
        ]);
    }

    public function removePhone($index)
    {
        unset($this->phones[$index]);

        if ($index == $this->defaultPhone) {
            $this->defaultPhone = array_key_first($this->phones);
        }
    }
    ################ Phones #####################


    ################ Addresses #####################
    public function countryUpdated($index)
    {
        $this->governorates[$index] = Governorate::where('country_id', $this->addresses[$index]['country_id'])->get()->toArray();
        $this->addresses[$index]['governorate_id'] = count($this->governorates[$index]) ? $this->governorates[$index][0]['id'] : '';
        $this->cities[$index] = count($this->governorates[$index]) ? City::where('governorate_id', $this->addresses[$index]['governorate_id'])->get()->toArray() : [];
        $this->addresses[$index]['city_id'] = $this->cities[$index] ? $this->cities[$index][0]['id'] : '';
    }

    public function governorateUpdated($index)
    {
        $this->cities[$index] = City::where('governorate_id', $this->addresses[$index]['governorate_id'])->get()->toArray();
        $this->addresses[$index]['city_id'] = $this->cities[$index] ? $this->cities[$index][0]['id'] : '';
    }

    public function addAddress()
    {
        $newAddress = [
            'user_id' => $this->user_id,
            'country_id' => 1,
            'governorate_id' => 1,
            'city_id' => 1,
            'details' => '',
            'special_marque' => '',
            'default' => 0
        ];

        array_push($this->addresses, $newAddress);

        $governorates = Governorate::where('country_id', 1)->get()->toArray();

        array_push($this->governorates, $governorates);

        array_push($this->cities, City::where('governorate_id', 1)->get()->toArray());
    }


    public function removeAddress($index)
    {
        unset($this->addresses[$index]);

        if ($index == $this->defaultAddress) {
            $this->defaultAddress = array_key_first($this->addresses);
        }
    }
    ################ Addresses #####################


    ################ Password #####################
    public function resetPasswordConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirmPassword', [
            "text" => __('admin/usersPages.Are you sure, you want to reset the password ?'),
            'confirmButtonText' => __('admin/usersPages.Confirm') ,
            'denyButtonText' => __('admin/usersPages.Cancel'),
        ]);
    }

    public function resetPassword()
    {
        try {
            $this->user->password = Hash::make(Config::get('constants.constants.DEFAULT_PASSWORD'));
            $this->user->save();

            $this->dispatchBrowserEvent('swalPasswordReset', [
                "text" => __('admin/usersPages.Password has been reset successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalPasswordReset', [
                "text" => __('admin/usersPages.Password hasn\'t been reset'),
                'icon' => 'error'
            ]);
        }
    }
    ################ Password #####################


    ################ Update #####################
    public function save()
    {

        // Final Validate
        $this->validate();

        DB::beginTransaction();

        try {
            ### Basic Data ###
            $this->user->update([
                'f_name' => [
                    'ar' => $this->f_name['ar'],
                    'en' => $this->f_name['en'],
                ],
                'l_name' => [
                    'ar' => $this->l_name['ar'],
                    'en' => $this->l_name['en'],
                ],

                'gender' => $this->gender,

                'email' => $this->email,

                'profile_photo_path'    =>  $this->oldImage ?? $this->image_name,

                'birth_date' => $this->birth_date
            ]);
            ### Basic Data ###

            ### Role ###
            if (isset($this->role)) {
                $this->user->syncRoles($this->role);
            }
            ### Role ###

            ### Add Phones ###
            $this->user->phones()->delete();

            $newPhones = [];

            foreach ($this->phones as $index => $phone) {
                if ($phone['phone']) {
                    $newPhone = new Phone([
                        'phone' => $phone['phone'],
                        'default' => $index == $this->defaultPhone ? 1 : 0
                    ]);
                    array_push($newPhones, $newPhone);
                }
            }

            $this->user->phones()->saveMany($newPhones);
            ### Add Phones ###


            ### Add Addresses ###
            $this->user->addresses()->delete();

            $newAddresses = [];

            foreach ($this->addresses as $index => $address) {
                if ($address['country_id'] && $address['governorate_id'] && $address['city_id']) {
                    $newAddress = new Address([
                        'country_id' => $address['country_id'],
                        'governorate_id' => $address['governorate_id'],
                        'city_id' => $address['city_id'],
                        'details' => $address['details'],
                        'special_marque' => $address['special_marque'],
                        'default' => $index == $this->defaultAddress ? 1 : 0,
                    ]);
                    array_push($newAddresses, $newAddress);
                }
            }

            $this->user->addresses()->saveMany($newAddresses);
            ### Add Addresses ###

            // Save and End Transaction
            DB::commit();

            Session::flash('success', __('admin/usersPages.User updated successfully'));
            redirect()->route('admin.users.index');
        } catch (\Throwable $th) {
            DB::rollback();

            Session::flash('error', __('admin/usersPages.User hasn\'t been updated'));
            redirect()->route('admin.users.index');
        }
    }
    ################ Update #####################


}
