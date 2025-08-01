<?php

namespace App\Livewire\Front\Order\Shipping;

use App\Enums\OrderStatus;
use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\Order;
use App\Models\Phone;
use App\Models\User;
use App\Models\Zone;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
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
    public $allowToOpenPackage = false;

    protected $listeners = [
        'submit'
    ];

    ################### Mount :: Start ###################
    public function mount()
    {
        $this->resetAddress();

        $oldOrder = Order::where('status_id', OrderStatus::UnderProcessing->value)
            ->where('user_id', auth()->user()->id ?? 0)
            ->first();

        if ($oldOrder) {
            $this->notes = $oldOrder->notes;
            $this->allowToOpenPackage = $oldOrder->allow_opening ? true : false;
        }
    }
    ################### Mount :: End ###################

    ################### Render :: Start ###################
    public function render()
    {
        $this->addresses = collect([]);

        if (auth()->user()) {
            $this->user = User::with([
                'phones',
                'addresses' => fn($q) => $q->with(['country', 'governorate', 'city'])
            ])->findOrFail(auth()->user()->id);

            if ($this->user->addresses->count()) {
                $this->addresses =  $this->user->addresses;
            } else {
                $this->addresses = collect([]);
                $this->changeAddress = true;
            }

            $this->checkDefaults();
        }

        return view('livewire.front.order.shipping.order-shipping-details');
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

        $this->dispatch('AddressUpdated');
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
        $this->governorates = Governorate::where('country_id', $this->address['country_id'])
            ->whereHas('deliveries')
            ->orderBy('name->' . session('locale'))
            ->get()
            ->toArray();
        $this->address['governorate_id'] = count($this->governorates) ? $this->governorates[0]['id'] : '';
        $this->cities = count($this->governorates) ? City::where('governorate_id', $this->address['governorate_id'])
            ->whereHas('deliveries')
            ->orderBy('name->' . session('locale'))
            ->get()
            ->toArray() : [];
        $this->address['city_id'] = $this->cities ? $this->cities[0]['id'] : '';
    }

    public function updatedAddressGovernorateId()
    {
        $this->cities = City::where('governorate_id', $this->address['governorate_id'])
            ->whereHas('deliveries')
            ->orderBy('name->' . session('locale'))
            ->get()
            ->toArray();
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
            'address.details'           => 'required|string',
            'address.landmarks'         => 'nullable|string',
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
            $this->dispatch('AddressUpdated');
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

        // $this->dispatch('PhoneUpdated');
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
            'phone' => 'required|digits:11|regex:/^01[0-2,5]\d{1,8}$/|' . Rule::unique('phones')->ignore($this->user->id, 'user_id'),
        ]);

        Phone::create([
            'user_id'       => $this->user->id,
            'phone'         => $this->phone,
            'default'       => $default
        ]);

        $this->changePhone = false;

        // if ($default) {
        //     $this->dispatch('PhoneUpdated');
        // }
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


    ################### Allow to open package :: Start ###################
    public function updatedAllowToOpenPackage($value)
    {
        $this->dispatch('AllowToOpenPackageUpdated', [
            'allowToOpenPackage' => $value
        ]);
    }
    ################### Allow to open package :: End ###################


    // ----------------------------------------------------------
    // ----------------------------------------------------------
    ################### Submit :: Start ###################
    // ----------------------------------------------------------
    // ----------------------------------------------------------

    public function checkDefaults()
    {
        $zones_count = auth()->user()->addresses->where('default', 1)->count() ?
            Zone::with(['destinations'])
            ->where('is_active', 1)
            ->whereHas('destinations', fn($q) => $q->where('city_id', auth()->user()->addresses->where('default', 1)->first()->city->id))
            ->whereHas('delivery', fn($q) => $q->where('is_active', 1))
            ->count() :
            0;

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
                $order = Order::where('user_id', auth()->user()->id)->where('status_id', OrderStatus::UnderProcessing->value)->first() ?? new Order;

                $order->fill([
                    'user_id' => auth()->user()->id,
                    'status_id' => OrderStatus::UnderProcessing->value,
                    'address_id' => $address->id,
                    'phone1' => $phones->where('default', 1)->first()->phone,
                    'phone2' => $phones->where('default', 0)->count() ? implode("-", $phones->where('default', 0)->pluck('phone')->toArray()) : null,
                    'package_type' => 'parcel',
                    'package_desc' => 'عروض عدد وأدوات قابلة للكسر برجاء المحافظة على مكونات الشحنة لتفادى التلف أو فقدان مكونات الشحنة',
                    'num_of_items' => Cart::instance('cart')->count(),
                    'allow_opening' => $this->allowToOpenPackage,
                    'notes' => $this->notes,
                ]);

                $order->save();

                if (
                    $order->statuses()->count() == 0 ||
                    $order->statuses()->orderBy('pivot_created_at', 'desc')->first()->id != OrderStatus::UnderProcessing->value
                ) {
                    $order->statuses()->attach(OrderStatus::UnderProcessing->value);
                }

                DB::commit();

                $this->dispatch('goToPayment', status: true);
            } catch (\Throwable $th) {
                DB::rollBack();

                $this->dispatch('goToPayment', status: false);
            }
        }
    }
}
