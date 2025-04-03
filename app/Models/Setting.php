<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'back_pagination',
        'front_pagination',
        'points_conversion_rate',
        'points_expiry',
        'return_period',
        'last_box_name',
        'last_box_quantity',
        'new_arrival_name',
        'new_arrival_period',
        'max_price_offer_name',
        'max_price_offer',
        'whatsapp_number',
        'facebook_page_name',
        'youtube_channel_name',
        'instagram_page_name',
        'tiktok_page_name',
        'whatsapp_group_invitation_code',
    ];

    public $translatable = [
        'last_box_name',
        'new_arrival_name',
        'max_price_offer_name',
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
