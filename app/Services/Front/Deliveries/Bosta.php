<?php

namespace App\Services\Front\Deliveries;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Http;
use App\Interfaces\Front\Deliveries\DeliveryInterface;

class Bosta implements DeliveryInterface
{
    public function __construct() {}

    public function createDelivery(Order $order): array
    {
        // Get the unpaid transactions
        $unpaidTransactions = $order->transactions()->where('payment_status_id', PaymentStatus::Pending->value)->get();

        // Calculate the payment amount
        $paymentAmount = $unpaidTransactions->where('payment_method_id', PaymentMethod::Cash->value)->sum('payment_amount') ?? 0;

        // Create the order data
        $orderData = [
            "type"      =>      10,
            "specs" => [
                "size"              =>      "SMALL",
                "packageType"       =>      "Parcel",
                "packageDetails"    =>      [
                    "itemsCount"    => $order->num_of_items,
                    "description"   => $order->package_desc,
                ],
                // "weight"            =>      $order->total_weight,
            ],
            "notes"     =>      $order->notes . ($order->user->phones->where('default', 0)->count() > 1 ? " - " . implode(' - ', $order->user->phones->where('default', 0)->pluck('phone')->toArray()) : '') . " - منتجات قابلة للكسر - برجاء الاتصال بوقت كافى قبل التوصيل والتواصل من خلال الواتس فى حالة ضعف الشبكة",
            "cod"       =>      $paymentAmount ? ceil($paymentAmount) : 0.00,
            "dropOffAddress" => [
                "city"          =>      $order->address->governorate->getTranslation('name', 'en'),
                "districtId"    =>      $order->address->city->bosta_id,
                "firstLine"     =>      str_pad($order->address->details ?? $order->address->city->getTranslation('name', 'en'), 5, '-'),
                "secondLine"    =>      $order->address->landmarks ?? '',
                "isWorkAddress" =>      true,
            ],
            "receiver" => [
                "firstName"     =>      $order->user->f_name,
                "lastName"      =>      $order->user->l_name ? $order->user->l_name : $order->user->f_name,
                "phone"         =>      $order->user->phones->where('default', 1)->first()->phone,
                "secondPhone"   =>      $order->user->phones->where('default', 0)->count() ? $order->user->phones->where('default', 0)->first()->phone : '',
                "email"         =>      $order->user->email != "" ? $order->user->email : "info@smarttoolsegypt.com",
            ],
            "businessReference" => $order->id,
            "allowToOpenPackage" => $order->allow_opening ? true : false,
            "webhookUrl" => env('BOSTA_WEBHOOK_URL', "https://www.smarttoolsegypt.com/api/orders/update-status"),
        ];

        // Send the request to Bosta API
        $response = Http::withHeaders([
            'Authorization'     =>  env('BOSTA_API_KEY'),
            'Content-Type'      =>  'application/json',
            'Accept'            =>  'application/json'
        ])->post(env('BOSTA_NEW_DELIVERY_URL'), $orderData);

        $decodedBostaResponse = $response->json();

        if ($response->successful() && $decodedBostaResponse['data']) {

            // update order in database
            $order->update([
                'tracking_number' => $decodedBostaResponse['data']['trackingNumber'],
                'order_delivery_id' => $decodedBostaResponse['data']['_id'],
            ]);

            $order->statuses()->attach(OrderStatus::PickupRequested->value);

            return [
                'status'    =>  true,
                'data'      =>  $decodedBostaResponse,
            ];
        } else {
            return [
                'status'    =>  false,
                'data'      =>  $decodedBostaResponse,
            ];
        }
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
