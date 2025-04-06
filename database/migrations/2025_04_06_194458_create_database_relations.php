<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Collection Product Relations
        Schema::table('collection_product', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade')->onUpdate('cascade');
        });

        // Cities Relations
        Schema::table('cities', function (Blueprint $table) {
            $table->foreign('governorate_id')->references('id')->on('governorates')->onDelete('cascade')->onUpdate('cascade');
        });

        // Invoices Relations
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->nullOnDelete();
        });

        // Sections Relations
        Schema::table('sections', function (Blueprint $table) {
            $table->foreign('offer_id')->references('id')->on('offers')->onUpdate('cascade')->onDelete('cascade');
        });

        // Product Complemented Relations
        Schema::table('product_product_complemented', function (Blueprint $table) {
            $table->foreign('first_product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('second_product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });

        // Back to Stock Notifications
        Schema::table('back_to_stock_notifications', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->nullOnDelete();
        });

        // Order Status Relations
        Schema::table('order_status', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onUpdate('cascade')->onDelete('cascade');
        });

        // Collection Product Complemented
        Schema::table('collection_product_complemented', function (Blueprint $table) {
            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });

        // Collection Collection Related
        Schema::table('collection_collection_related', function (Blueprint $table) {
            $table->foreign('first_collection_id')->references('id')->on('collections')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('second_collection_id')->references('id')->on('collections')->onDelete('cascade')->onUpdate('cascade');
        });

        // Destinations Relations
        Schema::table('destinations', function (Blueprint $table) {
            $table->foreign('delivery_id')->references('id')->on('deliveries')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('zone_id')->references('id')->on('zones')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('governorate_id')->references('id')->on('governorates')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->nullOnDelete()->onUpdate('cascade');
        });

        // Collections Relations
        Schema::table('collections', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete()->onUpdate('cascade');
        });

        // Products Relations
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('brand_id')->references('id')->on('brands')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete()->onUpdate('cascade');
        });

        // Subslider Small Banners
        Schema::table('subslider_small_banners', function (Blueprint $table) {
            $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade')->onUpdate('cascade');
        });

        // Brands Relations
        Schema::table('brands', function (Blueprint $table) {
            $table->foreign('country_id')->references('id')->on('countries')->nullOnDelete()->onUpdate('cascade');
        });

        // Addresses Relations
        Schema::table('addresses', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('governorate_id')->references('id')->on('governorates')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
        });

        // Phones Relations
        Schema::table('phones', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        // Governorates Relations
        Schema::table('governorates', function (Blueprint $table) {
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade')->onUpdate('cascade');
        });

        // Main Slider Banners
        Schema::table('main_slider_banners', function (Blueprint $table) {
            $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade')->onUpdate('cascade');
        });

        // Back to Stock Notifiables
        Schema::table('back_to_stock_notifiables', function (Blueprint $table) {
            $table->foreign('notification_id')->references('id')->on('back_to_stock_notifications')->onUpdate('cascade')->onDelete('cascade');
        });

        // Sectionables Relations
        Schema::table('sectionables', function (Blueprint $table) {
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade')->onUpdate('cascade');
        });

        // Zones Relations
        Schema::table('zones', function (Blueprint $table) {
            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('cascade')->onUpdate('cascade');
        });

        // Offerables Relations
        Schema::table('offerables', function (Blueprint $table) {
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade')->onUpdate('cascade');
        });

        // Points Relations
        Schema::table('points', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->nullOnDelete()->onUpdate('cascade');
        });

        // Permission Tables Relations
        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->foreign(PermissionRegistrar::$pivotPermission)
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->foreign(PermissionRegistrar::$pivotRole)
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
        });

        Schema::table('role_has_permissions', function (Blueprint $table) {
            $table->foreign(PermissionRegistrar::$pivotPermission)
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign(PermissionRegistrar::$pivotRole)
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
        });

        // Subcategories Relations
        Schema::table('subcategories', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
        });

        // Invoice Requests Relations
        Schema::table('invoice_requests', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
        });

        // Product Product Related
        Schema::table('product_product_related', function (Blueprint $table) {
            $table->foreign('first_product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('second_product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });

        // Reviews Relations
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        // Orders Relations
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('coupon_id')->references('id')->on('coupons')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('address_id')->references('id')->on('addresses')->nullOnDelete()->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop all foreign keys in reverse order

        // Orders Relations
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['address_id']);
        });

        // Reviews Relations
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Product Product Related
        Schema::table('product_product_related', function (Blueprint $table) {
            $table->dropForeign(['first_product_id']);
            $table->dropForeign(['second_product_id']);
        });

        // Invoice Requests Relations
        Schema::table('invoice_requests', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['order_id']);
        });

        // Subcategories Relations
        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        // Permission Tables Relations
        Schema::table('role_has_permissions', function (Blueprint $table) {
            $table->dropForeign([PermissionRegistrar::$pivotPermission]);
            $table->dropForeign([PermissionRegistrar::$pivotRole]);
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropForeign([PermissionRegistrar::$pivotRole]);
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->dropForeign([PermissionRegistrar::$pivotPermission]);
        });

        // Points Relations
        Schema::table('points', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['order_id']);
        });

        // Offerables Relations
        Schema::table('offerables', function (Blueprint $table) {
            $table->dropForeign(['offer_id']);
        });

        // Zones Relations
        Schema::table('zones', function (Blueprint $table) {
            $table->dropForeign(['delivery_id']);
        });

        // Sectionables Relations
        Schema::table('sectionables', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
        });

        // Back to Stock Notifiables
        Schema::table('back_to_stock_notifiables', function (Blueprint $table) {
            $table->dropForeign(['notification_id']);
        });

        // Main Slider Banners
        Schema::table('main_slider_banners', function (Blueprint $table) {
            $table->dropForeign(['banner_id']);
        });

        // Governorates Relations
        Schema::table('governorates', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
        });

        // Phones Relations
        Schema::table('phones', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Addresses Relations
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['country_id']);
            $table->dropForeign(['governorate_id']);
            $table->dropForeign(['city_id']);
        });

        // Brands Relations
        Schema::table('brands', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
        });

        // Subslider Small Banners
        Schema::table('subslider_small_banners', function (Blueprint $table) {
            $table->dropForeign(['banner_id']);
        });

        // Products Relations
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['created_by']);
        });

        // Collections Relations
        Schema::table('collections', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });

        // Destinations Relations
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropForeign(['delivery_id']);
            $table->dropForeign(['zone_id']);
            $table->dropForeign(['country_id']);
            $table->dropForeign(['governorate_id']);
            $table->dropForeign(['city_id']);
        });

        // Collection Collection Related
        Schema::table('collection_collection_related', function (Blueprint $table) {
            $table->dropForeign(['first_collection_id']);
            $table->dropForeign(['second_collection_id']);
        });

        // Collection Product Complemented
        Schema::table('collection_product_complemented', function (Blueprint $table) {
            $table->dropForeign(['collection_id']);
            $table->dropForeign(['product_id']);
        });

        // Order Status Relations
        Schema::table('order_status', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['status_id']);
        });

        // Back to Stock Notifications
        Schema::table('back_to_stock_notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Product Complemented Relations
        Schema::table('product_product_complemented', function (Blueprint $table) {
            $table->dropForeign(['first_product_id']);
            $table->dropForeign(['second_product_id']);
        });

        // Sections Relations
        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign(['offer_id']);
        });

        // Invoices Relations
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });

        // Cities Relations
        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign(['governorate_id']);
        });

        // Collection Product Relations
        Schema::table('collection_product', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['collection_id']);
        });
    }
};