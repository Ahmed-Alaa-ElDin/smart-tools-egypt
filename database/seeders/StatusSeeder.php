<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::updateOrCreate([
            'id' => 1,
            'name' => [
                'en' => 'Under Processing',
                'ar' => 'جاري إنشاء الطلب',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 2,
            'name' => [
                'en' => 'Waiting For Payment',
                'ar' => 'في انتظار الدفع',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 3,
            'name' => [
                'en' => 'Created',
                'ar' => 'تم إنشاء الطلب',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 4,
            'name' => [
                'en' => 'Preparing',
                'ar' => 'جاري التجهيز',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 5,
            'name' => [
                'en' => 'Under Reviewing',
                'ar' => 'جاري الفحص والمراجعة',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 6,
            'name' => [
                'en' => 'Shipped',
                'ar' => 'تم التسليم لشركة الشحن',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 8,
            'name' => [
                'en' => 'Cancellation Requested',
                'ar' => 'تم طلب الإلغاء',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 9,
            'name' => [
                'en' => 'Cancelled',
                'ar' => 'تم الإلغاء',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 15,
            'name' => [
                'en' => 'Under Editing',
                'ar' => 'جاري التعديل',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 16,
            'name' => [
                'en' => 'Edit Requested',
                'ar' => 'تم طلب التعديل',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 12,
            'name' => [
                'en' => 'Edit Approved',
                'ar' => 'تمت الموافقة على التعديل',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 13,
            'name' => [
                'en' => 'Edit Rejected',
                'ar' => 'تم رفض التعديل',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 14,
            'name' => [
                'en' => 'Waiting For Refund',
                'ar' => 'في انتظار ارتجاع المبلغ',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 7,
            'name' => [
                'en' => 'Under Returning',
                'ar' => 'جاري طلب الاسترجاع',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 17,
            'name' => [
                'en' => 'Return Requested',
                'ar' => 'تم طلب الاسترجاع',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 18,
            'name' => [
                'en' => 'Return Approved',
                'ar' => 'تم قبول طلب الاسترجاع',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 19,
            'name' => [
                'en' => 'Return Rejected',
                'ar' => 'تم رفض طلب الاسترجاع',
            ],
        ]);

        // Bosta Statuses
        Status::updateOrCreate([
            'id' => 10,
            'name' => [
                'en' => 'Pickup requested',
                'ar' => 'تم طلب الاستلام',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 11,
            'name' => [
                'en' => 'Waiting for route',
                'ar' => 'في انتظار توضيح الطريق',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 20,
            'name' => [
                'en' => 'Route Assigned',
                'ar' => 'تم تحديد الطريق',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 21,
            'name' => [
                'en' => 'Picked up from business',
                'ar' => 'تم الاستلام من المتجر',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 22,
            'name' => [
                'en' => 'Picking up from consignee',
                'ar' => 'جاري الاستلام من العميل',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 23,
            'name' => [
                'en' => 'Picked up from consignee',
                'ar' => 'تم الاستلام من العميل',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 24,
            'name' => [
                'en' => 'Received at warehouse',
                'ar' => 'تم الاستلام في المستودع',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 30,
            'name' => [
                'en' => 'In transit between Hubs',
                'ar' => 'في الطريق بين المستودعات',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 40,
            'name' => [
                'en' => 'Picking up',
                'ar' => 'جاري الاستلام',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 41,
            'name' => [
                'en' => 'Picked up',
                'ar' => 'تم الاستلام',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 42,
            'name' => [
                'en' => 'Pending Customer Signature',
                'ar' => 'في انتظار توقيع العميل',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 43,
            'name' => [
                'en' => 'Debriefed Successfully',
                'ar' => 'تم التأكيد',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 45,
            'name' => [
                'en' => 'Delivered',
                'ar' => 'تم التوصيل',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 46,
            'name' => [
                'en' => 'Returned to business',
                'ar' => 'تم الارجاع إلى المتجر',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 47,
            'name' => [
                'en' => 'Exception',
                'ar' => 'استثناء',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 48,
            'name' => [
                'en' => 'Terminated',
                'ar' => 'منتهي',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 49,
            'name' => [
                'en' => 'Canceled (uncovered area)',
                'ar' => 'ملغي (منطقة غير مغطاة)',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 50,
            'name' => [
                'en' => 'Collection Failed',
                'ar' => 'فشل في الاستلام',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 100,
            'name' => [
                'en' => 'Lost',
                'ar' => 'مفقود',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 101,
            'name' => [
                'en' => 'Damaged',
                'ar' => 'متضرر',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 102,
            'name' => [
                'en' => 'Investigation',
                'ar' => 'جاري التحقيق',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 103,
            'name' => [
                'en' => 'Awaiting your action',
                'ar' => 'في انتظار ردك',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 104,
            'name' => [
                'en' => 'Archived',
                'ar' => 'مؤرشف',
            ],
        ]);
        Status::updateOrCreate([
            'id' => 105,
            'name' => [
                'en' => 'On hold',
                'ar' => 'معلق',
            ],
        ]);
    }
}
