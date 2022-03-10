<?php

namespace App\Http\Livewire\Admin\Deliveries;

use App\Models\Delivery;
use App\Models\DeliveryPhone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class EditDeliveryForm extends Component
{
    use WithFileUploads;

    public $photo;
    public $temp_path;
    public $image_name;
    public $oldImage;

    public $delivery_id;
    public $delivery;

    public $defaultPhone = 0;

    public $name = ['ar' => '', 'en' => ''], $email, $active = 0;

    public function rules()
    {
        return [
            'name.ar'                       => 'required|string|max:30|min:3',
            'name.en'                       => 'nullable|string|max:30|min:3',
            'email'                         => 'nullable|required_without:phones.' . $this->defaultPhone . '.phone|email|max:50|min:3|unique:deliveries,email,' . $this->delivery_id,
            'phones.*.phone'                => 'nullable|required_without:email|digits_between:8,11|' . Rule::unique('phones')->ignore($this->delivery_id, 'user_id'),
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
        ];
    }

    // Called Once at the beginning
    public function mount()
    {
        $this->delivery = Delivery::with('phones')->findOrFail($this->delivery_id);

        // Name
        $this->name = [
            'ar' => $this->delivery->getTranslation('name', 'ar'),
            'en' => $this->delivery->getTranslation('name', 'en')
        ];

        // Email
        $this->email = $this->delivery->email;

        // Phones
        $this->phones = count($this->delivery->phones->toArray())  ? $this->delivery->phones->toArray() : [
            '0' => [
                'phone' => null,
                'default' => 1
            ]
        ];

        // set default phone number
        $this->defaultPhone = key(array_filter($this->phones, function ($phone) {
            return $phone['default'] == 1;
        }));

        // active or Not
        $this->active = $this->delivery->is_active;

        // get old image
        $this->oldImage = $this->delivery->logo_path;
    }

    // Called with every update
    public function render()
    {
        return view('livewire.admin.deliveries.edit-delivery-form');
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
        $imageUpload = imageUpload($photo, 'delivery-company-', 'deliveryCompanies');

        $this->temp_path = $imageUpload["temporaryUrl"];

        $this->image_name = $imageUpload["image_name"];
    }

    // remove image
    public function removePhoto()
    {
        $this->image_name = Null;
        $this->temp_path = Null;
        $this->oldImage = Null;
    }
    ######################## Logo : End ############################

    // Final Validate and add to database
    public function save($new = false, $zones = false)
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // Add Delivery
            $this->delivery->update([
                'name' => [
                    'ar' => $this->name['ar'],
                    'en' => $this->name['en'],
                ],
                'logo_path'    => $this->oldImage ?? $this->image_name,
                'is_active'    => $this->active ? 1 : 0
            ]);

            // Add Email if exists
            if ($this->email != Null) {
                $this->delivery->email = $this->email;
                $this->delivery->save();
            }

            ### Add Phones ###
            $this->delivery->phones()->delete();

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


            $this->delivery->phones()->saveMany($newPhones);
            ### Add Phones ###

            // Save and End Transaction
            DB::commit();

            if ($new) {
                Session::flash('success', __('admin/deliveriesPages.Delivery updated successfully'));
                redirect()->route('admin.deliveries.create');
            } elseif ($zones) {
                $delivery_id = $this->delivery_id;
                Session::flash('success', __('admin/deliveriesPages.Delivery updated successfully'));
                redirect()->route('admin.zones.deliveryZones.edit', ['delivery_id' => $delivery_id]);
            } else {
                Session::flash('success', __('admin/deliveriesPages.Delivery updated successfully'));
                redirect()->route('admin.deliveries.index');
            }
        } catch (Throwable $th) {
            DB::rollback();

            Session::flash('error', __("admin/deliveriesPages.Delivery hasn't been updated"));
            redirect()->route('admin.deliveries.index');
        }
    }
}
