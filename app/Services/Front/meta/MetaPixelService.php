<?php

namespace App\Services\Front\meta;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Services\Front\Communication\SMSService;

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
                $eventId = $this->generateEventId();
            }

            $endpoint = "https://graph.facebook.com/{$this->apiVersion}/{$this->pixelId}/events";

            $clientIp = request()->getClientIp();
            $userAgent = request()->userAgent();
            $fbc = request()->cookie('_fbc');
            $fbp = request()->cookie('_fbp');

            $eventTime = time();

            // Validate fbc timestamp (must not be older than 90 days or in the future)
            if ($fbc) {
                $fbcParts = explode('.', $fbc);
                if (count($fbcParts) >= 3 && is_numeric($fbcParts[2])) {
                    $fbcTimestamp = (int) $fbcParts[2];
                    // Older than 90 days
                    if (($eventTime - $fbcTimestamp) > (90 * 24 * 60 * 60)) {
                        $fbc = null;
                    }
                    // In the future relative to server time (cap to eventTime)
                    elseif ($fbcTimestamp > $eventTime) {
                        $fbcParts[2] = $eventTime;
                        $fbc = implode('.', $fbcParts);
                    }
                }
            }

            // Validate fbp timestamp (must not be older than 90 days or in the future)
            if ($fbp) {
                $fbpParts = explode('.', $fbp);
                if (count($fbpParts) >= 3 && is_numeric($fbpParts[2])) {
                    $fbpTimestamp = (int) $fbpParts[2];
                    // Older than 90 days
                    if (($eventTime - $fbpTimestamp) > (90 * 24 * 60 * 60)) {
                        $fbp = null;
                    }
                    // In the future relative to server time (cap to eventTime)
                    elseif ($fbpTimestamp > $eventTime) {
                        $fbpParts[2] = $eventTime;
                        $fbp = implode('.', $fbpParts);
                    }
                }
            }

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
                        'event_time' => $eventTime,
                        'action_source' => 'website',
                        'user_data' => $finalUserData,
                        'custom_data' => $customData,
                    ]
                ],
                'event_source_url' => request()->url(),
                'access_token' => $this->accessToken,
            ];

            if (Http::post($endpoint, $payload)->successful()) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            (new SMSService())->sendSMS('01111339306', $e->getMessage());
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

    public function generateEventId()
    {
        return Str::uuid();
    }
}
