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

class AddUserForm extends Component
{
    use WithFileUploads;

    public $roles;
    public $photo;
    public $temp_path;
    public $image_name;

    public $defaultPhone = 0;
    public $defaultAddress = 0;

    public $f_name = ['ar' => '', 'en' => ''], $l_name = ['ar' => '', 'en' => ''], $email, $phone, $gender = '0', $role, $birth_date;

    public $countries = [], $governorates = [],  $cities = [];

    protected $listeners = ['countryUpdated', 'governorateUpdated'];

    public function rules()
    {
        return [
            'f_name.ar'                     => 'required|string|max:20|min:3',
            'f_name.en'                     => 'nullable|string|max:20|min:3',
            'l_name.ar'                     => 'nullable|string|max:20|min:3',
            'l_name.en'                     => 'nullable|string|max:20|min:3',
            'email'                         => 'nullable|required_if:role,2|required_without:phones.0.phone|email|max:50|min:3|unique:users,email',
            'phones.*.phone'                => 'nullable|required_without:email|digits_between:8,11|' . Rule::unique('phones'),
            'gender'                        => 'in:0,1',
            'role'                          => 'exists:roles,id',
            'birth_date'                    => 'date|before:today',
            'photo'                         => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'addresses.*.country_id'        => 'required|exists:countries,id',
            'addresses.*.governorate_id'    => 'required|exists:governorates,id',
            'addresses.*.city_id'           => 'required|exists:cities,id',
            'defaultAddress'                => 'required',
            'defaultPhone'                  => 'required',
        ];
    }

    // Validation Custom messages
    public function messages()
    {
        return [
            'phones.*.phone.digits_between' => __('validation.The phone numbers must contain digits between 8 & 11'),
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

        // Phones
        $this->phones = [
            '0' => [
                'phone' => null,
                'default' => 1
            ]
        ];

        // get user addresses if present
        $this->addresses =  ["0" => [
            'country_id' => 1,
            'governorate_id' => 1,
            'city_id' => 1,
            'details' => '',
            'special_marque' => '',
            'default' => 1
        ]];

        // get all countries
        $this->countries = Country::orderBy('name')->get();

        if ($this->countries->count()) {
            // User Has Addresses
            $this->governorates[0] = Governorate::where('country_id', $this->addresses[0]['country_id'])->get()->toArray();
            $this->cities[0] = City::where('governorate_id', $this->addresses[0]['governorate_id'])->get()->toArray();
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

    ######################## Profile Image : Start ############################
    // validate and upload photo
    public function updatedPhoto($photo)
    {
        $this->validateOnly($photo);

        $imageUpload = imageUpload($photo, 'profile-', 'profiles');

        $this->temp_path = $imageUpload["temporaryUrl"];

        $this->image_name = $imageUpload["image_name"];

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
                    'en' => $this->f_name['en'] != null ? $this->f_name['en'] : $this->f_name['ar'],
                ],
                'l_name' => [
                    'ar' => $this->l_name['ar'],
                    'en' => $this->l_name['en'],
                ],
                'password'              => Hash::make(Config::get('constants.constants.DEFAULT_PASSWORD')),
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

            ### Add Phones ###
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

            $user->phones()->saveMany($newPhones);
            ### Add Phones ###


            ### Add Addresses ###
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

            $user->addresses()->saveMany($newAddresses);
            ### Add Addresses ###

            // Add Role if exist
            if (isset($this->role)) {
                $user->syncRoles($this->role);
            }

            // Save and End Transaction
            DB::commit();

            if ($new) {
                Session::flash('success', __('admin/usersPages.User added successfully'));
                redirect()->route('admin.users.create');
            } else {
                Session::flash('success', __('admin/usersPages.User added successfully'));
                redirect()->route('admin.users.index');
            }
        } catch (Throwable $th) {
            DB::rollback();

            Session::flash('error', __("admin/usersPages.User hasn't been added"));
            redirect()->route('admin.users.index');
        }
    }
}
