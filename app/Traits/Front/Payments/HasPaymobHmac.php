<?php

namespace App\Traits\Front\Payments;

trait HasPaymobHmac
{
    // Back Request
    public function validateHmacProcessed(array $data): bool
    {
        $hmac = $data['hmac'] ?? '';

        if (isset($data["transaction"])) {
            $data = $data["transaction"];

            $array = [
                'amount_cents',
                'created_at',
                'currency',
                'error_occured',
                'has_parent_transaction',
                'id',
                'integration_id',
                'is_3d_secure',
                'is_auth',
                'is_capture',
                'is_refunded',
                'is_standalone_payment',
                'is_voided',
                'order.id',
                'owner',
                'pending',
                'source_data.pan',
                'source_data.sub_type',
                'source_data.type',
                'success',
                'receipt'
            ];

            $concat_data = '';

            foreach ($array as $key) {
                if (isset($data[$key])) {
                    $concat_data .= $data[$key];
                }
            }

            $secret = env('PAYMOB_HMAC');

            $generated_hmac = hash_hmac('SHA512', $concat_data, $secret);

            return $generated_hmac == $hmac;
        }

        return false;
    }
    
    // Front Request
    public function validateHmacResponse(array $data): bool
    {
        $hmac = $data['hmac'] ?? '';

        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success'
        ];

        $concat_data = '';

        foreach ($array as $key) {
            if (isset($data[$key])) {
                $concat_data .= $data[$key];
            }
        }

        $secret = env('PAYMOB_HMAC');

        $generated_hmac = hash_hmac('SHA512', $concat_data, $secret);

        return $generated_hmac == $hmac;
    }
}
