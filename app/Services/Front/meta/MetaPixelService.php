<?php

namespace App\Services\Front\meta;

use Illuminate\Support\Str;
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

    public function sendEvent(string $eventName, array $userData = [], array $customData = [], string $eventId = "")
    {
        try {
            if (empty($eventId)) {
                $eventId = Str::uuid();
            }

            $endpoint = "https://graph.facebook.com/{$this->apiVersion}/{$this->pixelId}/events";

            $clientIp = request()->getClientIp();
            $userAgent = request()->userAgent();
            $fbc = $_COOKIE['_fbc'] ?? null;
            $fbp = $_COOKIE['_fbp'] ?? null;

            $user = auth()->check() ? auth()->user() : null;

            $hashedUserData = $this->hashUserData(array_merge($userData, [
                'em' => $user?->email,
                'ph' => $user?->phones?->pluck('phone')->toArray() ?? [],
                'fn' => $user?->f_name,
                'ln' => $user?->l_name,
                'ge' => $user ? ($user->gender === 0 ? 'm' : 'f') : null,
                'db' => $user?->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('Ymd') : null, //YYYYMMDD
                'country' => "eg",
                'external_id' => $user?->id,
            ]));

            $finalUserData = array_merge($hashedUserData, [
                'client_ip_address' => $clientIp,
                'client_user_agent' => $userAgent,
                'fbc' => $fbc,
                'fbp' => $fbp,
                'page_id' => config('services.meta_pixel.page_id'),
            ]);

            $payload = [
                'data' => [
                    [
                        'event_name' => $eventName,
                        'event_id' => $eventId,
                        'event_time' => now()->timestamp,
                        'creation_time' => now()->timestamp,
                        'action_source' => 'website',
                        'user_data' => $finalUserData,
                        'custom_data' => $customData,
                    ]
                ],
                'event_source_url' => request()->url(),
                'access_token' => $this->accessToken,
            ];

            return Http::post($endpoint, $payload)->json();
        } catch (\Exception $e) {
            return null;
        }
    }

    private function hashUserData(array $data)
    {
        return collect($data)
            ->mapWithKeys(function ($value, $key) {
                if (is_array($value)) {
                    return [$key => array_map(fn($v) => empty($v) ? '' : hash('sha256', strtolower(trim($v))), $value ?? [])];
                }
                return [$key => empty($value) ? '' : hash('sha256', strtolower(trim($value)))];
            })
            ->toArray();
    }
}
