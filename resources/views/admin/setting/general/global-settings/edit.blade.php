@extends('layouts.admin.admin', ['activeSection' => 'Site Control', 'activePage' => '', 'titlePage' => __('admin/sitePages.Global Settings')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary">
                        <a href="{{ route('admin.dashboard') }}">
                            {{ __('admin/sitePages.Dashboard') }}
                        </a>
                    </li>

                    <li class="breadcrumb-item hover:text-primary">
                        <a href="{{ route('admin.setting.general') }}">
                            {{ __('admin/sitePages.General Settings') }}
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('admin/sitePages.Global Settings') }}
                    </li>
                </ol>
            </nav>

            <section class="row">
                <div class="col-md-12">

                    {{-- Card --}}
                    <div class="card">

                        {{-- Card Head --}}
                        <div class="card-header card-header-primary">
                            <div class="flex justify-between items-center">
                                <div class=" ltr:text-left rtl:text-right font-bold self-center text-gray-100">
                                    <p class="text-center">
                                        {{ __('admin/sitePages.Here you can edit the global settings of the website') }}
                                    </p>
                                </div>

                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden py-5">

                            {{-- Form --}}
                            <form action="{{ route('admin.setting.general.global-settings.update') }}" method="POST"
                                class="mt-2" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="flex flex-col gap-1 justify-center items-center">
                                    {{-- Pagination : Start --}}
                                    <div class="grid grid-cols-12 gap-3 justify-between mb-3 w-full bg-gray-100 p-4 rounded shadow">
                                        <div class="col-span-12">
                                            <h3 class="text-lg font-bold text-black mb-2">
                                                {{ __('admin/sitePages.Pagination') }}
                                            </h3>
                                        </div>

                                        {{-- Admin Pagination --}}
                                        <div class="col-span-4 md:col-span-3">
                                            <label for="back_pagination" class="font-bold text-xs text-gray-700">
                                                {{ __('admin/sitePages.Admin Pagination') }}
                                            </label>
                                            <select name="back_pagination" id="back_pagination"
                                                class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('back_pagination') border-red-900 border-2 @enderror">
                                                <option value="5" @if (old('back_pagination', $settings->back_pagination) == 5) selected @endif>
                                                    5
                                                </option>
                                                <option value="10" @if (old('back_pagination', $settings->back_pagination) == 10) selected @endif>
                                                    10
                                                </option>
                                                <option value="25" @if (old('back_pagination', $settings->back_pagination) == 25) selected @endif>
                                                    25
                                                </option>
                                                <option value="50" @if (old('back_pagination', $settings->back_pagination) == 50) selected @endif>
                                                    50
                                                </option>
                                                <option value="100" @if (old('back_pagination', $settings->back_pagination) == 100) selected @endif>
                                                    100
                                                </option>
                                            </select>
                                            @error('back_pagination')
                                                <label id="pagination-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                    for="back_pagination">{{ $message }}</label>
                                            @enderror
                                        </div>

                                        {{-- Front Pagination --}}
                                        <div class="col-span-4 md:col-span-3">
                                            <label for="front_pagination" class="font-bold text-xs text-gray-700">
                                                {{ __('admin/sitePages.User Pagination') }}
                                            </label>
                                            <input type="number" name="front_pagination" id="front_pagination"
                                                dir="ltr"
                                                class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('front_pagination') border-red-900 border-2 @enderror"
                                                value="{{ old('front_pagination', $settings->front_pagination) }}">
                                            @error('front_pagination')
                                                <label id="pagination-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                    for="front_pagination">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- Pagination : End --}}

                                    {{-- Points : Start --}}
                                    <div class="grid grid-cols-12 gap-3 justify-between mb-3 w-full bg-red-100 p-4 rounded shadow">
                                        <div class="col-span-12">
                                            <h3 class="text-lg font-bold text-black mb-2">
                                                {{ __('admin/sitePages.Points') }}
                                            </h3>
                                        </div>

                                        {{-- Points Conversion Rate --}}
                                        <div class="col-span-4 md:col-span-3">
                                            <label for="points_conversion_rate" class="font-bold text-xs text-gray-700">
                                                {{ __('admin/sitePages.Points Conversion Rate') }}
                                            </label>
                                            <input type="number" name="points_conversion_rate" id="points_conversion_rate"
                                                dir="ltr" step="0.01"
                                                class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('points_conversion_rate') border-red-900 border-2 @enderror"
                                                value="{{ old('points_conversion_rate', $settings->points_conversion_rate) }}">
                                            @error('points_conversion_rate')
                                                <label id="points_conversion_rate-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                    for="points_conversion_rate">{{ $message }}</label>
                                            @enderror
                                        </div>

                                        {{-- Points Expiry --}}
                                        <div class="col-span-4 md:col-span-3">
                                            <label for="points_expiry" class="font-bold text-xs text-gray-700">
                                                {{ __('admin/sitePages.Points Expiry') }}
                                            </label>
                                            <input type="number" name="points_expiry" id="points_expiry" dir="ltr"
                                                class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('points_expiry') border-red-900 border-2 @enderror"
                                                value="{{ old('points_expiry', $settings->points_expiry) }}">
                                            @error('points_expiry')
                                                <label id="points_expiry-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                    for="points_expiry">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- Points : End --}}

                                    {{-- Return Policy : Start --}}
                                    <div class="grid grid-cols-12 gap-3 justify-between mb-3 w-full bg-gray-100 p-4 rounded shadow">
                                        <div class="col-span-12">
                                            <h3 class="text-lg font-bold text-black mb-2">
                                                {{ __('admin/sitePages.Return Policy') }}
                                            </h3>
                                        </div>

                                        {{-- Return Period --}}
                                        <div class="col-span-4 md:col-span-3">
                                            <label for="return_period" class="font-bold text-xs text-gray-700">
                                                {{ __('admin/sitePages.Return Period') }}
                                            </label>
                                            <input type="number" name="return_period" id="return_period" dir="ltr"
                                                class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('return_period') border-red-900 border-2 @enderror"
                                                value="{{ old('return_period', $settings->return_period) }}">
                                            @error('return_period')
                                                <label id="return_period-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                    for="return_period">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- Return Policy : End --}}

                                    {{-- Allow To Open Package Price : Start --}}
                                    <div class="grid grid-cols-12 gap-3 justify-between mb-3 w-full bg-red-100 p-4 rounded shadow">
                                        <div class="col-span-12">
                                            <h3 class="text-lg font-bold text-black mb-2">
                                                {{ __('admin/sitePages.Allow To Open Package Price') }}
                                            </h3>
                                        </div>

                                        {{-- Allow To Open Package Price --}}
                                        <div class="col-span-4 md:col-span-3">
                                            <label for="allow_to_open_package_price" class="font-bold text-xs text-gray-700">
                                                {{ __('admin/sitePages.Allow To Open Package Price') }}
                                            </label>
                                            <input type="number" name="allow_to_open_package_price" id="allow_to_open_package_price" dir="ltr"
                                                class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('allow_to_open_package_price') border-red-900 border-2 @enderror"
                                                value="{{ old('allow_to_open_package_price', $settings->allow_to_open_package_price) }}">
                                            @error('allow_to_open_package_price')
                                                <label id="allow_to_open_package_price-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                    for="allow_to_open_package_price">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- Allow To Open Package Price : End --}}

                                    {{-- Special Offers : Start --}}
                                    <div class="grid grid-cols-12 gap-3 justify-between mb-3 w-full bg-gray-100 p-4 rounded shadow">
                                        <div class="col-span-12">
                                            <h3 class="text-lg font-bold text-black mb-2">
                                                {{ __('admin/sitePages.Special Offers') }}
                                            </h3>
                                        </div>

                                        {{-- Last Box Offers --}}
                                        <div class="col-span-12 grid grid-cols-12 gap-3">
                                            <h3 class="col-span-12 text-md font-bold text-gray-800">
                                                {{ __('admin/sitePages.Last Box Offers') }}
                                            </h3>

                                            <div class="col-span-6 lg:col-span-5">
                                                <label for="last_box_name_ar" class="font-bold text-xs text-gray-700">
                                                    {{ __('admin/sitePages.Name (Ar)') }}
                                                </label>

                                                <input type="text" name="last_box_name_ar" id="last_box_name_ar"
                                                    dir="rtl"
                                                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('last_box_name_ar') border-red-900 border-2 @enderror"
                                                    value="{{ old('last_box_name_ar', $settings->getTranslation('last_box_name', 'ar')) }}">
                                                @error('last_box_name_ar')
                                                    <label id="last_box_name_ar-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                        for="last_box_name_ar">{{ $message }}</label>
                                                @enderror
                                            </div>

                                            <div class="col-span-6 lg:col-span-5">
                                                <label for="last_box_name_en" class="font-bold text-xs text-gray-700">
                                                    {{ __('admin/sitePages.Name (En)') }}
                                                </label>

                                                <input type="text" name="last_box_name_en" id="last_box_name_en"
                                                    dir="ltr"
                                                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('last_box_name_en') border-red-900 border-2 @enderror"
                                                    value="{{ old('last_box_name_en', $settings->getTranslation('last_box_name', 'en')) }}">
                                                @error('last_box_name_en')
                                                    <label id="last_box_name_en-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                        for="last_box_name_en">{{ $message }}</label>
                                                @enderror
                                            </div>

                                            <div class="col-span-4 lg:col-span-2">
                                                <label for="last_box_quantity" class="font-bold text-xs text-gray-700">
                                                    {{ __('admin/sitePages.Last Box Quantity') }}
                                                </label>

                                                <input type="number" name="last_box_quantity" id="last_box_quantity"
                                                    dir="ltr"
                                                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('last_box_quantity') border-red-900 border-2 @enderror"
                                                    value="{{ old('last_box_quantity', $settings->last_box_quantity) }}">
                                                @error('last_box_quantity')
                                                    <label id="last_box_quantity-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                        for="last_box_quantity">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>

                                        <hr class="col-span-12 w-3/4 m-auto">

                                        {{-- New Arrival Offers --}}
                                        <div class="col-span-12 grid grid-cols-12 gap-3">
                                            <h3 class="col-span-12 text-md font-bold text-gray-800">
                                                {{ __('admin/sitePages.New Arrival Offers') }}
                                            </h3>

                                            <div class="col-span-6 lg:col-span-5">
                                                <label for="new_arrival_name_ar" class="font-bold text-xs text-gray-700">
                                                    {{ __('admin/sitePages.Name (Ar)') }}
                                                </label>

                                                <input type="text" name="new_arrival_name_ar" id="new_arrival_name_ar"
                                                    dir="rtl"
                                                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('new_arrival_name_ar') border-red-900 border-2 @enderror"
                                                    value="{{ old('new_arrival_name_ar', $settings->getTranslation('new_arrival_name', 'ar')) }}">
                                                @error('new_arrival_name_ar')
                                                    <label id="new_arrival_name_ar-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                        for="new_arrival_name_ar">{{ $message }}</label>
                                                @enderror
                                            </div>

                                            <div class="col-span-6 lg:col-span-5">
                                                <label for="new_arrival_name_en" class="font-bold text-xs text-gray-700">
                                                    {{ __('admin/sitePages.Name (En)') }}
                                                </label>

                                                <input type="text" name="new_arrival_name_en" id="new_arrival_name_en"
                                                    dir="ltr"
                                                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('new_arrival_name_en') border-red-900 border-2 @enderror"
                                                    value="{{ old('new_arrival_name_en', $settings->getTranslation('new_arrival_name', 'en')) }}">
                                                @error('new_arrival_name_en')
                                                    <label id="new_arrival_name_en-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                        for="new_arrival_name_en">{{ $message }}</label>
                                                @enderror
                                            </div>

                                            <div class="col-span-4 lg:col-span-2">
                                                <label for="new_arrival_period" class="font-bold text-xs text-gray-700">
                                                    {{ __('admin/sitePages.New Arrival Period') }}
                                                </label>

                                                <input type="number" name="new_arrival_period" id="new_arrival_period"
                                                    dir="ltr"
                                                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('new_arrival_period') border-red-900 border-2 @enderror"
                                                    value="{{ old('new_arrival_period', $settings->new_arrival_period) }}">
                                                @error('new_arrival_period')
                                                    <label id="new_arrival_period-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                        for="new_arrival_period">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>

                                        <hr class="col-span-12 w-3/4 m-auto">

                                        {{-- Max Price Offers --}}
                                        <div class="col-span-12 grid grid-cols-12 gap-3">
                                            <h3 class="col-span-12 text-md font-bold text-gray-800">
                                                {{ __('admin/sitePages.Max Price Offers') }}
                                            </h3>

                                            <div class="col-span-6 lg:col-span-5">
                                                <label for="max_price_offer_name_ar" class="font-bold text-xs text-gray-700">
                                                    {{ __('admin/sitePages.Name (Ar)') }}
                                                </label>

                                                <input type="text" name="max_price_offer_name_ar"
                                                    id="max_price_offer_name_ar" dir="rtl"
                                                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('max_price_offer_name_ar') border-red-900 border-2 @enderror"
                                                    value="{{ old('max_price_offer_name_ar', $settings->getTranslation('max_price_offer_name', 'ar')) }}">
                                                @error('max_price_offer_name_ar')
                                                    <label id="max_price_offer_name_ar-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                        for="max_price_offer_name_ar">{{ $message }}</label>
                                                @enderror
                                            </div>

                                            <div class="col-span-6 lg:col-span-5">
                                                <label for="max_price_offer_name_en" class="font-bold text-xs text-gray-700">
                                                    {{ __('admin/sitePages.Name (En)') }}
                                                </label>

                                                <input type="text" name="max_price_offer_name_en"
                                                    id="max_price_offer_name_en" dir="ltr"
                                                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('max_price_offer_name_en') border-red-900 border-2 @enderror"
                                                    value="{{ old('max_price_offer_name_en', $settings->getTranslation('max_price_offer_name', 'en')) }}">
                                                @error('max_price_offer_name_en')
                                                    <label id="max_price_offer_name_en-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                        for="max_price_offer_name_en">{{ $message }}</label>
                                                @enderror
                                            </div>

                                            <div class="col-span-4 lg:col-span-2">
                                                <label for="max_price_offer" class="font-bold text-xs text-gray-700">
                                                    {{ __('admin/sitePages.Max Price') }}
                                                </label>

                                                <input type="number" name="max_price_offer" id="max_price_offer"
                                                    step="0.1" min="0" dir="ltr"
                                                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('max_price_offer') border-red-900 border-2 @enderror"
                                                    value="{{ old('max_price_offer', $settings->max_price_offer) }}">
                                                @error('max_price_offer')
                                                    <label id="max_price_offer-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                        for="max_price_offer">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Special Offers : End --}}

                                    {{-- Contact Information : Start --}}
                                    <div class="grid grid-cols-12 gap-3 justify-between mb-3 w-full bg-red-100 p-4 rounded shadow">
                                        <div class="col-span-12">
                                            <h3 class="text-lg font-bold text-black mb-2">
                                                {{ __('admin/sitePages.Contact Information') }}
                                            </h3>
                                        </div>

                                        {{-- Whatsapp Number --}}
                                        <div class="col-span-4 md:col-span-3">
                                            <label for="whatsapp_number" class="font-bold text-xs text-gray-700">
                                                {{ __('admin/sitePages.Whatsapp Number') }}
                                            </label>
                                            <input type="text" name="whatsapp_number" id="whatsapp_number"
                                                dir="ltr"
                                                class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('whatsapp_number') border-red-900 border-2 @enderror"
                                                value="{{ old('whatsapp_number', $settings->whatsapp_number) }}">
                                            @error('whatsapp_number')
                                                <label id="whatsapp_number-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                    for="whatsapp_number">{{ $message }}</label>
                                            @enderror
                                        </div>

                                        {{-- Facebook --}}
                                        <div class="col-span-4 md:col-span-3">
                                            <label for="facebook_page_name" class="font-bold text-xs text-gray-700">
                                                {{ __('admin/sitePages.Facebook Page Name') }}
                                            </label>
                                            <input type="text" name="facebook_page_name" id="facebook_page_name" dir="ltr"
                                                class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('facebook_page_name') border-red-900 border-2 @enderror"
                                                value="{{ old('facebook_page_name', $settings->facebook_page_name) }}">
                                            @error('facebook_page_name')
                                                <label id="facebook_page_name-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                    for="facebook_page_name">{{ $message }}</label>
                                            @enderror
                                        </div>

                                        {{-- YouTube Channel --}}
                                        <div class="col-span-4 md:col-span-3">
                                            <label for="youtube_channel_name" class="font-bold text-xs text-gray-700">
                                                {{ __('admin/sitePages.Youtube Channel Name') }}
                                            </label>
                                            <input type="text" name="youtube_channel_name" id="youtube_channel_name" dir="ltr"
                                                class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('youtube_channel_name') border-red-900 border-2 @enderror"
                                                value="{{ old('youtube_channel_name', $settings->youtube_channel_name) }}">
                                            @error('youtube_channel_name')
                                                <label id="youtube_channel_name-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                    for="youtube_channel_name">{{ $message }}</label>
                                            @enderror
                                        </div>

                                        {{-- Instagram Page --}}
                                        <div class="col-span-4 md:col-span-3">
                                            <label for="instagram_page_name" class="font-bold text-xs text-gray-700">
                                                {{ __('admin/sitePages.Instagram Page Name') }}
                                            </label>
                                            <input type="text" name="instagram_page_name" id="instagram_page_name" dir="ltr"
                                                class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('instagram_page_name') border-red-900 border-2 @enderror"
                                                value="{{ old('instagram_page_name', $settings->instagram_page_name) }}">
                                            @error('instagram_page_name')
                                                <label id="instagram_page_name-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                    for="instagram_page_name">{{ $message }}</label>
                                            @enderror
                                        </div>

                                        {{-- TikTok Page --}}
                                        <div class="col-span-4 md:col-span-3">
                                            <label for="tiktok_page_name" class="font-bold text-xs text-gray-700">
                                                {{ __('admin/sitePages.TikTok Page Name') }}
                                            </label>
                                            <input type="text" name="tiktok_page_name" id="tiktok_page_name" dir="ltr"
                                                class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('tiktok_page_name') border-red-900 border-2 @enderror"
                                                value="{{ old('tiktok_page_name', $settings->tiktok_page_name) }}">
                                            @error('tiktok_page_name')
                                                <label id="tiktok_page_name-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                    for="tiktok_page_name">{{ $message }}</label>
                                            @enderror
                                        </div>

                                        {{-- Whatsapp Group Invitation Code --}}
                                        <div class="col-span-4 md:col-span-3">
                                            <label for="whatsapp_group_invitation_code" class="font-bold text-xs text-gray-700">
                                                {{ __('admin/sitePages.Whatsapp Group Invitation Code') }}
                                            </label>
                                            <input type="text" name="whatsapp_group_invitation_code" id="whatsapp_group_invitation_code" dir="ltr"
                                                class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('whatsapp_group_invitation_code') border-red-900 border-2 @enderror"
                                                value="{{ old('whatsapp_group_invitation_code', $settings->whatsapp_group_invitation_code) }}">
                                            @error('whatsapp_group_invitation_code')
                                                <label id="whatsapp_group_invitation_code-error" class="col-span-12 mt-2 bg-red-700 rounded text-white shadow px-3 py-1"
                                                    for="whatsapp_group_invitation_code">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- Contact Information : End --}}
                                </div>

                                {{-- Form Submit Button --}}
                                <div class="flex justify-around">
                                    {{-- Save --}}
                                    <button type="submit"
                                        class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/usersPages.Update') }}</button>

                                    {{-- Back --}}
                                    <a href="{{ route('admin.setting.general') }}"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/usersPages.Back') }}</a>
                                </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
