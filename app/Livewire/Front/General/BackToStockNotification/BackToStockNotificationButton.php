<?php

namespace App\Livewire\Front\General\BackToStockNotification;

use Livewire\Component;
use App\Facades\MetaPixel;
use Illuminate\Support\Facades\DB;
use App\Models\BackToStockNotification;
use Illuminate\Validation\ValidationException;

class BackToStockNotificationButton extends Component
{
    public $item_id, $type = 'Product', $text = false, $large = false, $isNotified = false, $phone, $item;

    protected $listeners = ['confirmGuestPhone' => 'confirmGuestPhone'];

    protected $rules = [
        'phone' => 'required|regex:/^01[0125]\d{8}$/',
    ];

    public function mount()
    {
        if (auth()->check()) session()->forget('guest_phone');

        $this->phone = session()->get('guest_phone', '');

        $this->item = $this->resolveItemModel();
    }

    public function render()
    {
        return view('livewire.front.general.back-to-stock-notification.back-to-stock-notification-button');
    }

    ############## Add To Notify me :: Start ##############
    public function addToNotify()
    {
        try {
            if (!$this->item) {
                $this->dispatchErrorNotification(__('front/homePage.Item not found'));
                return;
            }

            // Authenticated user flow
            if (auth()->check()) {
                $this->createNotificationForAuthUser();
            }

            // Guest user flow
            elseif ($this->phone) {
                $this->createNotificationForGuest();
            } else {
                $this->dispatchGuestNotificationModal();
                return;
            }

            // Change the state of the button
            $this->isNotified = true;

            MetaPixel::sendEvent("Subscribe", [], [
                'content_type' => 'product',
                'content_ids' => [$this->item_id],
                'content_name' => $this->item->name,
                'contents' => [$this->item->toArray()],
                'currency' => 'EGP',
                'value' => $this->item->final_price,
            ]);

            $this->dispatchSuccessNotification(__('front/homePage.The notification has been added successfully'));
        } catch (\Exception $e) {
            $this->dispatchErrorNotification(__('front/homePage.An error occurred. Please try again.'));
        }
    }

    public function confirmGuestPhone($phone)
    {
        try {
            $this->phone = $phone;

            // Validate phone
            $this->validateOnly('phone');

            // Save phone
            session()->put('guest_phone', $this->phone);

            // add to notify
            $this->createNotificationForGuest();

            // Change the state of the button
            $this->isNotified = true;

            $this->dispatchSuccessNotification(__('front/homePage.The notification has been added successfully'));
        } catch (ValidationException $e) {
            $this->phone = "";

            $this->dispatchErrorNotification(implode("\n", $e->validator->errors()->all()));
        } catch (\Exception $e) {
            $this->dispatchErrorNotification(__('front/homePage.An error occurred. Please try again.'));
        }
    }

    protected function dispatchGuestNotificationModal()
    {
        $this->dispatch(
            'swalGetGuestPhone',
            title: __('front/homePage.Enter the phone number'),
            html: $this->getGuestPhoneHtml(),
            confirmButtonText: __('front/homePage.Notify'),
            denyButtonText: __('front/homePage.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: true,
            icon: 'warning',
            method: 'confirmGuestPhone',
        );
    }

    protected function getGuestPhoneHtml(): string
    {
        return
            "<div class='flex flex-col p-2 gap-3'>
                <div>
                    <label class='text-gray-600' for='guest_phone'>
                        " . __('front/homePage.Phone number') . "
                    </label>
                    <input type='number' id='guest_phone' dir='ltr'
                        placeholder='" . __('front/homePage.Enter the phone number') . "'
                        class='text-center focus:ring-primary focus:border-primary flex-1 block w-full rounded-md sm:text-sm border-gray-300'>
                </div>
            </div>
            ";
    }

    protected function resolveItemModel()
    {
        $modelClass = "App\\Models\\{$this->type}";

        if (!class_exists($modelClass)) {
            $this->dispatchErrorNotification(__('front/homePage.An error occurred. Please try again.'));
            return null;
        }

        return $modelClass::find($this->item_id);
    }

    protected function userAlreadyNotified(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->item->backToStockNotifications()
            ->where('user_id', auth()->id())
            ->whereNull('sent_at')
            ->exists();
    }

    protected function guestAlreadyNotified(): bool
    {
        if (!session()->has('guest_phone')) {
            return false;
        }

        return $this->item->backToStockNotifications()
            ->where('phone', session()->get('guest_phone'))
            ->whereNull('sent_at')
            ->exists();
    }

    protected function createNotificationForAuthUser()
    {
        try {
            DB::beginTransaction();

            $notification = BackToStockNotification::create([
                'user_id' => auth()->id(),
            ]);

            $this->item->backToStockNotifications()->syncWithoutDetaching([$notification->id]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function createNotificationForGuest()
    {
        try {
            DB::beginTransaction();

            // check if the notification already exists
            if ($this->guestAlreadyNotified()) {
                DB::commit();
                $this->dispatchWarningNotification(__('front/homePage.The notification has been added successfully'));
                return;
            }

            $notification = BackToStockNotification::create([
                'phone' => $this->phone,
            ]);

            $this->item->backToStockNotifications()->syncWithoutDetaching([$notification->id]);

            DB::commit();

            $this->dispatchSuccessNotification(__('front/homePage.The notification has been added successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function dispatchSuccessNotification(string $message): void
    {
        $this->dispatch(
            'swalDone',
            text: $message,
            icon: 'success'
        );
    }

    protected function dispatchErrorNotification(string $message): void
    {
        $this->dispatch(
            'swalDone',
            text: $message,
            icon: 'error'
        );
    }

    protected function dispatchWarningNotification(string $message): void
    {
        $this->dispatch(
            'swalDone',
            text: $message,
            icon: 'warning'
        );
    }
}
