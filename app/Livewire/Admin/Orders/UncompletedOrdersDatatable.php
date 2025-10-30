<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Cart;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class UncompletedOrdersDatatable extends Component
{
    use WithPagination;

    public $search;
    public $perPage;
    public $sortBy;
    public $sortDirection;

    protected $listeners = ['deleteCart'];

    // Before First Render
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'shoppingcart.created_at';

        $this->sortDirection = 'DESC';

        $this->search = "";
    }

    public function render()
    {
        $carts = Cart::with([
            'user' => fn($q) => $q->select('id', 'f_name', 'l_name')
                ->with([
                    'phones' => fn($q) => $q->select('id', 'user_id', 'phone', 'default'),
                    'defaultAddress'
                    => fn($q) => $q->select('id', 'governorate_id', 'city_id', 'default', 'user_id')->with([
                        'governorate' => fn($q) => $q->select('id', 'name'),
                        'city' => fn($q) => $q->select('id', 'name'),
                    ]),
                ])
        ])
            ->whereHas(
                'user',
                fn($q) => $q
                    ->where(DB::raw('LOWER(f_name)'), 'like', '%' . strtolower($this->search) . '%')
                    ->orWhere(DB::raw('LOWER(l_name)'), 'like', '%' . strtolower($this->search) . '%')
                    ->orWhereHas('phones', fn($q) => $q->where('phone', 'like', '%' . strtolower($this->search) . '%'))
            )
            ->cartsOnly()
            ->notEmpty()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.orders.uncompleted-orders-datatable', compact('carts'));
    }

    public function setSortBy($field)
    {
        $this->sortBy = $field;
        $this->sortDirection = $this->sortDirection === 'ASC' ? 'DESC' : 'ASC';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showCartItems($identifier)
    {
        $this->dispatch('showCartItems', identifier: $identifier);
    }

    public function deleteCart($identifier)
    {
        try {
            Cart::where('identifier', $identifier)
                ->where('instance', 'cart')
                ->delete();

            $this->dispatch('swalDone', text: __('admin/ordersPages.Cart has been deleted successfully'), icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __('admin/ordersPages.Cart has not been deleted'), icon: 'error');
        }
    }
}
