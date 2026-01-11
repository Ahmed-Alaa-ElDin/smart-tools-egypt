<?php

namespace App\Livewire\Front\Order\OrderForm;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Phone;
use Illuminate\Support\Facades\Auth;

class PhoneSection extends Component
{
    public $phones = [];
    public $selected_phone;
    public $selected_phone_secondary;
    public $show_add_form = false;
    public $new_phone = '';

    protected $listeners = ['phoneUpdated' => 'mount'];

    protected $rules = [
        'new_phone' => 'required|regex:/^01[0125][0-9]{8}$/',
    ];

    public function mount()
    {
        if (Auth::check()) {
            $this->phones = Auth::user()->phones()->get();
            $default = $this->phones->where('default', true)->first();
            $this->selected_phone = $default ? $default->phone : ($this->phones->first()?->phone ?? null);
            $this->selected_phone_secondary = null;
            $this->dispatchPhoneSelection();
        }
    }

    public function selectPhone($phone)
    {
        // If selecting the same as secondary, swap them
        if ($this->selected_phone_secondary === $phone) {
            $this->selected_phone_secondary = $this->selected_phone;
        }
        $this->selected_phone = $phone;
        $this->dispatchPhoneSelection();
    }

    public function toggleAddForm()
    {
        $this->show_add_form = !$this->show_add_form;
        if ($this->show_add_form) {
            $this->new_phone = '';
        }
    }

    public function savePhone()
    {
        $this->validate();

        // Check if phone already exists for this user
        $exists = Auth::user()->phones()->where('phone', $this->new_phone)->exists();
        if ($exists) {
            $this->addError('new_phone', __('front/homePage.This phone number already exists.'));
            return;
        }

        $newPhone = Auth::user()->phones()->create([
            'phone' => $this->new_phone,
            'default' => $this->phones->isEmpty(),
        ]);

        $this->show_add_form = false;
        $this->new_phone = '';
        $this->mount();
        $this->selectPhone($newPhone->phone);
    }

    #[On('deletePhone')]
    public function deletePhone($id = null, $phone = null)
    {
        // Support both direct call (phone) and event dispatch (id)
        $phoneNumber = $phone ?? $id;

        if (!$phoneNumber) {
            return;
        }

        if ($this->phones->count() <= 1) {
            return; // Cannot delete last phone
        }

        // Check if deleted phone is default
        $deletedPhone = Auth::user()->phones()->where('phone', $phoneNumber)->first();
        $wasDefault = $deletedPhone && $deletedPhone->default;

        // Delete the phone
        Auth::user()->phones()->where('phone', $phoneNumber)->delete();

        // If deleted phone was default, set another as default
        if ($wasDefault) {
            $newDefault = Auth::user()->phones()->first();
            if ($newDefault) {
                $newDefault->update(['default' => true]);
            }
        }

        // If deleted phone was selected, select another
        if ($this->selected_phone === $phoneNumber) {
            $this->mount();
        } else if ($this->selected_phone_secondary === $phoneNumber) {
            $this->selected_phone_secondary = null;
        }

        $this->phones = Auth::user()->phones()->get();
        $this->dispatchPhoneSelection();
    }

    private function dispatchPhoneSelection()
    {
        // Get remaining phones (excluding selected primary), max 2
        $secondaryPhones = $this->phones
            ->where('phone', '!=', $this->selected_phone)
            ->take(2)
            ->pluck('phone')
            ->implode(' - ');

        $this->dispatch('phoneSelected', [
            'phone1' => $this->selected_phone,
            'phone2' => $secondaryPhones ?: null,
        ])->to(Wrapper::class);
    }

    public function render()
    {
        return view('livewire.front.order.order-form.phone-section');
    }
}
