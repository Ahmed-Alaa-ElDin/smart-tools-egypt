<?php

namespace App\Services\Front\Communication;

use Illuminate\Support\Facades\Http;

class SMSService
{
    protected $username;
    protected $password;
    protected $senderId;

    public function __construct()
    {
        // Set your credentials
        $this->username = env('SMS_EG_USERNAME');
        $this->password = env('SMS_EG_PASSWORD');
        $this->senderId = env('SMS_EG_SENDER_ID');
    }

    public function sendSMS($mobileNumber, $message)
    {
        $response = Http::asForm()->post('https://smssmartegypt.com/sms/api/', [
            'username' => $this->username,
            'password' => $this->password,
            'sendername' => $this->senderId,
            'mobiles' => $mobileNumber,
            'message' => $message,
        ]);

        // Check if the request was successful
        if ($response->successful()) {
            return $response->json();
        }

        return $response->json();
    }
}
