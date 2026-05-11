<?php

namespace App\Livewire\Admin\Customers;

use App\Models\User;
use App\Models\Point;
use Livewire\Component;

class CustomerProfile extends Component
{
    public $customer_id;

    protected $listeners = ['addPoints', 'addBalance'];

    public function mount($customer_id)
    {
        $this->customer_id = $customer_id;
    }

    public function getCustomerProperty()
    {
        return User::with([
            'addresses' => fn($q) => $q->with(['governorate', 'city']),
            'phones',
            'orders' => fn($q) => $q->with(['status', 'invoice'])->orderBy('created_at', 'desc'),
            'points' => fn($q) => $q->orderBy('created_at', 'desc'),
        ])->findOrFail($this->customer_id);
    }

    public function addPoints($user_id, $points)
    {
        if ($this->customer_id != $user_id) return;

        try {
            Point::create([
                'user_id' => $user_id,
                'value' => $points,
                'status' => 1, // Approved
            ]);

            $this->dispatch(
                'swalDone',
                text: __('admin/usersPages.Points added successfully'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __('admin/usersPages.Points haven\'t been added'),
                icon: 'error'
            );
        }
    }

    public function addBalance($user_id, $balance)
    {
        if ($this->customer_id != $user_id) return;

        try {
            $user = User::findOrFail($user_id);
            $user->increment('balance', $balance);

            $this->dispatch(
                'swalDone',
                text: __('admin/usersPages.Balance added successfully'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __('admin/usersPages.Balance haven\'t been added'),
                icon: 'error'
            );
        }
    }

    public function render()
    {
        return view('livewire.admin.customers.customer-profile', [
            'customer' => $this->customer
        ]);
    }
}
