<?php

namespace App\Livewire\Front\Profile;

use App\Models\City;
use App\Models\User;
use App\Models\Phone;
use App\Models\Address;
use App\Models\Country;
use Livewire\Component;
use App\Models\Governorate;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;

class ProfileEdit extends Component
{
    use WithFileUploads;

    public $user_id;
    public $user;
    public $photo;
    public $oldImage;
    public $image_name;
    public $auth_id;

    public $defaultPhone = 0;
    public $defaultAddress = 0;

    public $f_name = ['ar' => '', 'en' => ''];
    public $l_name = ['ar' => '', 'en' => ''];
    public $email;
    public $phone;
    public $gender = '0';
    public $birth_date;
    public $countries = [];
    public $governorates = [];
    public  $cities = [];

    public $phones = [];

    public $addresses = [];

    public $old_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    protected $listeners = ['countryUpdated', 'governorateUpdated'];

    public function rules()
    {
        return [
            'f_name.ar'                     => 'required|string|max:40|min:3',
            'f_name.en'                     => 'nullable|string|max:40|min:3',
            'l_name.ar'                     => 'nullable|string|max:40|min:3',
            'l_name.en'                     => 'nullable|string|max:40|min:3',
            'email'                         => 'nullable|email|max:50|min:3',
            'phones.*.phone'                => 'required_if:auth_id,Null|nullable|digits:11|regex:/^01[0125]\d{1,8}$/|' . Rule::unique('phones')->ignore($this->user_id, 'user_id'),
            'gender'                        => 'in:0,1',
            'birth_date'                    => 'date|before:today',
            'photo'                         => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'addresses.*.country_id'        => 'required|exists:countries,id',
            'addresses.*.governorate_id'    => 'required|exists:governorates,id',
            'addresses.*.city_id'           => 'required|exists:cities,id',
            'addresses.*.details'           => 'required|string',
            'addresses.*.landmarks'         => 'nullable|string',
            'defaultAddress'                => 'required',
            'defaultPhone'                  => 'required',
            'old_password'                  => ['required_with:new_password', 'string', 'max:50', 'min:8', 'current_password'],
            'new_password'                  => ['required_with:old_password', 'string', 'max:50', 'min:8', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'new_password_confirmation'     => ['required_with:old_password', 'string', 'max:50', 'min:8'],
        ];
    }

    // Validation Custom messages
    public function messages()
    {
        return [
            'phones.*.phone.digits_between' => __('validation.The phone numbers must contain digits between 8 & 11'),
            'phones.*.phone.required_if'   => __('validation.The phone number field is required'),
            'addresses.*.details.required'  => __('validation.The details field is required'),
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

        // get user addresses if present
        $this->addresses =  ["0" => [
            'country_id' => 1,
            'governorate_id' => 1,
            'city_id' => 1,
            'details' => '',
            'landmarks' => '',
            'default' => 1
        ]];

        // get all countries
        $this->countries = Country::orderBy('name->' . session('locale'))->get();

        if ($this->countries->count()) {
            // User Has Addresses
            $this->governorates[0] = Governorate::where('country_id', $this->addresses[0]['country_id'])
                ->whereHas('deliveries')
                ->orderBy('name->' . session('locale'))
                ->get()
                ->toArray();
            $this->cities[0] = City::where('governorate_id', $this->addresses[0]['governorate_id'])
                ->whereHas('deliveries')
                ->orderBy('name->' . session('locale'))
                ->get()
                ->toArray();
        }

        // get User Data
        $this->user = User::with([
            'phones',
            'addresses' => fn($q) => $q->with(['country', 'governorate', 'city'])
        ])->findOrFail($this->user_id);

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

        // get auth id (social media id)
        $this->auth_id = $this->user->auth_id;

        // set default phone number
        $this->defaultPhone = key(array_filter($this->phones, function ($phone) {
            return $phone['default'] == 1;
        }));

        // Gender
        $this->gender = $this->user->gender ?? 0;

        // Birth date
        $this->birth_date = $this->user->birth_date ?? '';

        // get user addresses if present
        $this->addresses = count($this->user->addresses->toArray()) ? $this->user->addresses->toArray() : ["0" => [
            'user_id' => $this->user_id,
            'country_id' => 1,
            'governorate_id' => 1,
            'city_id' => 1,
            'details' => '',
            'landmarks' => '',
            'default' => 1
        ]];

        // all Addresses
        $this->countries = Country::orderBy('name->' . session('locale'))->get();

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

    // Called with every update
    public function render()
    {
        return view('livewire.front.profile.profile-edit');
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
        $this->governorates[$index] = Governorate::where('country_id', $this->addresses[$index]['country_id'])
            ->whereHas('deliveries')
            ->orderBy('name->' . session('locale'))
            ->get()
            ->toArray();
        $this->addresses[$index]['governorate_id'] = count($this->governorates[$index]) ? $this->governorates[$index][0]['id'] : '';
        $this->cities[$index] = count($this->governorates[$index]) ? City::where('governorate_id', $this->addresses[$index]['governorate_id'])
            ->whereHas('deliveries')
            ->orderBy('name->' . session('locale'))
            ->get()
            ->toArray() : [];
        $this->addresses[$index]['city_id'] = $this->cities[$index] ? $this->cities[$index][0]['id'] : '';
    }

    public function governorateUpdated($index)
    {
        $this->cities[$index] = City::where('governorate_id', $this->addresses[$index]['governorate_id'])
            ->whereHas('deliveries')
            ->orderBy('name->' . session('locale'))
            ->get()
            ->toArray();
        $this->addresses[$index]['city_id'] = $this->cities[$index] ? $this->cities[$index][0]['id'] : '';
    }

    public function addAddress()
    {
        $newAddress = [
            'country_id' => 1,
            'governorate_id' => 1,
            'city_id' => 1,
            'details' => '',
            'landmarks' => '',
            'default' => 0
        ];

        array_push($this->addresses, $newAddress);

        $governorates = Governorate::where('country_id', 1)
            ->whereHas('deliveries')
            ->orderBy('name->' . session('locale'))
            ->get()
            ->toArray();

        array_push($this->governorates, $governorates);

        array_push($this->cities, City::where('governorate_id', 1)
            ->whereHas('deliveries')
            ->orderBy('name->' . session('locale'))
            ->get()
            ->toArray());
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

        $this->image_name = singleImageUpload($photo, 'profile-', 'profiles');
    }

    // remove image
    public function removePhoto()
    {
        $this->image_name = Null;
        $this->oldImage = Null;
    }
    ######################## Profile Image : End ############################

    ######################## Password : Start ############################
    public function updatedNewPassword()
    {
        $this->validateOnly('new_password_confirmation');
    }

    public function updatedNewPasswordConfirmation()
    {
        $this->validateOnly('new_password');
    }
    ######################## Password : End ############################

    ################ Update #####################
    public function update()
    {

        // Final Validate
        $this->validate();

        DB::beginTransaction();

        try {
            ### Basic Data ###
            $this->user->update([
                'f_name' => [
                    'ar' => $this->f_name['ar'] ?? $this->f_name['en'],
                    'en' => $this->f_name['en'] ?? $this->f_name['ar'],
                ],
                'l_name' => [
                    'ar' => $this->l_name['ar'] ?? $this->l_name['en'],
                    'en' => $this->l_name['en'] ?? $this->l_name['ar'],
                ],

                'gender' => $this->gender ?? 0,

                'email' => $this->email ?? Null,

                'profile_photo_path'    =>  $this->oldImage ?? $this->image_name,

                'birth_date' => $this->birth_date ?? Null,

                'password' => $this->new_password ? Hash::make($this->new_password) : $this->user->password,
            ]);
            ### Basic Data ###

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
                        'landmarks' => $address['landmarks'],
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
            redirect()->route('front.profile.index');
        } catch (\Throwable $th) {
            DB::rollback();

            Session::flash('error', __("admin/usersPages.User has not been updated"));
            redirect()->route('front.profile.index');
        }
    }
    ################ Update #####################
}
