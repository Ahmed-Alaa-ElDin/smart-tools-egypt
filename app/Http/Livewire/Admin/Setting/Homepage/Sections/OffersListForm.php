<?php

namespace App\Http\Livewire\Admin\Setting\Homepage\Sections;

use App\Models\Offer;
// use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class OffersListForm extends Component
{
    use WithPagination;

    public $selected_offer;

    ######## Fires once in the beginning : Start ########
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');
        $this->search = "";
    }
    ######## Fires once in the beginning : End ########

    ######## Fires with each update : Start ########
    public function render()
    {
        $offers = Offer::select([
            'id',
            'title',
            'banner',
            'start_at',
            'expire_at',
        ])
            // ->where('start_at', '<=', Carbon::now())
            // ->where('expire_at', '>=', Carbon::now())
            ->where(
                fn ($q) => $q
                    ->where('title->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('title->en', 'like', '%' . $this->search . '%')
            )
            ->orderBy('start_at')
            ->paginate($this->perPage);

        return view('livewire.admin.setting.homepage.sections.offers-list-form', compact('offers'));
    }
    ######## Fires with each update : Start ########

    ######## Select Offer : Start ########
    public function selectOffer($offer_id)
    {
        if ($this->selected_offer == $offer_id) {
            $this->selected_offer = null;
        } else {
            $this->selected_offer = $offer_id;
        }

        $this->emitTo('admin.setting.homepage.sections.section-form', 'listUpdated', ['selected_offer' => $this->selected_offer]);
    }
    ######## Select Offer : End ########
}
