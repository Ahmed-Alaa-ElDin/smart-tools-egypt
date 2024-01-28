<?php

namespace App\Http\Livewire\Admin\Products;

use Livewire\Component;

class ProductListPopup extends Component
{
    public $selectedCollections = [];
    public $selectedProducts = [];
    public $excludedCollections = [];
    public $excludedProducts = [];

    // General Attributes
    public $show = false;
    public $modalName = "";
    public $model = "product";

    public $totalSelected = 0;

    // Add Listener
    protected $listeners = [
        'show',
        'selectedCollectionsUpdated',
        'selectedProductsUpdated',
        'unselectAll',
    ];

    public function render()
    {
        return view('livewire.admin.products.product-list-popup');
    }


    ######## Unselect All Products #########
    public function unselectAll()
    {
        $this->selectedCollections = [];
        $this->selectedProducts = [];
        $this->totalSelected = 0;
    }
    ######## Unselect All Products #########

    ######## Show Modal #########
    public function show($modalName)
    {
        if ($modalName == $this->modalName) {
            $this->show = true;
        }
    }
    ######## Show Modal #########

    ######## Hide Modal #########
    public function hide($modalName)
    {
        if ($modalName == $this->modalName) {
            $this->show = false;
        }
    }
    ######## Hide Modal #########

    ######## Add Selected Collections #########
    public function selectedCollectionsUpdated($selectedCollections)
    {
        $this->selectedCollections = $selectedCollections;
        $this->totalSelected = count($this->selectedCollections) + count($this->selectedProducts);
    }
    ######## Add Selected Collections #########

    ######## Add Selected Products #########
    public function selectedProductsUpdated($selectedProducts)
    {
        $this->selectedProducts = $selectedProducts;
        $this->totalSelected = count($this->selectedCollections) + count($this->selectedProducts);
    }
    ######## Add Selected Products #########

    ######## Add Selected Items #########
    public function addSelected()
    {
        $this->emit('addSelected', $this->modalName, $this->selectedCollections, $this->selectedProducts);
        $this->emit('unselectAll');
        $this->show = false;
    }
    ######## Add Selected Items #########
}
