<?php

namespace Database\Seeders;

use App\Models\Policy;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PoliciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delivery
        Policy::create([
            'name' => 'delivery',
            'title' => [
                'en' => 'Delivery Policy',
                'ar' => 'سياسة الشحن',
            ],
            'content' => [
                'en' =>
                '<h2 class="text-2xl font-bold text-gray-800 mb-4">Delivery Time</h2>
                    <p class="text-gray-700 mb-2">The delivery time depends on your primary location and is as follows:</p>
                    <ul class="list-none flex flex-col gap-2">
                        <li class="bg-gray-200 p-3 rounded-md"><strong>Cairo, Giza, and Alexandria:</strong> 1 to 3 business days from order confirmation</li>
                        <li class="bg-gray-200 p-3 rounded-md"><strong>Canal and Delta Governorates:</strong> 2 to 4 business days from order confirmation</li>
                        <li class="bg-gray-200 p-3 rounded-md"><strong>Upper Egypt Governorates:</strong> 3 to 5 business days from order confirmation</li>
                    </ul>
                    <p class="text-gray-700 mt-4">Some special orders may take longer than the mentioned periods.</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">Shipping Cost</h2>
                    <p class="text-gray-700">Shipping cost is estimated based on the location and total weight of the order.</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">Shipping and Quality Policy</h2>
                    <p class="text-gray-700">Orders are reviewed and inspected by the quality department before shipping.</p>
                    <p class="text-gray-700">According to our shipping policy, most products in our store may take up to 24 business hours to complete the review and technical inspection by the quality department to ensure their safety and quality before shipping (because we don’t miss a single detail for you).</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">Order Confirmation for Shipping</h2>
                    <p class="text-gray-700">After the quality department has inspected the products and confirmed that all items are ready for shipping, we will contact you via email or phone number through text messages or a direct call from one of our customer service representatives to confirm the availability of all requested products.</p>
                    <p class="text-gray-700">If some products are unavailable or not ready for shipping, we will modify the order and contact you via one of our customer service representatives to make the necessary adjustments.</p>
                    <p class="text-gray-700">Please note that for some orders, full payment or a partial payment may be required in case of cash on delivery to confirm the order and complete its shipping process as a commitment from the customer to receive the order.</p>',
                'ar' =>
                '<h1 class="text-2xl font-bold text-gray-800 mb-4">موعد التسليم</h1>
                    <p class="text-gray-700 mb-2">يعتمد وقت التسليم على موقعك الأساسي ويكون كالتالي:</p>
                    <ul class="list-none flex flex-col gap-2">
                        <li class="bg-gray-200 p-3 rounded-md"><strong>القاهرة والجيزة والإسكندرية:</strong> من 1 إلى 3 أيام عمل من تأكيد الطلب</li>
                        <li class="bg-gray-200 p-3 rounded-md"><strong>القناة ومحافظات الدلتا:</strong> من 2 إلى 4 أيام عمل من تأكيد الطلب</li>
                        <li class="bg-gray-200 p-3 rounded-md"><strong>محافظات الصعيد:</strong> من 3 إلى 5 أيام عمل من تأكيد الطلب</li>
                    </ul>
                    <p class="text-gray-700 mt-4">قد تستغرق بعض الطلبات الخاصة وقتًا أطول من الفترات المذكورة.</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">تكلفة الشحن</h2>
                    <p class="text-gray-700">يتم تقدير تكلفة الشحن بناءً على موقع ووزن الطلب الإجمالي.</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">سياسة الشحن والجودة</h2>
                    <p class="text-gray-700">يتم مراجعة الطلب وفحصه من قسم الجودة قبل الشحن.</p>
                    <p class="text-gray-700">طبقًا لسياسة الشحن الخاصة بنا، فإن معظم السلع الواردة بمتجرنا قد تستغرق 24 ساعة عمل لإتمام عملية المراجعة والفحص الفني من قسم الجودة، وذلك للتأكد من سلامتها وجودتها قبل الشحن (علشانك مش بنفوت مسمار).</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">تأكيد الطلب للشحن</h2>
                    <p class="text-gray-700">بعد الانتهاء من فحص المنتجات من قسم الجودة والتأكد من جاهزية جميع المنتجات للشحن، سيتم التواصل معكم من خلال البريد الإلكتروني أو رقم الهاتف عبر الرسائل النصية أو اتصال مباشر من أحد موظفي خدمة العملاء، وذلك للتأكيد على توافر جميع المنتجات المطلوبة.</p>
                    <p class="text-gray-700">في حالة عدم توافر بعض المنتجات أو عدم جاهزيتها للشحن، سنقوم بتعديل الطلب والتواصل معكم عبر أحد موظفي خدمة العملاء لإجراء التعديلات المناسبة على الطلبات.</p>
                    <p class="text-gray-700">يرجى العلم أنه في بعض الطلبات قد يتوجب دفعها بالكامل أو دفع جزء منها في حالة الدفع عند التسليم لتأكيد الطلب وإتمام عملية شحنه وكجدية استلام من العميل للطلب.</p>',
            ],
        ]);

        Policy::create([
            'name' => 'return-and-exchange',
            'title'=> [
                'en' => 'Returns and Exchanges',
                'ar' => 'الإرجاع والتبديل',
            ],
            'content'=> [
                'en' => '
                    <h2 class="text-xl font-semibold text-gray-800 mt-6">Return Request</h2>
                    <p class="text-gray-700">You can request a return for products and offers easily through the website, and the request will be processed within 72 hours. The return request will be confirmed through WhatsApp or a phone call. If there\'s no response within 24 hours, the request will be canceled.</p>
                    <p class="text-gray-700 mt-2">Return requests can be made within 14 days from the receipt of the order for defective or non-compliant products.</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">Return Conditions</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>Original invoice must be available.</li>
                        <li>Return within 14 days.</li>
                        <li>The product and packaging must be in their original condition, unused, and unopened.</li>
                        <li>The value of the returned product will be converted to customer account credit or points for future purchases. No cash refunds.</li>
                        <li>If paid by Visa or installment, bank fees and taxes will be deducted from the amount, and the remaining balance will be refunded using the same payment method.</li>
                        <li>Online payments take 7-14 business days for the return process.</li>
                        <li>All original product contents and packaging must be returned in their original condition.</li>
                        <li>If any part or accessory of the product is missing, the return will be rejected.</li>
                        <li>The customer is responsible for return shipping costs.</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">Exchange Conditions</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>The product must be in its original condition, completely sealed and unopened.</li>
                        <li>Original invoice must be available.</li>
                        <li>Exchanges must occur within 48 hours.</li>
                        <li>The exchange must be for the same value or higher, not lower.</li>
                        <li>The customer is responsible for shipping costs both ways for exchanges.</li>
                        <li>The company will cover all shipping costs if a wrong or non-compliant product was delivered.</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">Non-Returnable & Non-Exchangeable Items</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>Damage caused by misuse, which can be avoided by contacting customer support for proper usage instructions.</li>
                        <li>Products with serial number mismatches, damaged packaging, or any differences in appearance from when purchased.</li>
                        <li>Items without the original packaging will not be accepted for return.</li>
                        <li>All electrical or manual devices that use liquids or collect dirt (e.g., vacuums, paint sprayers, etc.).</li>
                        <li>Used hand tools are not eligible for return.</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-red-600 mt-6">Important Notice</h2>
                    <p class="text-gray-700">In case of misuse of the return policy (excessive returns or returning non-original products), the company reserves the right to warn, restrict, suspend, or ban customer accounts as necessary.</p>
                ',
                'ar' => '
                    <h2 class="text-xl font-semibold text-gray-800 mt-6">طلب الاسترجاع</h2>
                    <p class="text-gray-700">يمكنك عمل طلب استرجاع للمنتجات والعروض بكل سهوله من خلال الموقع، وسيتم العمل عليه خلال 72 ساعة. سيتم تأكيد الطلب عبر الواتساب أو مكالمة هاتفية، وفي حالة عدم التجاوب خلال 24 ساعة يتم إلغاء الطلب.</p>
                    <p class="text-gray-700 mt-2">يمكن طلب الاسترجاع خلال 14 يومًا من استلام الطلب للمنتجات المعيبة أو غير المطابقة للوصف.</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">شروط الاسترجاع</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>توافر الفاتورة الأصلية.</li>
                        <li>الارتجاع خلال 14 يومًا.</li>
                        <li>المنتج والتغليف في حالتهما الأصلية، غير مستخدم أو مفتوح.</li>
                        <li>قيمة المنتج المرتجع تُحوَّل إلى حساب العميل أو كنقاط شراء، ولا تُرد نقدًا.</li>
                        <li>في حالة الدفع بالفيزا أو التقسيط يتم خصم عمولة البنك والضرائب.</li>
                        <li>المدفوعات الإلكترونية تستغرق 7-14 يوم عمل للاسترجاع.</li>
                        <li>يجب إرجاع جميع المحتويات الأصلية للمنتج.</li>
                        <li>إذا فقد أي جزء من المحتويات، يتم رفض الاسترجاع.</li>
                        <li>العميل يتحمل رسوم الشحن عند الإرجاع.</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">شروط الاستبدال</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>المنتج بحالته الأصلية، مغلق تمامًا.</li>
                        <li>توافر الفاتورة الأصلية.</li>
                        <li>يتم الاستبدال خلال 48 ساعة.</li>
                        <li>الاستبدال بنفس القيمة أو أعلى.</li>
                        <li>العميل يتحمل مصاريف الشحن ذهابًا وإيابًا.</li>
                        <li>الشركة تتحمل الشحن عند الخطأ في المنتج أو المواصفات.</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">حالات لا يمكن استرجاعها</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>أعطال ناتجة عن سوء الاستخدام.</li>
                        <li>عدم تطابق الرقم التسلسلي أو تضرر العبوة الأصلية.</li>
                        <li>عدم وجود العبوة الأصلية.</li>
                        <li>الأجهزة الكهربائية التي تتعامل مع السوائل والأتربة.</li>
                        <li>الأدوات اليدوية المستخدمة.</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-red-600 mt-6">تنويه هام</h2>
                    <p class="text-gray-700">في حالة إساءة استخدام سياسة الإرجاع (إرجاع مفرط أو إرجاع منتجات غير أصلية)، تحتفظ الشركة بحقها في تحذير الحسابات أو تقييدها أو حظرها.</p>
                ',
            ],
        ]);

        Policy::create([
            'name' => 'privacy',
            'title'=> [
                'en' => 'privacy Policy',
                'ar' => 'سياسة الخصوصية',
            ],
            'content' => [
                'en' => '
                    <h2 class="text-xl font-semibold text-gray-800 mt-6">What Personal Information Do We Collect</h2>
                    <p class="text-gray-700">When you visit the site, we automatically collect certain information about your device, including information about your web browser, IP address, time zone, and some cookies that are installed on your device.</p>
                    <p class="text-gray-700 mt-2">Additionally, as you browse the site, we collect information about the individual web pages or products that you view, the websites or search terms that referred you to the site, and information about how you interact with the site.</p>
                    <p class="text-gray-700 mt-2">When you make a purchase or attempt to make a purchase through the site, we collect certain information from you, including your name, billing address, shipping address, and payment information.</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">How We Use Your Personal Information</h2>
                    <p class="text-gray-700">We use the order information we collect generally to fulfill any orders placed through the site (including processing your payment information, arranging for shipping, and providing you with invoices and/or order confirmations).</p>
                    <p class="text-gray-700 mt-2">Additionally, we use this order information to:</p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>Communicate with you.</li>
                        <li>Screen our orders for potential risks or fraud.</li>
                        <li>When in line with the preferences you’ve shared with us, provide you with information or advertisements related to our products or services.</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">Sharing Your Personal Information</h2>
                    <p class="text-gray-700">We share your personal information with third parties to help us use your personal information, as described above. We also use Google Analytics to help us understand how our customers use our site.</p>
                    <p class="text-gray-700 mt-2">Finally, we may also share your personal information to comply with applicable laws and regulations, respond to a subpoena, search warrant, or other lawful requests for information we receive, or to otherwise protect our rights.</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">Advertising</h2>
                    <p class="text-gray-700">We use your personal information to provide you with targeted advertisements or marketing communications we believe may be of interest to you.</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">Data Retention</h2>
                    <p class="text-gray-700">When you place an order through the site, we will retain your order information for our records unless and until you ask us to delete this information.</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">Changes</h2>
                    <p class="text-gray-700">We may update this Privacy Policy from time to time to reflect, for example, changes to our practices or for other operational, legal, or regulatory reasons.</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">Your Rights Through Visiting or Accessing Your Account on the Site</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>The right to access your personal data.</li>
                        <li>The right to amend your personal data.</li>
                        <li>The right to delete your personal data.</li>
                    </ul>
                ',
                'ar' => '
                    <h2 class="text-xl font-semibold text-gray-800 mt-6">ما هي المعلومات الشخصية التي نجمعها</h2>
                    <p class="text-gray-700">عند زيارتك الموقع، نقوم تلقائيًا بجمع معلومات معينة حول جهازك، بما في ذلك معلومات حول متصفح الويب وعنوان IP والمنطقة الزمنية وبعض ملفات تعريف الارتباط المثبتة على جهازك.</p>
                    <p class="text-gray-700 mt-2">بالإضافة إلى ذلك، أثناء تصفحك للموقع، نقوم بجمع معلومات حول صفحات الويب الفردية أو المنتجات التي تشاهدها، ومواقع الويب أو مصطلحات البحث التي أحالتك إلى الموقع، ومعلومات حول كيفية تفاعلك مع الموقع.</p>
                    <p class="text-gray-700 mt-2">عند إجراء عملية شراء أو محاولة إجراء عملية شراء عبر الموقع، فإننا نجمع معلومات معينة منك، بما في ذلك اسمك وعنوان إرسال الفواتير وعنوان الشحن ومعلومات الدفع.</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">كيف نستخدم المعلومات الشخصية الخاصة بك</h2>
                    <p class="text-gray-700">نستخدم معلومات الطلب التي نجمعها بشكل عام للوفاء بأي طلبات يتم تقديمها عبر الموقع (بما في ذلك معالجة معلومات الدفع الخاصة بك، وترتيب الشحن، وتزويدك بالفواتير و/أو تأكيدات الطلبات).</p>
                    <p class="text-gray-700 mt-2">بالإضافة إلى ذلك، نستخدم معلومات الطلب هذه من أجل:</p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>التواصل معك.</li>
                        <li>فحص طلباتنا للمخاطر المحتملة أو الاحتيال.</li>
                        <li>عندما تتماشى مع التفضيلات التي شاركتها معنا، قم بتزويدك بالمعلومات أو الإعلانات المتعلقة بمنتجاتنا أو خدماتنا.</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">تبادل المعلومات الشخصية الخاصة بك</h2>
                    <p class="text-gray-700">نشارك معلوماتك الشخصية مع أطراف ثالثة لمساعدتنا في استخدام معلوماتك الشخصية، كما هو موضح أعلاه. نستخدم أيضًا Google Analytics لمساعدتنا على فهم كيفية استخدام عملائنا للموقع.</p>
                    <p class="text-gray-700 mt-2">أخيرًا، قد نشارك أيضًا معلوماتك الشخصية للامتثال للقوانين واللوائح المعمول بها، للرد على أمر استدعاء أو أمر تفتيش أو طلبات قانونية أخرى للحصول على معلومات نتلقاها، أو لحماية حقوقنا بطريقة أخرى.</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">الدعاية</h2>
                    <p class="text-gray-700">نستخدم معلوماتك الشخصية لتزويدك بالإعلانات المستهدفة أو الاتصالات التسويقية التي نعتقد أنها قد تهمك.</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">الاحتفاظ بالبيانات</h2>
                    <p class="text-gray-700">عند تقديم طلب عبر الموقع، سنحتفظ بمعلومات الطلب الخاصة بك لسجلاتنا ما لم تطلب منا حذف هذه المعلومات.</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">التغييرات</h2>
                    <p class="text-gray-700">قد نقوم بتحديث سياسة الخصوصية هذه من وقت لآخر من أجل عكس، على سبيل المثال، التغييرات التي تطرأ على ممارساتنا أو لأسباب تشغيلية أو قانونية أو تنظيمية أخرى.</p>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6">ما هي حقوقك من خلال زيارة حسابك على الموقع</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>الحق في الوصول إلى بياناتك الشخصية.</li>
                        <li>الحق في تعديل بياناتك الشخصية.</li>
                        <li>الحق في حذف بياناتك الشخصية.</li>
                    </ul>
                ',
            ],
        ]);
    }
}
