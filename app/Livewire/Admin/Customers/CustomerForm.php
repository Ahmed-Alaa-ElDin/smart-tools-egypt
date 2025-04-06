<?php

namespace App\Livewire\Admin\Customers;

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
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class CustomerForm extends Component
{
    use WithFileUploads;

    public $customer_id;
    public $customer;
    public $photo;
    public $temp_path;
    public $oldImage;
    public $image_name;

    public $defaultPhone = 0;
    public $defaultAddress = 0;

    public $f_name = ['ar' => '', 'en' => ''], $l_name = ['ar' => '', 'en' => ''], $email, $phone, $gender = '0', $birth_date;
    public $countries = [], $governorates = [],  $cities = [];

    public $phones = [];
    public $addresses = [];
    public $role;

    protected $listeners = ['countryUpdated', 'governorateUpdated', 'resetPassword'];

    public function rules()
    {
        return [
            'f_name.ar'                     => 'required|string|max:20|min:3',
            'f_name.en'                     => 'nullable|string|max:20|min:3',
            'l_name.ar'                     => 'nullable|string|max:20|min:3',
            'l_name.en'                     => 'nullable|string|max:20|min:3',
            'photo'                         => 'nullable|image|max:2048',
            'email'                         => 'nullable|email|max:50|min:3|unique:users,email,' . $this->customer_id,
            'phones.*.phone'                => 'nullable|required|digits:11|regex:/^01[0-2]\d{1,8}$/|' . Rule::unique('phones')->ignore($this->customer_id, 'user_id'),
            'gender'                        => 'in:0,1',
            'birth_date'                    => 'date|before:today',
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
        ];
    }

    // Called Once at the beginning
    public function mount()
    {
        // Phones
        $this->phones = [
            '0' => [
                'phone' => null,
                'default' => 1
            ]
        ];

        $this->countries = Country::orderBy('name->' . session('locale'))->get()->toArray();

        if ($this->customer_id) {
            // get User Data
            $this->customer = User::with(['phones', 'addresses'])->findOrFail($this->customer_id);

            // get old image
            $this->oldImage = $this->customer->profile_photo_path;

            // First name
            $this->f_name = [
                'ar' => $this->customer->getTranslation('f_name', 'ar'),
                'en' => $this->customer->getTranslation('f_name', 'en')
            ];

            // Last name
            $this->l_name = [
                'ar' => $this->customer->getTranslation('l_name', 'ar') ?? '',
                'en' => $this->customer->getTranslation('l_name', 'en') ?? ''
            ];

            // Email
            $this->email = $this->customer->email ?? '';

            // Phones
            $this->phones = count($this->customer->phones->toArray()) ? $this->customer->phones->toArray() : [
                '0' => [
                    'user_id' => $this->customer_id,
                    'phone' => null,
                    'default' => 1
                ]
            ];

            // set default phone number
            $this->defaultPhone = key(array_filter($this->phones, function ($phone) {
                return $phone['default'] == 1;
            }));

            // Gender
            $this->gender = $this->customer->gender ?? 0;

            // Role
            $this->role = $this->customer->roles->first()->id ?? '';

            // Birth date
            $this->birth_date = $this->customer->birth_date ?? '';

            // get user addresses if present
            $this->addresses = count($this->customer->addresses->toArray()) ? $this->customer->addresses->toArray() : ["0" => [
                'user_id' => $this->customer_id,
                'country_id' => 1,
                'governorate_id' => 1,
                'city_id' => 1,
                'details' => '',
                'landmarks' => '',
                'default' => 1
            ]];

            // $this->countries = Country::orderBy('name->' . session('locale'))->get();
            if (count($this->countries)) {
                // User Has Addresses
                foreach ($this->addresses as $index => $address) {
                    $this->governorates[$index] = Governorate::where('country_id', $address['country_id'])->get()->toArray();
                    $this->cities[$index] = City::where('governorate_id', $address['governorate_id'])->get()->toArray();
                }
                $this->defaultAddress = key(array_filter($this->addresses, function ($address) {
                    return $address['default'] == 1;
                }));
            }
        } else {
            // get user addresses if present
            $this->addresses =  ["0" => [
                'country_id' => '',
                'governorate_id' => '',
                'city_id' => '',
                'details' => '',
                'landmarks' => '',
                'default' => 1
            ]];

            $this->addresses["0"]['country_id'] = count($this->countries) ? $this->countries["0"]['id'] : null;

            $this->governorates[0] = $this->addresses["0"]['country_id'] ? Governorate::where('country_id', $this->addresses["0"]['country_id'])->orderBy('name->' . session('locale'))->get()->toArray() : [];
            $this->addresses["0"]['governorate_id'] = count($this->governorates[0]) ? $this->governorates[0]["0"]['id'] : null;

            $this->cities[0] = $this->addresses["0"]['governorate_id'] ? City::where('governorate_id', $this->addresses["0"]['governorate_id'])->orderBy('name->' . session('locale'))->get()->toArray() : [];
            $this->addresses["0"]['city_id'] = count($this->cities[0]) ? $this->cities[0]['0']['id'] : null;
        }
    }


    // Called with every update
    public function render()
    {
        // dd($this->countries);
        return view('livewire.admin.customers.customer-form');
    }

    // Real Time Validation
    public function updated($field)
    {
        $this->validateOnly($field);
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
        $this->governorates[$index] = Governorate::where('country_id', $this->addresses[$index]['country_id'])->orderBy('name->' . session('locale'))->get()->toArray();
        $this->addresses[$index]['governorate_id'] = count($this->governorates[$index]) ? $this->governorates[$index][0]['id'] : '';
        $this->cities[$index] = count($this->governorates[$index]) ? City::where('governorate_id', $this->addresses[$index]['governorate_id'])->orderBy('name->' . session('locale'))->get()->toArray() : [];
        $this->addresses[$index]['city_id'] = $this->cities[$index] ? $this->cities[$index][0]['id'] : '';
    }

    public function governorateUpdated($index)
    {
        $this->cities[$index] = City::where('governorate_id', $this->addresses[$index]['governorate_id'])->orderBy('name->' . session('locale'))->get()->toArray();
        $this->addresses[$index]['city_id'] = $this->cities[$index] ? $this->cities[$index][0]['id'] : '';
    }

    public function addAddress()
    {
        $newAddress = [
            'country_id' => '',
            'governorate_id' => '',
            'city_id' => '',
            'details' => '',
            'landmarks' => '',
            'default' => 0
        ];
        $newAddress['country_id'] = count($this->countries) ? $this->countries[0]['id'] : '';

        $governorates = Governorate::where('country_id', $newAddress['country_id'])->orderBy('name->' . session('locale'))->get()->toArray();

        array_push($this->governorates, $governorates);

        $newAddress['governorate_id'] = count($governorates) ? $governorates[0]['id'] : '';

        $cities = City::where('governorate_id', $newAddress['governorate_id'])->orderBy('name->' . session('locale'))->get()->toArray();

        array_push($this->cities, $cities);

        $newAddress['city_id'] = count($cities) ? $cities[0]['id'] : '';

        array_push($this->addresses, $newAddress);
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
        $this->dispatch('swalConfirmPassword', text: __('admin/usersPages.Are you sure, you want to reset the password ?'),
            confirmButtonText: __('admin/usersPages.Confirm'),
            denyButtonText: __('admin/usersPages.Cancel'));
    }

    public function resetPassword()
    {
        try {
            $this->customer->password = Hash::make(Config::get('constants.constants.DEFAULT_PASSWORD'));
            $this->customer->save();

            $this->dispatch('swalPasswordReset', text: __('admin/usersPages.Password has been reset successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalPasswordReset', text: __("admin/usersPages.Password has not been reset"),
                icon: 'error');
        }
    }
    ################ Password #####################

    ######################## Profile Image : Start ############################
    // validate and upload photo
    public function updatedPhoto($photo)
    {
        try {
            $this->validateOnly('photo');

            $imageUpload = singleImageUpload($photo, 'profile-', 'profiles');

            $directory = asset("storage/images/profiles");
            $this->temp_path = "$directory/original/$imageUpload";
            $this->image_name = $imageUpload;
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->reset('photo'); // Reset the photo property if validation fails
            throw $e; // Re-throw the exception to show the error message
        }
    }

    // remove image
    public function removePhoto()
    {
        $this->image_name = Null;
        $this->temp_path = Null;
        $this->oldImage = Null;
    }
    ######################## Profile Image : End ############################

    // Final Validate and add to database
    public function save($new = false)
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // Add User
            $customer = User::create([
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
                'birth_date'            => !empty($this->birth_date) ? $this->birth_date : null,
            ]);

            // Add Email if exists
            if ($this->email != Null) {
                $customer->email = $this->email;
                $customer->save();
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

            $customer->phones()->saveMany($newPhones);
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
                        'landmarks' => $address['landmarks'],
                        'default' => $index == $this->defaultAddress ? 1 : 0,
                    ]);
                    array_push($newAddresses, $newAddress);
                }
            }

            $customer->addresses()->saveMany($newAddresses);
            ### Add Addresses ###

            // Add Role if exist
            $customer->syncRoles(1);

            // Save and End Transaction
            DB::commit();

            if ($new) {
                Session::flash('success', __('admin/usersPages.User added successfully'));
                redirect()->route('admin.customers.create');
            } else {
                Session::flash('success', __('admin/usersPages.User added successfully'));
                redirect()->route('admin.customers.index');
            }
        } catch (Throwable $th) {
            DB::rollback();

            Session::flash('error', __("admin/usersPages.User has not been added"));
            redirect()->route('admin.customers.index');
        }
    }

    ################ Update #####################
    public function update()
    {
        // Final Validate
        $this->validate();

        DB::beginTransaction();

        try {
            ### Basic Data ###
            $this->customer->update([
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

                'birth_date' => !empty($this->birth_date) ? $this->birth_date : null,
            ]);
            ### Basic Data ###

            ### Role ###
            $this->customer->syncRoles(1);
            ### Role ###

            ### Add Phones ###
            $this->customer->phones()->delete();

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

            $this->customer->phones()->saveMany($newPhones);
            ### Add Phones ###


            ### Add Addresses ###
            $this->customer->addresses()->delete();

            $newAddresses = [];

            foreach ($this->addresses as $index => $address) {
                if ($address['country_id'] && $address['governorate_id'] && $address['city_id']) {
                    $newAddress = new Address([
                        'country_id' => $address['country_id'],
                        'governorate_id' => $address['governorate_id'],
                        'city_id' => $address['city_id'],
                        'details' => $address['details'],
                        'landmarks' => $address['landmarks'],
                        'default' => $index == $this->defaultAddress ? 1 : 0,
                    ]);
                    array_push($newAddresses, $newAddress);
                }
            }

            $this->customer->addresses()->saveMany($newAddresses);
            ### Add Addresses ###

            // Save and End Transaction
            DB::commit();

            Session::flash('success', __('admin/usersPages.Customer updated successfully'));
            redirect()->route('admin.customers.index');
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollback();

            Session::flash('error', __("admin/usersPages.Customer has not been updated"));
            redirect()->route('admin.customers.index');
        }
    }
    ################ Update #####################
}
