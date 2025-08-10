<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MetaPixelService
{
    protected $pixelId;
    protected $accessToken;
    protected $apiVersion;

    public function __construct()
    {
        $this->pixelId = config('services.meta_pixel.id');
        $this->accessToken = config('services.meta_pixel.access_token');
        $this->apiVersion = config('services.meta_pixel.api_version');
    }

    public function sendEvent(string $eventName, array $userData, array $customData = [])
    {
        $endpoint = "https://graph.facebook.com/{$this->apiVersion}/{$this->pixelId}/events";

        $payload = [
            'data' => [
                [
                    'event_name' => $eventName,
                    'event_time' => now()->timestamp,
                    'action_source' => 'website',
                    'user_data' => $this->hashUserData($userData),
                    'custom_data' => $customData,
                ]
            ],
            'access_token' => $this->accessToken,
        ];

        return Http::post($endpoint, $payload)->json();
    }

    private function hashUserData(array $data)
    {
        return collect($data)
            ->mapWithKeys(function ($value, $key) {
                return [$key => hash('sha256', strtolower(trim($value)))];
            })
            ->toArray();
    }
}
