<?php

namespace App\Services\Front\meta;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Services\Front\Communication\SMSService;
use FacebookAds\ParamBuilder;

class MetaPixelService
{
    protected $pixelId;
    protected $accessToken;
    protected $apiVersion;
    protected $paramBuilder;

    public function __construct()
    {
        $this->pixelId = config('services.meta_pixel.id');
        $this->accessToken = config('services.meta_pixel.access_token');
        $this->apiVersion = config('services.meta_pixel.api_version');

        // Initialize Facebook ParamBuilder with the e-commerce domain
        $this->paramBuilder = new ParamBuilder(['smarttoolsegypt.com']);
        
        // Extract and process parameters automatically from the Laravel Request context
        $this->paramBuilder->processRequest(
            request()->getHost(),
            request()->query->all(),
            request()->cookies->all(),
            request()->headers->get('referer'),
            request()->headers->get('x-forwarded-for'),
            request()->ip()
        );
    }

    public function sendEvent(string $eventName, array $userData = [], array $customData = [], string $eventId = "")
    {
        try {
            if (empty($eventId)) {
                $eventId = $this->generateEventId();
            }

            $endpoint = "https://graph.facebook.com/{$this->apiVersion}/{$this->pixelId}/events";
            $eventTime = time();
            $finalUserData = $this->getUserData($userData);

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

    public function getUserData(array $userData = [])
    {
        $user = auth()->check() ? auth()->user() : null;
        $address = $user?->defaultAddress->first();

        // Standardize base user data using ParamBuilder's automated normalization & SHA-256 hashing
        $finalUserData = [
            'em' => $user?->email ? $this->paramBuilder->getNormalizedAndHashedPII($user->email, 'email') : null,
            'fn' => $user?->f_name ? $this->paramBuilder->getNormalizedAndHashedPII($user->f_name, 'first_name') : null,
            'ln' => $user?->l_name ? $this->paramBuilder->getNormalizedAndHashedPII($user->l_name, 'last_name') : null,
            'ge' => $user ? $this->paramBuilder->getNormalizedAndHashedPII(($user->gender === 0 ? 'm' : 'f'), 'gender') : null,
            'db' => $user?->birth_date ? $this->paramBuilder->getNormalizedAndHashedPII(\Carbon\Carbon::parse($user->birth_date)->format('Ymd'), 'date_of_birth') : null,
            'ct' => $address?->city?->name ? $this->paramBuilder->getNormalizedAndHashedPII($address->city->name, 'city') : null,
            'st' => $address?->governorate?->name ? $this->paramBuilder->getNormalizedAndHashedPII($address->governorate->name, 'state') : null,
            'country' => $this->paramBuilder->getNormalizedAndHashedPII('eg', 'country'),
            'external_id' => $user?->id ? $this->paramBuilder->getNormalizedAndHashedPII((string) $user->id, 'external_id') : null,
        ];

        // Process phones list
        $phones = $user?->phones?->map(fn($p) => preg_replace('/\D/', '', $p->phone))->toArray() ?? [];
        $hashedPhones = [];
        foreach ($phones as $phone) {
            if (!empty($phone)) {
                $hashedPhones[] = $this->paramBuilder->getNormalizedAndHashedPII($phone, 'phone');
            }
        }
        if (!empty($hashedPhones)) {
            $finalUserData['ph'] = $hashedPhones;
        }

        // Process any custom dynamic PII passed into this method
        foreach ($userData as $key => $value) {
            if (!empty($value)) {
                $finalUserData[$key] = $this->paramBuilder->getNormalizedAndHashedPII($value, $this->mapFieldToPIIType($key));
            }
        }

        // Merge with auto-extracted request params
        return array_merge(array_filter($finalUserData), [
            'client_ip_address' => $this->paramBuilder->getClientIpAddress(),
            'client_user_agent' => request()->userAgent(),
            'fbc' => $this->paramBuilder->getFbc(),
            'fbp' => $this->paramBuilder->getFbp(),
            'page_id' => config('services.meta_pixel.page_id'),
        ]);
    }

    /**
     * Map Meta payload keys back to ParamBuilder PII types
     */
    private function mapFieldToPIIType(string $key): string
    {
        return [
            'em' => 'email',
            'ph' => 'phone',
            'fn' => 'first_name',
            'ln' => 'last_name',
            'db' => 'date_of_birth',
            'ge' => 'gender',
            'ct' => 'city',
            'st' => 'state',
            'zp' => 'zip_code',
            'country' => 'country',
            'external_id' => 'external_id',
        ][$key] ?? 'external_id';
    }

    public function generateEventId()
    {
        return Str::uuid();
    }

    /**
     * Retrieve any first-party cookies recommended to be saved
     */
    public function getCookiesToSet()
    {
        return $this->paramBuilder->getCookiesToSet();
    }
}
