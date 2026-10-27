<?php

namespace App\Services\Front\Deliveries;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Interfaces\Front\Deliveries\DeliveryInterface;
use Throwable;

class Bosta implements DeliveryInterface
{
    public function __construct() {}

    public function createDelivery(Order $order): array
    {
        $order->loadMissing([
            'address.city',
            'address.governorate',
            'user.phones',
            'transactions',
        ]);

        $validationErrors = $this->validateOrderData($order);

        if ($validationErrors) {
            Log::warning('Bosta delivery was not created because the order data is incomplete.', [
                'order_id' => $order->id,
                'errors' => $validationErrors,
            ]);

            return $this->failed(implode(' ', $validationErrors));
        }

        // Get the unpaid transactions
        $unpaidTransactions = $order->transactions()->where('payment_status_id', PaymentStatus::Pending->value)->get();

        // Calculate the payment amount
        $paymentAmount = $unpaidTransactions->whereIn('payment_method_id', [PaymentMethod::Cash->value, PaymentMethod::Flash->value, PaymentMethod::ElectronicWallet->value])->sum('payment_amount') ?? 0;

        // Create the order data
        $orderData = [
            "type"                  =>      10,
            "specs"                 => [
                "size"              =>      "SMALL",
                "packageType"       =>      "Parcel",
                "packageDetails"    =>      [
                    "itemsCount"    => $order->num_of_items,
                    "description"   => $order->package_desc,
                ],
                // "weight"            =>      $order->total_weight,
            ],
            "notes"                 =>      $order->notes . ($order->user->phones->where('default', 0)->count() > 1 ? " - " . implode(' - ', $order->user->phones->where('default', 0)->pluck('phone')->toArray()) : '') . " - منتجات قابلة للكسر - برجاء الاتصال بوقت كافى قبل التوصيل والتواصل من خلال الواتس فى حالة ضعف الشبكة",
            "cod"                   =>      $paymentAmount ? ceil($paymentAmount) : 0.00,
            "dropOffAddress"        =>      [
                "city"              =>      $order->address->governorate->getTranslation('name', 'en'),
                "districtId"        =>      $order->address->city->bosta_id,
                "firstLine"         =>      $order->address->governorate->getTranslation('name', 'ar') . " - " . $order->address->city->getTranslation('name', 'ar') . " - " . str_pad($order->address->details ?? "", 5, '-'),
                "secondLine"        =>      $order->address->landmarks ?? '',
                "isWorkAddress"     =>      true,
            ],
            "receiver"              =>      [
                "firstName"         =>      $order->user->f_name,
                "lastName"          =>      $order->user->l_name ?? $order->user->f_name,
                "phone"             =>      $order->user->phones->where('default', 1)->first()->phone,
                "secondPhone"       =>      $order->user->phones->where('default', 0)->count() ? $order->user->phones->where('default', 0)->first()->phone : '',
                "email"             =>      $order->user->email != "" ? $order->user->email : "info@smarttoolsegypt.com",
            ],
            "businessReference"     =>      $order->id,
            "allowToOpenPackage"    =>      $order->allow_opening ? true : false,
            "webhookUrl"            =>      env('BOSTA_WEBHOOK_URL', "https://www.smarttoolsegypt.com/api/orders/update-status"),
        ];

        // Send the request to Bosta API
        try {
            $response = Http::timeout(20)->withHeaders([
                'Authorization'     =>  env('BOSTA_API_KEY'),
                'Content-Type'      =>  'application/json',
                'Accept'            =>  'application/json'
            ])->post(env('BOSTA_NEW_DELIVERY_URL'), $orderData);

            $decodedBostaResponse = $response->json();
            $deliveryData = data_get($decodedBostaResponse, 'data');

            if ($response->successful() && is_array($deliveryData) && filled($deliveryData['trackingNumber'] ?? null) && filled($deliveryData['_id'] ?? null)) {

                // update order in database
                $order->update([
                    'tracking_number' => $deliveryData['trackingNumber'],
                    'order_delivery_id' => $deliveryData['_id'],
                ]);

                $order->statuses()->attach(OrderStatus::PickupRequested->value);

                return [
                    'status'    =>  true,
                    'data'      =>  $decodedBostaResponse,
                ];
            }

            $message = $this->responseMessage($decodedBostaResponse);

            Log::warning('Bosta rejected delivery creation.', [
                'order_id' => $order->id,
                'http_status' => $response->status(),
                'bosta_error' => $message,
                'bosta_response' => $decodedBostaResponse,
            ]);

            return $this->failed($message, $decodedBostaResponse);
        } catch (Throwable $exception) {
            report($exception);

            Log::error('Bosta delivery request failed before a response was received.', [
                'order_id' => $order->id,
                'exception' => $exception->getMessage(),
            ]);

            return $this->failed('Could not contact Bosta. Please try again.');
        }
    }

    private function validateOrderData(Order $order): array
    {
        $errors = [];

        if (!$order->address) {
            $errors[] = 'This order has no delivery address.';
        } elseif (!$order->address->city || blank($order->address->city->bosta_id)) {
            $errors[] = 'The address city is not mapped to a Bosta district.';
        } elseif (!$order->address->governorate) {
            $errors[] = 'The address has no governorate.';
        }

        if (!$order->user) {
            $errors[] = 'This order has no customer.';
        } elseif (!$order->user->phones->firstWhere('default', 1)) {
            $errors[] = 'The customer has no default phone number.';
        }

        return $errors;
    }

    private function responseMessage(mixed $response): string
    {
        $message = data_get($response, 'message')
            ?? data_get($response, 'error.message')
            ?? data_get($response, 'error')
            ?? data_get($response, 'errors.0.message')
            ?? data_get($response, 'errors.0');

        return is_string($message) && filled($message)
            ? $message
            : 'Bosta rejected the delivery request. See the application log for the response.';
    }

    private function failed(string $message, mixed $data = null): array
    {
        return [
            'status' => false,
            'message' => $message,
            'data' => $data,
        ];
    }

    public function getAWBs(array $deliveryIds, string $pageSize = "A6", string $language = "ar"): string
    {
        // Set the request options
        $options = [
            "ids" => implode(",", $deliveryIds),
            "requestedAwbType" => $pageSize,
            "lang" => $language
        ];

        // Send the request to Bosta API
        $response = Http::withHeaders([
            'Authorization'     =>  env('BOSTA_API_KEY'),
        ])->post('https://app.bosta.co/api/v2/deliveries/mass-awb', $options)->json();

        // Return the response
        return $response['data'] ?? "";
    }
}
