<?php

namespace App\Http\Livewire\Front\Order;

use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\Destination;
use App\Models\Governorate;
use App\Models\Order;
use App\Models\Phone;
use App\Models\User;
use App\Models\Zone;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;

class OrderShippingDetails extends Component
{
    public $user;
    public $addresses;
    public $changeAddress;

    public $address;
    public $countries = [], $governorates = [], $cities = [];
    public $country = null, $governorate = null, $city = null;

    public $phone;
    public $changePhone;
    public $notes;
    public $billing = false;

    ################### Mount :: Start ###################
    public function mount()
    {
        $this->resetAddress();
    }
    ################### Mount :: End ###################

    ################### Render :: Start ###################
    public function render()
    {
        $this->addresses = collect([]);

        if (auth()->user()) {
            $this->user = User::with([
                'phones',
                'addresses' => fn ($q) => $q->with(['country', 'governorate', 'city'])
            ])->findOrFail(auth()->user()->id);

            if ($this->user->addresses->count()) {
                $this->addresses =  $this->user->addresses;
            } else {
                $this->addresses = collect([]);
                $this->changeAddress = true;
            }

            $this->checkDefaults();
        }

        return view('livewire.front.order.order-shipping-details');
    }
    ################### Render :: End ###################


    // ----------------------------------------------------------
    // ----------------------------------------------------------
    ################### Addresses :: Start ###################
    // ----------------------------------------------------------
    // ----------------------------------------------------------

    ################### Select Address :: Start ###################
    public function selectAddress($address_id)
    {
        if ($this->user->addresses->where('default', 1)->count() && $this->user->addresses->where('default', 1)->first()->id != $address_id) {
            // remove default from old address
            $this->user->addresses->where('default', 1)->first()->update(['default' => 0]);
            // set default to new address
            $this->user->addresses->where('id', $address_id)->first()->update(['default' => 1]);
        } else {
            // set default to new address
            $this->user->addresses->where('id', $address_id)->first()->update(['default' => 1]);
        }

        $this->emit('AddressUpdated');
        $this->emit('cartUpdated');
    }
    ################### Select Address :: End ###################

    ################### Add New Address :: Start ###################
    public function addAddress()
    {
        $this->changeAddress = true;

        $this->resetAddress();
    }
    ################### Add New Address :: End ###################

    ################ Update Addresses' Fields :: Start #####################
    public function updatedAddressCountryId()
    {
        $this->governorates = Governorate::where('country_id', $this->address['country_id'])->orderBy('name->' . session('locale'))->get()->toArray();
        $this->address['governorate_id'] = count($this->governorates) ? $this->governorates[0]['id'] : '';
        $this->cities = count($this->governorates) ? City::where('governorate_id', $this->address['governorate_id'])->orderBy('name->' . session('locale'))->get()->toArray() : [];
        $this->address['city_id'] = $this->cities ? $this->cities[0]['id'] : '';
    }

    public function updatedAddressGovernorateId()
    {
        $this->cities = City::where('governorate_id', $this->address['governorate_id'])->orderBy('name->' . session('locale'))->get()->toArray();
        $this->address['city_id'] = $this->cities ? $this->cities[0]['id'] : '';
    }
    ################ Update Addresses' Fields :: End #####################

    ################### Save Address :: Start ###################
    public function saveAddress($default)
    {
        $this->validate([
            'address.country_id'        => 'required|exists:countries,id',
            'address.governorate_id'    => 'required|exists:governorates,id',
            'address.city_id'           => 'required|exists:cities,id',
        ]);

        Address::create([
            'user_id'           => $this->user->id,
            'country_id'        => $this->address['country_id'],
            'governorate_id'    => $this->address['governorate_id'],
            'city_id'           => $this->address['city_id'],
            'details'           => $this->address['details'],
            'landmarks'         => $this->address['landmarks'],
            'default'           => $default
        ]);

        $this->changeAddress = false;

        if ($default) {
            $this->emit('AddressUpdated');
            $this->emit('cartUpdated');
        }
    }
    ################### Save Address :: End ###################

    ################### Cancel adding new Address :: Start ###################
    public function cancelAddress()
    {
        $this->changeAddress = false;
    }
    ################### Cancel adding new Address :: End ###################

    ################### Remove Address :: Start ###################
    public function removeAddress($address_id)
    {
        $this->user->addresses->where('id', $address_id)->first()->delete();
    }
    ################### Remove Address :: End ###################

