<?php

namespace App\Http\Livewire\Admin\Orders;

use App\Models\User;
use Livewire\Component;

class NewOrderUserPart extends Component
{
    public $search = "";

    public $customer_id, $selectedCustomer;

    protected $listeners = ['clearSearch'];

    public function mount()
    {
        $this->customers = collect([]);
    }

    public function render()
    {
        return view('livewire.admin.orders.new-order-user-part');
    }

    public function updatedSearch()
    {
        if ($this->search != "") {
            $this->customers = User::where(function ($q) {
                $q->where('f_name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('l_name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('f_name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('l_name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereHas('phones', function ($q) {
                        $q->where('phone', 'like', '%' . $this->search . '%');
                    });
            })
                ->get();
        } else {
            $this->customers = collect([]);
        }
    }

    public function updatedCustomerId()
    {
        $this->selectedCustomer = User::findOrFail($this->customer_id);

        // $this->clearSearch();
        
        // $this->render();
    }

    public function clearSearch()
    {
        $this->search = null;
        $this->customers = collect([]);
    }
}
