<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
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
        // Helper function to check if foreign key exists
        $checkForeignKeyExists = function ($table, $foreignKey) {
            $keyExists = DB::select(
                "SELECT *
                FROM information_schema.TABLE_CONSTRAINTS
                WHERE CONSTRAINT_TYPE = 'FOREIGN KEY'
                AND TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = '{$table}'
                AND CONSTRAINT_NAME = '{$table}_{$foreignKey}_foreign'"
            );

            return count($keyExists) > 0;
        };

        // Collection Product Relations
        Schema::table('collection_product', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('collection_product', 'product_id')) {
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            }
            if (!$checkForeignKeyExists('collection_product', 'collection_id')) {
                $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade')->onUpdate('cascade');
            }
        });

        // Cities Relations
        Schema::table('cities', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('cities', 'governorate_id')) {
                $table->foreign('governorate_id')->references('id')->on('governorates')->onDelete('cascade')->onUpdate('cascade');
            }
        });

        // Invoices Relations
        Schema::table('invoices', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('invoices', 'order_id')) {
                $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->nullOnDelete();
            }
        });

        // Sections Relations
        Schema::table('sections', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('sections', 'offer_id')) {
                $table->foreign('offer_id')->references('id')->on('offers')->onUpdate('cascade')->onDelete('cascade');
            }
        });

        // Product Complemented Relations
        Schema::table('product_product_complemented', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('product_product_complemented', 'first_product_id')) {
                $table->foreign('first_product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            }
            if (!$checkForeignKeyExists('product_product_complemented', 'second_product_id')) {
                $table->foreign('second_product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            }
        });

        // Back to Stock Notifications
        Schema::table('back_to_stock_notifications', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('back_to_stock_notifications', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->nullOnDelete();
            }
        });

        // Order Status Relations
        Schema::table('order_status', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('order_status', 'order_id')) {
                $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
            }
            if (!$checkForeignKeyExists('order_status', 'status_id')) {
                $table->foreign('status_id')->references('id')->on('statuses')->onUpdate('cascade')->onDelete('cascade');
            }
        });

        // Collection Product Complemented
        Schema::table('collection_product_complemented', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('collection_product_complemented', 'collection_id')) {
                $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade')->onUpdate('cascade');
            }
            if (!$checkForeignKeyExists('collection_product_complemented', 'product_id')) {
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            }
        });

        // Collection Collection Related
        Schema::table('collection_collection_related', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('collection_collection_related', 'first_collection_id')) {
                $table->foreign('first_collection_id')->references('id')->on('collections')->onDelete('cascade')->onUpdate('cascade');
            }
            if (!$checkForeignKeyExists('collection_collection_related', 'second_collection_id')) {
                $table->foreign('second_collection_id')->references('id')->on('collections')->onDelete('cascade')->onUpdate('cascade');
            }
        });

        // Destinations Relations
        Schema::table('destinations', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('destinations', 'delivery_id')) {
                $table->foreign('delivery_id')->references('id')->on('deliveries')->nullOnDelete()->onUpdate('cascade');
            }
            if (!$checkForeignKeyExists('destinations', 'zone_id')) {
                $table->foreign('zone_id')->references('id')->on('zones')->nullOnDelete()->onUpdate('cascade');
            }
            if (!$checkForeignKeyExists('destinations', 'country_id')) {
                $table->foreign('country_id')->references('id')->on('countries')->nullOnDelete()->onUpdate('cascade');
            }
            if (!$checkForeignKeyExists('destinations', 'governorate_id')) {
                $table->foreign('governorate_id')->references('id')->on('governorates')->nullOnDelete()->onUpdate('cascade');
            }
            if (!$checkForeignKeyExists('destinations', 'city_id')) {
                $table->foreign('city_id')->references('id')->on('cities')->nullOnDelete()->onUpdate('cascade');
            }
        });

        // Collections Relations
        Schema::table('collections', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('collections', 'created_by')) {
                $table->foreign('created_by')->references('id')->on('users')->nullOnDelete()->onUpdate('cascade');
            }
        });

        // Products Relations
        Schema::table('products', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('products', 'brand_id')) {
                $table->foreign('brand_id')->references('id')->on('brands')->nullOnDelete()->onUpdate('cascade');
            }
            if (!$checkForeignKeyExists('products', 'created_by')) {
                $table->foreign('created_by')->references('id')->on('users')->nullOnDelete()->onUpdate('cascade');
            }
        });

        // Subslider Small Banners
        Schema::table('subslider_small_banners', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('subslider_small_banners', 'banner_id')) {
                $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade')->onUpdate('cascade');
            }
        });

        // Brands Relations
        Schema::table('brands', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('brands', 'country_id')) {
                $table->foreign('country_id')->references('id')->on('countries')->nullOnDelete()->onUpdate('cascade');
            }
        });

        // Addresses Relations
        Schema::table('addresses', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('addresses', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            }
            if (!$checkForeignKeyExists('addresses', 'country_id')) {
                $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade')->onUpdate('cascade');
            }
            if (!$checkForeignKeyExists('addresses', 'governorate_id')) {
                $table->foreign('governorate_id')->references('id')->on('governorates')->onDelete('cascade')->onUpdate('cascade');
            }
            if (!$checkForeignKeyExists('addresses', 'city_id')) {
                $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
            }
        });

        // Phones Relations
        Schema::table('phones', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('phones', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            }
        });

        // Governorates Relations
        Schema::table('governorates', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('governorates', 'country_id')) {
                $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade')->onUpdate('cascade');
            }
        });

        // Main Slider Banners
        Schema::table('main_slider_banners', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('main_slider_banners', 'banner_id')) {
                $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade')->onUpdate('cascade');
            }
        });

        // Back to Stock Notifiables
        Schema::table('back_to_stock_notifiables', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('back_to_stock_notifiables', 'notification_id')) {
                $table->foreign('notification_id')->references('id')->on('back_to_stock_notifications')->onUpdate('cascade')->onDelete('cascade');
            }
        });

        // Sectionables Relations
        Schema::table('sectionables', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('sectionables', 'section_id')) {
                $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade')->onUpdate('cascade');
            }
        });

        // Zones Relations
        Schema::table('zones', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('zones', 'delivery_id')) {
                $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('cascade')->onUpdate('cascade');
            }
        });

        // Offerables Relations
        Schema::table('offerables', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('offerables', 'offer_id')) {
                $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade')->onUpdate('cascade');
            }
        });

        // Points Relations
        Schema::table('points', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('points', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete()->onUpdate('cascade');
            }
            if (!$checkForeignKeyExists('points', 'order_id')) {
                $table->foreign('order_id')->references('id')->on('orders')->nullOnDelete()->onUpdate('cascade');
            }
        });

        // Permission Tables Relations
        Schema::table('model_has_permissions', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('model_has_permissions', PermissionRegistrar::$pivotPermission)) {
                $table->foreign(PermissionRegistrar::$pivotPermission)
                    ->references('id')
                    ->on('permissions')
                    ->onDelete('cascade');
            }
        });

        Schema::table('model_has_roles', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('model_has_roles', PermissionRegistrar::$pivotRole)) {
                $table->foreign(PermissionRegistrar::$pivotRole)
                    ->references('id')
                    ->on('roles')
                    ->onDelete('cascade');
            }
        });

        Schema::table('role_has_permissions', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('role_has_permissions', PermissionRegistrar::$pivotPermission)) {
                $table->foreign(PermissionRegistrar::$pivotPermission)
                    ->references('id')
                    ->on('permissions')
                    ->onDelete('cascade');
            }

            if (!$checkForeignKeyExists('role_has_permissions', PermissionRegistrar::$pivotRole)) {
                $table->foreign(PermissionRegistrar::$pivotRole)
                    ->references('id')
                    ->on('roles')
                    ->onDelete('cascade');
            }
        });

        // Subcategories Relations
        Schema::table('subcategories', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('subcategories', 'category_id')) {
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            }
        });

        // Invoice Requests Relations
        Schema::table('invoice_requests', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('invoice_requests', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            }
            if (!$checkForeignKeyExists('invoice_requests', 'order_id')) {
                $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
            }
        });

        // Product Product Related
        Schema::table('product_product_related', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('product_product_related', 'first_product_id')) {
                $table->foreign('first_product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            }
            if (!$checkForeignKeyExists('product_product_related', 'second_product_id')) {
                $table->foreign('second_product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            }
        });

        // Reviews Relations
        Schema::table('reviews', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('reviews', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            }
        });

        // Orders Relations
        Schema::table('orders', function (Blueprint $table) use ($checkForeignKeyExists) {
            if (!$checkForeignKeyExists('orders', 'coupon_id')) {
                $table->foreign('coupon_id')->references('id')->on('coupons')->nullOnDelete()->onUpdate('cascade');
            }
            if (!$checkForeignKeyExists('orders', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete()->onUpdate('cascade');
            }
            if (!$checkForeignKeyExists('orders', 'address_id')) {
                $table->foreign('address_id')->references('id')->on('addresses')->nullOnDelete()->onUpdate('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // We won't drop foreign keys in the down method
        // since they might be needed by the original migrations
        // This migration is just to ensure all relations exist
    }
};