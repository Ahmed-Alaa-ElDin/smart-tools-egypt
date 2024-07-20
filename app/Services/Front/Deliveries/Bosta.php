<?php

namespace App\Services\Front\Deliveries;

use Illuminate\Support\Facades\Http;
use App\Interfaces\Front\Deliveries\DeliveryInterface;

class Bosta implements DeliveryInterface
{
    public function __construct()
    {
    }

    public function getAWBs(array $deliveryIds, string $pageSize = "A4", string $language = "ar"): string
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