    ################### Reset Address :: Start ###################
    public function resetAddress()
    {
        $this->countries = Country::get()->toArray();
        $this->address['country_id'] = count($this->countries) ? $this->countries[0]['id'] : null;

        $this->governorates = $this->address['country_id'] ? Governorate::where('country_id', $this->address['country_id'])->orderBy('name->' . session('locale'))->get()->toArray() : [];
        $this->address['governorate_id'] = count($this->governorates) ? $this->governorates[0]['id'] : null;

        $this->cities = $this->address['governorate_id'] ? City::where('governorate_id', $this->address['governorate_id'])->orderBy('name->' . session('locale'))->get()->toArray() : [];
        $this->address['city_id'] = count($this->cities) ? $this->cities[0]['id'] : null;

        $this->address['details'] = null;
        $this->address['landmarks'] = null;
    }
    ################### Reset Address :: End ###################

    // ----------------------------------------------------------
    // ----------------------------------------------------------
    ################### Phones :: Start ###################
    // ----------------------------------------------------------
    // ----------------------------------------------------------

    ################### Select Phone :: Start ###################
    public function selectPhone($phone_id)
    {
        if ($this->user->phones->where('default', 1)->count() && $this->user->phones->where('default', 1)->first()->id != $phone_id) {
            // remove default from old phone
            $this->user->phones->where('default', 1)->first()->update(['default' => 0]);
            // set default to new phone
            $this->user->phones->where('id', $phone_id)->first()->update(['default' => 1]);
        } else {
            // set default to new phone
            $this->user->phones->where('id', $phone_id)->first()->update(['default' => 1]);
        }
    }
    ################### Select Phone :: End ###################

    ################### Add New Phone :: Start ###################
    public function addPhone()
    {
        $this->changePhone = true;

        $this->phone = null;
    }
    ################### Add New Phone :: End ###################

    ################### Save Phone :: Start ###################
    public function savePhone($default)
    {
        $this->validate([
            'phone' => 'required|digits_between:8,11|' . Rule::unique('phones')->ignore($this->user->id, 'user_id'),
        ]);

        Phone::create([
            'user_id'       => $this->user->id,
            'phone'         => $this->phone,
            'default'       => $default
        ]);

        $this->changePhone = false;
    }
    ################### Save Phone :: End ###################

    ################### Cancel adding new Phone :: Start ###################
    public function cancelPhone()
    {
        $this->changePhone = false;
    }
    ################### Cancel adding new Phone :: End ###################

    ################### Remove Phone :: Start ###################
    public function removePhone($phone_id)
    {
        $this->user->phones->where('id', $phone_id)->first()->delete();
    }
    ################### Remove Phone :: End ###################

    // ----------------------------------------------------------
    // ----------------------------------------------------------
    ################### Submit :: Start ###################
    // ----------------------------------------------------------
    // ----------------------------------------------------------

    public function checkDefaults()
    {
        $zones_count = auth()->user()->addresses->where('default', 1)->count() ? Zone::with(['destinations'])
            ->where('is_active', 1)
            ->whereHas('destinations', fn ($q) => $q->where('city_id', auth()->user()->addresses->where('default', 1)->first()->city->id))
            ->whereHas('delivery', fn ($q) => $q->where('is_active', 1))
            ->count() : 0;

        if (auth()->user()->addresses->where('default', 1)->count() && auth()->user()->phones->where('default', 1)->count() && $zones_count) {
            $this->billing = true;
            return true;
        } else {
            $this->billing = false;
            return false;
        }
    }

    ################### Submit :: Start ###################
    public function submit()
    {
        if ($this->checkDefaults()) {
            $phones = auth()->user()->phones;
            $address = auth()->user()->addresses->where('default', 1)->first();

            DB::beginTransaction();

            try {
                $order = Order::updateOrCreate([
                    'user_id' => auth()->user()->id,
                    'status_id' => 1,
                ], [
                    'address_id' => $address->id,
                    'phone1' => $phones->where('default', 1)->first()->phone,
                    'phone2' => $phones->where('default', 0)->count() ? implode("-", $phones->where('default', 0)->pluck('phone')->toArray()) : null,
                    'package_type' => 'parcel',
                    'package_desc' => 'قابل للكسر',
                    'num_of_items' => Cart::instance('cart')->count(),
                    'allow_opening' => 1,
                    'payment_status' => 0,
                    'notes' => $this->notes,
                ]);

                DB::commit();

                Session::flash('success', __('front/homePage.Shipping Details Saved Successfully'));
                redirect()->route('front.order.billing');
            } catch (\Throwable $th) {
                DB::rollBack();

                Session::flash('error', __("front/homePage.Shipping Details Haven't Saved"));
                redirect()->route('front.order.shipping');
            }
        }
    }
}
