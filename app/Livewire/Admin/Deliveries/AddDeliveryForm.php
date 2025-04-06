<?php

namespace App\Livewire\Admin\Deliveries;

use App\Models\Delivery;
use App\Models\DeliveryPhone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class AddDeliveryForm extends Component
{

    use WithFileUploads;

    public $photo;
    public $temp_path;
    public $image_name;
    public $phones = [];
    public $defaultPhone = 0;

    public $name = ['ar' => '', 'en' => ''], $email, $active = 1;

    public function rules()
    {
        return [
            'name.ar'                       => 'required|string|max:30|min:3',
            'name.en'                       => 'nullable|string|max:30|min:3',
            'email'                         => 'nullable|required_without:phones.' . $this->defaultPhone . '.phone|email|max:50|min:3|unique:deliveries,email',
            'phones.*.phone'                => 'nullable|required_without:email|digits:11|regex:/^01[0-2]\d{1,8}$/|' . Rule::unique('phones'),
            'photo'                         => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'defaultPhone'                  => 'required',
            'active'                        => 'required',
        ];
    }

    // Validation Custom messages
    public function messages()
    {
        return [
            'phones.*.phone.digits_between' => __('validation.The phone numbers must contain digits between 8 & 11'),
            'attributes' => [
                'phones.*.phone' => "phone"
            ]
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
    }

    // Called with every update
    public function render()
    {
        return view('livewire.admin.deliveries.add-delivery-form');
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


    ######################## Logo : Start ############################
    // validate and upload photo
    public function updatedPhoto($photo)
    {
        $this->validateOnly($photo);

        // for photo rendering
        $imageUpload = singleImageUpload($photo, 'delivery-company-', 'deliveryCompanies');

        $directory = asset("storage/images/deliveryCompanies");

        $this->temp_path = "$directory/original/$imageUpload";

        $this->image_name = $imageUpload;
    }

    // remove image
    public function removePhoto()
    {
        $this->image_name = Null;
        $this->temp_path = Null;
    }
    ######################## Logo : End ############################

    // Final Validate and add to database
    public function save($new = false, $zones = false)
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // Add Delivery
            $delivery = Delivery::create([
                'name' => [
                    'ar' => $this->name['ar'],
                    'en' => $this->name['en'] != null ? $this->name['en'] : $this->name['ar'],
                ],
                'logo_path'    =>  $this->image_name,
                'is_active'    => $this->active ? 1 : 0
            ]);

            // Add Email if exists
            if ($this->email != Null) {
                $delivery->email = $this->email;
                $delivery->save();
            }

            ### Add Phones ###
            $newPhones = [];

            foreach ($this->phones as $index => $phone) {
                if ($phone['phone']) {
                    $newPhone = new DeliveryPhone([
                        'phone' => $phone['phone'],
                        'default' => $index == $this->defaultPhone ? 1 : 0
                    ]);
                    array_push($newPhones, $newPhone);
                }
            }


            $delivery->phones()->saveMany($newPhones);
            ### Add Phones ###

            // Save and End Transaction
            DB::commit();

            if ($new) {
                Session::flash('success', __('admin/deliveriesPages.Delivery added successfully'));
                redirect()->route('admin.deliveries.create');
            } elseif ($zones) {
                $delivery_id = $delivery->id;
                Session::flash('success', __('admin/deliveriesPages.Delivery added successfully'));
                redirect()->route('admin.zones.deliveryZones.edit', ['delivery_id' => $delivery_id]);
            } else {
                Session::flash('success', __('admin/deliveriesPages.Delivery added successfully'));
                redirect()->route('admin.deliveries.index');
            }
        } catch (Throwable $th) {
            DB::rollback();

            Session::flash('error', __("admin/deliveriesPages.Delivery has not been added"));
            redirect()->route('admin.deliveries.index');
        }
    }
}
