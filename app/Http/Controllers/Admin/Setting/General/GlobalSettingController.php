<?php

namespace App\Http\Controllers\Admin\Setting\General;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GlobalSettingController  extends Controller
{
    public function edit()
    {
        $settings = Setting::first();

        return view('admin.setting.general.global-settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        // Validate data
        $request->validate([
            'back_pagination' => 'required|in:5,10,25,50,100',
            'front_pagination' => 'required|integer',
            'points_conversion_rate' => 'required|numeric',
            'points_expiry' => 'required|integer',
            'return_period' => 'required|integer',
            'last_box_name_ar' => 'required|string',
            'last_box_name_en' => 'nullable|string',
            'last_box_quantity' => 'nullable|integer',
            'new_arrival_name_ar' => 'required|string',
            'new_arrival_name_en' => 'required|string',
            'new_arrival_period' => 'required|integer',
            'max_price_offer_name_ar' => 'required|string',
            'max_price_offer_name_en' => 'required|string',
            'max_price_offer' => 'required|numeric',
            'whatsapp_number' => 'nullable|numeric',
            'facebook_page_name' => 'nullable|string',
            'youtube_channel_name' => 'nullable|string',
            'instagram_page_name' => 'nullable|string',
            'tiktok_page_name' => 'nullable|string',
            'whatsapp_group_invitation_code' => 'nullable|string',
        ]);

        // Update settings
        $settings = Setting::first();

        $settings->update([
            'back_pagination' => $request->back_pagination,
            'front_pagination' => $request->front_pagination,
            'points_conversion_rate' => $request->points_conversion_rate,
            'points_expiry' => $request->points_expiry,
            'return_period' => $request->return_period,
            'last_box_name' => [
                'ar' => $request->last_box_name_ar,
                'en' => $request->last_box_name_en,
            ],
            'last_box_quantity' => $request->last_box_quantity,
            'new_arrival_name' => [
                'ar' => $request->new_arrival_name_ar,
                'en' => $request->new_arrival_name_en,
            ],
            'new_arrival_period' => $request->new_arrival_period,
            'max_price_offer_name' => [
                'ar' => $request->max_price_offer_name_ar,
                'en' => $request->max_price_offer_name_en,
            ],
            'max_price_offer' => $request->max_price_offer,
            'whatsapp_number' => $request->whatsapp_number,
            'facebook_page_name' => $request->facebook_page_name,
            'youtube_channel_name' => $request->youtube_channel_name,
            'instagram_page_name' => $request->instagram_page_name,
            'tiktok_page_name' => $request->tiktok_page_name,
            'whatsapp_group_invitation_code' => $request->whatsapp_group_invitation_code,
        ]);

        // Redirect to the settings page with a success message
        return redirect()->route('admin.setting.general')->with('success', __('admin/sitePages.Settings updated successfully'));
    }
}
