<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::create([
            'id' => 1,
            'name' => ['en' => '15 May', 'ar' => '15 مايو'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 2,
            'name' => ['en' => 'Al Azbakeyah', 'ar' => 'الازبكية'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 3,
            'name' => ['en' => 'Al Basatin', 'ar' => 'البساتين'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 4,
            'name' => ['en' => 'Tebin', 'ar' => 'التبين'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 5,
            'name' => ['en' => 'El-Khalifa', 'ar' => 'الخليفة'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 6,
            'name' => ['en' => 'El darrasa', 'ar' => 'الدراسة'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 7,
            'name' => ['en' => 'Aldarb Alahmar', 'ar' => 'الدرب الاحمر'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 8,
            'name' => ['en' => 'Zawya al-Hamra', 'ar' => 'الزاوية الحمراء'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 9,
            'name' => ['en' => 'El-Zaytoun', 'ar' => 'الزيتون'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 10,
            'name' => ['en' => 'Sahel', 'ar' => 'الساحل'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 11,
            'name' => ['en' => 'El Salam', 'ar' => 'السلام'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 12,
            'name' => ['en' => 'Sayeda Zeinab', 'ar' => 'السيدة زينب'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 13,
            'name' => ['en' => 'El Sharabeya', 'ar' => 'الشرابية'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 14,
            'name' => ['en' => 'Shorouk', 'ar' => 'مدينة الشروق'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 15,
            'name' => ['en' => 'El Daher', 'ar' => 'الظاهر'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 16,
            'name' => ['en' => 'Ataba', 'ar' => 'العتبة'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 17,
            'name' => ['en' => 'New Cairo', 'ar' => 'القاهرة الجديدة'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 18,
            'name' => ['en' => 'El Marg', 'ar' => 'المرج'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 19,
            'name' => ['en' => 'Ezbet el Nakhl', 'ar' => 'عزبة النخل'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 20,
            'name' => ['en' => 'Matareya', 'ar' => 'المطرية'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 21,
            'name' => ['en' => 'Maadi', 'ar' => 'المعادى'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 22,
            'name' => ['en' => 'Maasara', 'ar' => 'المعصرة'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 23,
            'name' => ['en' => 'Mokattam', 'ar' => 'المقطم'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 24,
            'name' => ['en' => 'Manyal', 'ar' => 'المنيل'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 25,
            'name' => ['en' => 'Mosky', 'ar' => 'الموسكى'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 26,
            'name' => ['en' => 'Nozha', 'ar' => 'النزهة'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 27,
            'name' => ['en' => 'Waily', 'ar' => 'الوايلى'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 28,
            'name' => ['en' => 'Bab al-Shereia', 'ar' => 'باب الشعرية'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 29,
            'name' => ['en' => 'Bolaq', 'ar' => 'بولاق'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 30,
            'name' => ['en' => 'Garden City', 'ar' => 'جاردن سيتى'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 31,
            'name' => ['en' => 'Hadayek El-Kobba', 'ar' => 'حدائق القبة'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 32,
            'name' => ['en' => 'Helwan', 'ar' => 'حلوان'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 33,
            'name' => ['en' => 'Dar Al Salam', 'ar' => 'دار السلام'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 34,
            'name' => ['en' => 'Shubra', 'ar' => 'شبرا'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 35,
            'name' => ['en' => 'Tura', 'ar' => 'طره'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 36,
            'name' => ['en' => 'Abdeen', 'ar' => 'عابدين'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 37,
            'name' => ['en' => 'Abaseya', 'ar' => 'عباسية'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 38,
            'name' => ['en' => 'Ain Shams', 'ar' => 'عين شمس'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 39,
            'name' => ['en' => 'Nasr City', 'ar' => 'مدينة نصر'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 40,
            'name' => ['en' => 'New Heliopolis', 'ar' => 'مصر الجديدة'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 41,
            'name' => ['en' => 'Masr Al Qadima', 'ar' => 'مصر القديمة'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 42,
            'name' => ['en' => 'Mansheya Nasir', 'ar' => 'منشية ناصر'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 43,
            'name' => ['en' => 'Badr City', 'ar' => 'مدينة بدر'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 44,
            'name' => ['en' => 'Obour City', 'ar' => 'مدينة العبور'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 45,
            'name' => ['en' => 'Cairo Downtown', 'ar' => 'وسط البلد'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 46,
            'name' => ['en' => 'Zamalek', 'ar' => 'الزمالك'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 47,
            'name' => ['en' => 'Kasr El Nile', 'ar' => 'قصر النيل'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 48,
            'name' => ['en' => 'Rehab', 'ar' => 'الرحاب'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 49,
            'name' => ['en' => 'Katameya', 'ar' => 'القطامية'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 50,
            'name' => ['en' => 'Madinty', 'ar' => 'مدينتي'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 51,
            'name' => ['en' => 'Rod Alfarag', 'ar' => 'روض الفرج'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 52,
            'name' => ['en' => 'Sheraton', 'ar' => 'شيراتون'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 53,
            'name' => ['en' => 'El-Gamaleya', 'ar' => 'الجمالية'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 54,
            'name' => ['en' => '10th of Ramadan City', 'ar' => 'العاشر من رمضان'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 55,
            'name' => ['en' => 'Helmeyat Alzaytoun', 'ar' => 'الحلمية'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 56,
            'name' => ['en' => 'New Nozha', 'ar' => 'النزهة الجديدة'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 57,
            'name' => ['en' => 'Capital New', 'ar' => 'العاصمة الإدارية'],
            'governorate_id' => 1
        ]);
        City::create([
            'id' => 58,
            'name' => ['en' => 'Giza', 'ar' => 'الجيزة'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 59,
            'name' => ['en' => 'Sixth of October', 'ar' => 'السادس من أكتوبر'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 60,
            'name' => ['en' => 'Cheikh Zayed', 'ar' => 'الشيخ زايد'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 61,
            'name' => ['en' => 'Hawamdiyah', 'ar' => 'الحوامدية'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 62,
            'name' => ['en' => 'Al Badrasheen', 'ar' => 'البدرشين'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 63,
            'name' => ['en' => 'Saf', 'ar' => 'الصف'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 64,
            'name' => ['en' => 'Atfih', 'ar' => 'أطفيح'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 65,
            'name' => ['en' => 'Al Ayat', 'ar' => 'العياط'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 66,
            'name' => ['en' => 'Al-Bawaiti', 'ar' => 'الباويطي'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 67,
            'name' => ['en' => 'ManshiyetAl Qanater', 'ar' => 'منشأة القناطر'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 68,
            'name' => ['en' => 'Oaseem', 'ar' => 'أوسيم'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 69,
            'name' => ['en' => 'Kerdasa', 'ar' => 'كرداسة'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 70,
            'name' => ['en' => 'Abu Nomros', 'ar' => 'أبو النمرس'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 71,
            'name' => ['en' => 'Kafr Ghati', 'ar' => 'كفر غطاطي'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 72,
            'name' => ['en' => 'Manshiyet Al Bakari', 'ar' => 'منشأة البكاري'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 73,
            'name' => ['en' => 'Dokki', 'ar' => 'الدقى'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 74,
            'name' => ['en' => 'Agouza', 'ar' => 'العجوزة'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 75,
            'name' => ['en' => 'Haram', 'ar' => 'الهرم'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 76,
            'name' => ['en' => 'Warraq', 'ar' => 'الوراق'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 77,
            'name' => ['en' => 'Imbaba', 'ar' => 'امبابة'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 78,
            'name' => ['en' => 'Boulaq Dakrour', 'ar' => 'بولاق الدكرور'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 79,
            'name' => ['en' => 'Al Wahat Al Baharia', 'ar' => 'الواحات البحرية'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 80,
            'name' => ['en' => 'Omraneya', 'ar' => 'العمرانية'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 81,
            'name' => ['en' => 'Moneeb', 'ar' => 'المنيب'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 82,
            'name' => ['en' => 'Bin Alsarayat', 'ar' => 'بين السرايات'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 83,
            'name' => ['en' => 'Kit Kat', 'ar' => 'الكيت كات'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 84,
            'name' => ['en' => 'Mohandessin', 'ar' => 'المهندسين'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 85,
            'name' => ['en' => 'Faisal', 'ar' => 'فيصل'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 86,
            'name' => ['en' => 'Abu Rawash', 'ar' => 'أبو رواش'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 87,
            'name' => ['en' => 'Hadayek Alahram', 'ar' => 'حدائق الأهرام'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 88,
            'name' => ['en' => 'Haraneya', 'ar' => 'الحرانية'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 89,
            'name' => ['en' => 'Hadayek October', 'ar' => 'حدائق اكتوبر'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 90,
            'name' => ['en' => 'Saft Allaban', 'ar' => 'صفط اللبن'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 91,
            'name' => ['en' => 'Smart Village', 'ar' => 'القرية الذكية'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 92,
            'name' => ['en' => 'Ard Ellwaa', 'ar' => 'ارض اللواء'],
            'governorate_id' => 2
        ]);
        City::create([
            'id' => 93,
            'name' => ['en' => 'Abu Qir', 'ar' => 'ابو قير'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 94,
            'name' => ['en' => 'Al Ibrahimeyah', 'ar' => 'الابراهيمية'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 95,
            'name' => ['en' => 'Azarita', 'ar' => 'الأزاريطة'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 96,
            'name' => ['en' => 'Anfoushi', 'ar' => 'الانفوشى'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 97,
            'name' => ['en' => 'Dekheila', 'ar' => 'الدخيلة'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 98,
            'name' => ['en' => 'El Soyof', 'ar' => 'السيوف'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 99,
            'name' => ['en' => 'Ameria', 'ar' => 'العامرية'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 100,
            'name' => ['en' => 'El Labban', 'ar' => 'اللبان'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 101,
            'name' => ['en' => 'Al Mafrouza', 'ar' => 'المفروزة'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 102,
            'name' => ['en' => 'El Montaza', 'ar' => 'المنتزه'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 103,
            'name' => ['en' => 'Mansheya', 'ar' => 'المنشية'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 104,
            'name' => ['en' => 'Naseria', 'ar' => 'الناصرية'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 105,
            'name' => ['en' => 'Ambrozo', 'ar' => 'امبروزو'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 106,
            'name' => ['en' => 'Bab Sharq', 'ar' => 'باب شرق'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 107,
            'name' => ['en' => 'Bourj Alarab', 'ar' => 'برج العرب'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 108,
            'name' => ['en' => 'Stanley', 'ar' => 'ستانلى'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 109,
            'name' => ['en' => 'Smouha', 'ar' => 'سموحة'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 110,
            'name' => ['en' => 'Sidi Bishr', 'ar' => 'سيدى بشر'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 111,
            'name' => ['en' => 'Shads', 'ar' => 'شدس'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 112,
            'name' => ['en' => 'Gheet Alenab', 'ar' => 'غيط العنب'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 113,
            'name' => ['en' => 'Fleming', 'ar' => 'فلمينج'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 114,
            'name' => ['en' => 'Victoria', 'ar' => 'فيكتوريا'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 115,
            'name' => ['en' => 'Camp Shizar', 'ar' => 'كامب شيزار'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 116,
            'name' => ['en' => 'Karmooz', 'ar' => 'كرموز'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 117,
            'name' => ['en' => 'Mahta Alraml', 'ar' => 'محطة الرمل'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 118,
            'name' => ['en' => 'Mina El-Basal', 'ar' => 'مينا البصل'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 119,
            'name' => ['en' => 'Asafra', 'ar' => 'العصافرة'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 120,
            'name' => ['en' => 'Agamy', 'ar' => 'العجمي'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 121,
            'name' => ['en' => 'Bakos', 'ar' => 'بكوس'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 122,
            'name' => ['en' => 'Boulkly', 'ar' => 'بولكلي'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 123,
            'name' => ['en' => 'Cleopatra', 'ar' => 'كليوباترا'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 124,
            'name' => ['en' => 'Glim', 'ar' => 'جليم'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 125,
            'name' => ['en' => 'Al Mamurah', 'ar' => 'المعمورة'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 126,
            'name' => ['en' => 'Al Mandara', 'ar' => 'المندرة'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 127,
            'name' => ['en' => 'Moharam Bek', 'ar' => 'محرم بك'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 128,
            'name' => ['en' => 'Elshatby', 'ar' => 'الشاطبي'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 129,
            'name' => ['en' => 'Sidi Gaber', 'ar' => 'سيدي جابر'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 130,
            'name' => ['en' => 'North Coast/sahel', 'ar' => 'الساحل الشمالي'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 131,
            'name' => ['en' => 'Alhadra', 'ar' => 'الحضرة'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 132,
            'name' => ['en' => 'Alattarin', 'ar' => 'العطارين'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 133,
            'name' => ['en' => 'Sidi Kerir', 'ar' => 'سيدي كرير'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 134,
            'name' => ['en' => 'Elgomrok', 'ar' => 'الجمرك'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 135,
            'name' => ['en' => 'Al Max', 'ar' => 'المكس'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 136,
            'name' => ['en' => 'Marina', 'ar' => 'مارينا'],
            'governorate_id' => 3
        ]);
        City::create([
            'id' => 137,
            'name' => ['en' => 'Mansoura', 'ar' => 'المنصورة'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 138,
            'name' => ['en' => 'Talkha', 'ar' => 'طلخا'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 139,
            'name' => ['en' => 'Mitt Ghamr', 'ar' => 'ميت غمر'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 140,
            'name' => ['en' => 'Dekernes', 'ar' => 'دكرنس'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 141,
            'name' => ['en' => 'Aga', 'ar' => 'أجا'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 142,
            'name' => ['en' => 'Menia El Nasr', 'ar' => 'منية النصر'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 143,
            'name' => ['en' => 'Sinbillawin', 'ar' => 'السنبلاوين'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 144,
            'name' => ['en' => 'El Kurdi', 'ar' => 'الكردي'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 145,
            'name' => ['en' => 'Bani Ubaid', 'ar' => 'بني عبيد'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 146,
            'name' => ['en' => 'Al Manzala', 'ar' => 'المنزلة'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 147,
            'name' => ['en' => 'tami alamdid', 'ar' => 'تمي الأمديد'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 148,
            'name' => ['en' => 'aljamalia', 'ar' => 'الجمالية'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 149,
            'name' => ['en' => 'Sherbin', 'ar' => 'شربين'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 150,
            'name' => ['en' => 'Mataria', 'ar' => 'المطرية'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 151,
            'name' => ['en' => 'Belqas', 'ar' => 'بلقاس'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 152,
            'name' => ['en' => 'Meet Salsil', 'ar' => 'ميت سلسيل'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 153,
            'name' => ['en' => 'Gamasa', 'ar' => 'جمصة'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 154,
            'name' => ['en' => 'Mahalat Damana', 'ar' => 'محلة دمنة'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 155,
            'name' => ['en' => 'Nabroh', 'ar' => 'نبروه'],
            'governorate_id' => 4
        ]);
        City::create([
            'id' => 156,
            'name' => ['en' => 'Hurghada', 'ar' => 'الغردقة'],
            'governorate_id' => 5
        ]);
        City::create([
            'id' => 157,
            'name' => ['en' => 'Ras Ghareb', 'ar' => 'رأس غارب'],
            'governorate_id' => 5
        ]);
        City::create([
            'id' => 158,
            'name' => ['en' => 'Safaga', 'ar' => 'سفاجا'],
            'governorate_id' => 5
        ]);
        City::create([
            'id' => 159,
            'name' => ['en' => 'El Qusiar', 'ar' => 'القصير'],
            'governorate_id' => 5
        ]);
        City::create([
            'id' => 160,
            'name' => ['en' => 'Marsa Alam', 'ar' => 'مرسى علم'],
            'governorate_id' => 5
        ]);
        City::create([
            'id' => 161,
            'name' => ['en' => 'Shalatin', 'ar' => 'الشلاتين'],
            'governorate_id' => 5
        ]);
        City::create([
            'id' => 162,
            'name' => ['en' => 'Halaib', 'ar' => 'حلايب'],
            'governorate_id' => 5
        ]);
        City::create([
            'id' => 163,
            'name' => ['en' => 'Aldahar', 'ar' => 'الدهار'],
            'governorate_id' => 5
        ]);
        City::create([
            'id' => 164,
            'name' => ['en' => 'Damanhour', 'ar' => 'دمنهور'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 165,
            'name' => ['en' => 'Kafr El Dawar', 'ar' => 'كفر الدوار'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 166,
            'name' => ['en' => 'Rashid', 'ar' => 'رشيد'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 167,
            'name' => ['en' => 'Edco', 'ar' => 'إدكو'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 168,
            'name' => ['en' => 'Abu al-Matamir', 'ar' => 'أبو المطامير'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 169,
            'name' => ['en' => 'Abu Homs', 'ar' => 'أبو حمص'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 170,
            'name' => ['en' => 'Delengat', 'ar' => 'الدلنجات'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 171,
            'name' => ['en' => 'Mahmoudiyah', 'ar' => 'المحمودية'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 172,
            'name' => ['en' => 'Rahmaniyah', 'ar' => 'الرحمانية'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 173,
            'name' => ['en' => 'Itai Baroud', 'ar' => 'إيتاي البارود'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 174,
            'name' => ['en' => 'Housh Eissa', 'ar' => 'حوش عيسى'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 175,
            'name' => ['en' => 'Shubrakhit', 'ar' => 'شبراخيت'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 176,
            'name' => ['en' => 'Kom Hamada', 'ar' => 'كوم حمادة'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 177,
            'name' => ['en' => 'Badr', 'ar' => 'بدر'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 178,
            'name' => ['en' => 'Wadi Natrun', 'ar' => 'وادي النطرون'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 179,
            'name' => ['en' => 'New Nubaria', 'ar' => 'النوبارية الجديدة'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 180,
            'name' => ['en' => 'Alnoubareya', 'ar' => 'النوبارية'],
            'governorate_id' => 6
        ]);
        City::create([
            'id' => 181,
            'name' => ['en' => 'Fayoum', 'ar' => 'الفيوم'],
            'governorate_id' => 7
        ]);
        City::create([
            'id' => 182,
            'name' => ['en' => 'Fayoum El Gedida', 'ar' => 'الفيوم الجديدة'],
            'governorate_id' => 7
        ]);
        City::create([
            'id' => 183,
            'name' => ['en' => 'Tamiya', 'ar' => 'طامية'],
            'governorate_id' => 7
        ]);
        City::create([
            'id' => 184,
            'name' => ['en' => 'Snores', 'ar' => 'سنورس'],
            'governorate_id' => 7
        ]);
        City::create([
            'id' => 185,
            'name' => ['en' => 'Etsa', 'ar' => 'إطسا'],
            'governorate_id' => 7
        ]);
        City::create([
            'id' => 186,
            'name' => ['en' => 'Epschway', 'ar' => 'إبشواي'],
            'governorate_id' => 7
        ]);
        City::create([
            'id' => 187,
            'name' => ['en' => 'Yusuf El Sediaq', 'ar' => 'يوسف الصديق'],
            'governorate_id' => 7
        ]);
        City::create([
            'id' => 188,
            'name' => ['en' => 'Hadqa', 'ar' => 'الحادقة'],
            'governorate_id' => 7
        ]);
        City::create([
            'id' => 189,
            'name' => ['en' => 'Atsa', 'ar' => 'اطسا'],
            'governorate_id' => 7
        ]);
        City::create([
            'id' => 190,
            'name' => ['en' => 'Algamaa', 'ar' => 'الجامعة'],
            'governorate_id' => 7
        ]);
        City::create([
            'id' => 191,
            'name' => ['en' => 'Sayala', 'ar' => 'السيالة'],
            'governorate_id' => 7
        ]);
        City::create([
            'id' => 192,
            'name' => ['en' => 'Tanta', 'ar' => 'طنطا'],
            'governorate_id' => 8
        ]);
        City::create([
            'id' => 193,
            'name' => ['en' => 'Al Mahalla Al Kobra', 'ar' => 'المحلة الكبرى'],
            'governorate_id' => 8
        ]);
        City::create([
            'id' => 194,
            'name' => ['en' => 'Kafr El Zayat', 'ar' => 'كفر الزيات'],
            'governorate_id' => 8
        ]);
        City::create([
            'id' => 195,
            'name' => ['en' => 'Zefta', 'ar' => 'زفتى'],
            'governorate_id' => 8
        ]);
        City::create([
            'id' => 196,
            'name' => ['en' => 'El Santa', 'ar' => 'السنطة'],
            'governorate_id' => 8
        ]);
        City::create([
            'id' => 197,
            'name' => ['en' => 'Qutour', 'ar' => 'قطور'],
            'governorate_id' => 8
        ]);
        City::create([
            'id' => 198,
            'name' => ['en' => 'Basion', 'ar' => 'بسيون'],
            'governorate_id' => 8
        ]);
        City::create([
            'id' => 199,
            'name' => ['en' => 'Samannoud', 'ar' => 'سمنود'],
            'governorate_id' => 8
        ]);
        City::create([
            'id' => 200,
            'name' => ['en' => 'Ismailia', 'ar' => 'الإسماعيلية'],
            'governorate_id' => 9
        ]);
        City::create([
            'id' => 201,
            'name' => ['en' => 'Fayed', 'ar' => 'فايد'],
            'governorate_id' => 9
        ]);
        City::create([
            'id' => 202,
            'name' => ['en' => 'Qantara Sharq', 'ar' => 'القنطرة شرق'],
            'governorate_id' => 9
        ]);
        City::create([
            'id' => 203,
            'name' => ['en' => 'Qantara Gharb', 'ar' => 'القنطرة غرب'],
            'governorate_id' => 9
        ]);
        City::create([
            'id' => 204,
            'name' => ['en' => 'El Tal El Kabier', 'ar' => 'التل الكبير'],
            'governorate_id' => 9
        ]);
        City::create([
            'id' => 205,
            'name' => ['en' => 'Abu Sawir', 'ar' => 'أبو صوير'],
            'governorate_id' => 9
        ]);
        City::create([
            'id' => 206,
            'name' => ['en' => 'Kasasien El Gedida', 'ar' => 'القصاصين الجديدة'],
            'governorate_id' => 9
        ]);
        City::create([
            'id' => 207,
            'name' => ['en' => 'Nefesha', 'ar' => 'نفيشة'],
            'governorate_id' => 9
        ]);
        City::create([
            'id' => 208,
            'name' => ['en' => 'Sheikh Zayed', 'ar' => 'الشيخ زايد'],
            'governorate_id' => 9
        ]);
        City::create([
            'id' => 209,
            'name' => ['en' => 'Shbeen El Koom', 'ar' => 'شبين الكوم'],
            'governorate_id' => 10
        ]);
        City::create([
            'id' => 210,
            'name' => ['en' => 'Sadat City', 'ar' => 'مدينة السادات'],
            'governorate_id' => 10
        ]);
        City::create([
            'id' => 211,
            'name' => ['en' => 'Menouf', 'ar' => 'منوف'],
            'governorate_id' => 10
        ]);
        City::create([
            'id' => 212,
            'name' => ['en' => 'Sars El-Layan', 'ar' => 'سرس الليان'],
            'governorate_id' => 10
        ]);
        City::create([
            'id' => 213,
            'name' => ['en' => 'Ashmon', 'ar' => 'أشمون'],
            'governorate_id' => 10
        ]);
        City::create([
            'id' => 214,
            'name' => ['en' => 'Al Bagor', 'ar' => 'الباجور'],
            'governorate_id' => 10
        ]);
        City::create([
            'id' => 215,
            'name' => ['en' => 'Quesna', 'ar' => 'قويسنا'],
            'governorate_id' => 10
        ]);
        City::create([
            'id' => 216,
            'name' => ['en' => 'Berkat El Saba', 'ar' => 'بركة السبع'],
            'governorate_id' => 10
        ]);
        City::create([
            'id' => 217,
            'name' => ['en' => 'Tala', 'ar' => 'تلا'],
            'governorate_id' => 10
        ]);
        City::create([
            'id' => 218,
            'name' => ['en' => 'Al Shohada', 'ar' => 'الشهداء'],
            'governorate_id' => 10
        ]);
        City::create([
            'id' => 219,
            'name' => ['en' => 'Minya', 'ar' => 'المنيا'],
            'governorate_id' => 11
        ]);
        City::create([
            'id' => 220,
            'name' => ['en' => 'Minya El Gedida', 'ar' => 'المنيا الجديدة'],
            'governorate_id' => 11
        ]);
        City::create([
            'id' => 221,
            'name' => ['en' => 'El Adwa', 'ar' => 'العدوة'],
            'governorate_id' => 11
        ]);
        City::create([
            'id' => 222,
            'name' => ['en' => 'Magagha', 'ar' => 'مغاغة'],
            'governorate_id' => 11
        ]);
        City::create([
            'id' => 223,
            'name' => ['en' => 'Bani Mazar', 'ar' => 'بني مزار'],
            'governorate_id' => 11
        ]);
        City::create([
            'id' => 224,
            'name' => ['en' => 'Mattay', 'ar' => 'مطاي'],
            'governorate_id' => 11
        ]);
        City::create([
            'id' => 225,
            'name' => ['en' => 'Samalut', 'ar' => 'سمالوط'],
            'governorate_id' => 11
        ]);
        City::create([
            'id' => 226,
            'name' => ['en' => 'Madinat El Fekria', 'ar' => 'المدينة الفكرية'],
            'governorate_id' => 11
        ]);
        City::create([
            'id' => 227,
            'name' => ['en' => 'Meloy', 'ar' => 'ملوي'],
            'governorate_id' => 11
        ]);
        City::create([
            'id' => 228,
            'name' => ['en' => 'Deir Mawas', 'ar' => 'دير مواس'],
            'governorate_id' => 11
        ]);
        City::create([
            'id' => 229,
            'name' => ['en' => 'Abu Qurqas', 'ar' => 'ابو قرقاص'],
            'governorate_id' => 11
        ]);
        City::create([
            'id' => 230,
            'name' => ['en' => 'Ard Sultan', 'ar' => 'ارض سلطان'],
            'governorate_id' => 11
        ]);
        City::create([
            'id' => 231,
            'name' => ['en' => 'Banha', 'ar' => 'بنها'],
            'governorate_id' => 12
        ]);
        City::create([
            'id' => 232,
            'name' => ['en' => 'Qalyub', 'ar' => 'قليوب'],
            'governorate_id' => 12
        ]);
        City::create([
            'id' => 233,
            'name' => ['en' => 'Shubra Al Khaimah', 'ar' => 'شبرا الخيمة'],
            'governorate_id' => 12
        ]);
        City::create([
            'id' => 234,
            'name' => ['en' => 'Al Qanater Charity', 'ar' => 'القناطر الخيرية'],
            'governorate_id' => 12
        ]);
        City::create([
            'id' => 235,
            'name' => ['en' => 'Khanka', 'ar' => 'الخانكة'],
            'governorate_id' => 12
        ]);
        City::create([
            'id' => 236,
            'name' => ['en' => 'Kafr Shukr', 'ar' => 'كفر شكر'],
            'governorate_id' => 12
        ]);
        City::create([
            'id' => 237,
            'name' => ['en' => 'Tukh', 'ar' => 'طوخ'],
            'governorate_id' => 12
        ]);
        City::create([
            'id' => 238,
            'name' => ['en' => 'Qaha', 'ar' => 'قها'],
            'governorate_id' => 12
        ]);
        City::create([
            'id' => 239,
            'name' => ['en' => 'Obour', 'ar' => 'العبور'],
            'governorate_id' => 12
        ]);
        City::create([
            'id' => 240,
            'name' => ['en' => 'Khosous', 'ar' => 'الخصوص'],
            'governorate_id' => 12
        ]);
        City::create([
            'id' => 241,
            'name' => ['en' => 'Shibin Al Qanater', 'ar' => 'شبين القناطر'],
            'governorate_id' => 12
        ]);
        City::create([
            'id' => 242,
            'name' => ['en' => 'Mostorod', 'ar' => 'مسطرد'],
            'governorate_id' => 12
        ]);
        City::create([
            'id' => 243,
            'name' => ['en' => 'El Kharga', 'ar' => 'الخارجة'],
            'governorate_id' => 13
        ]);
        City::create([
            'id' => 244,
            'name' => ['en' => 'Paris', 'ar' => 'باريس'],
            'governorate_id' => 13
        ]);
        City::create([
            'id' => 245,
            'name' => ['en' => 'Mout', 'ar' => 'موط'],
            'governorate_id' => 13
        ]);
        City::create([
            'id' => 246,
            'name' => ['en' => 'Farafra', 'ar' => 'الفرافرة'],
            'governorate_id' => 13
        ]);
        City::create([
            'id' => 247,
            'name' => ['en' => 'Balat', 'ar' => 'بلاط'],
            'governorate_id' => 13
        ]);
        City::create([
            'id' => 248,
            'name' => ['en' => 'Dakhla', 'ar' => 'الداخلة'],
            'governorate_id' => 13
        ]);
        City::create([
            'id' => 249,
            'name' => ['en' => 'Suez', 'ar' => 'السويس'],
            'governorate_id' => 14
        ]);
        City::create([
            'id' => 250,
            'name' => ['en' => 'Alganayen', 'ar' => 'الجناين'],
            'governorate_id' => 14
        ]);
        City::create([
            'id' => 251,
            'name' => ['en' => 'Ataqah', 'ar' => 'عتاقة'],
            'governorate_id' => 14
        ]);
        City::create([
            'id' => 252,
            'name' => ['en' => 'Ain Sokhna', 'ar' => 'العين السخنة'],
            'governorate_id' => 14
        ]);
        City::create([
            'id' => 253,
            'name' => ['en' => 'Faysal', 'ar' => 'فيصل'],
            'governorate_id' => 14
        ]);
        City::create([
            'id' => 254,
            'name' => ['en' => 'Aswan', 'ar' => 'أسوان'],
            'governorate_id' => 15
        ]);
        City::create([
            'id' => 255,
            'name' => ['en' => 'Aswan El Gedida', 'ar' => 'أسوان الجديدة'],
            'governorate_id' => 15
        ]);
        City::create([
            'id' => 256,
            'name' => ['en' => 'Drau', 'ar' => 'دراو'],
            'governorate_id' => 15
        ]);
        City::create([
            'id' => 257,
            'name' => ['en' => 'Kom Ombo', 'ar' => 'كوم أمبو'],
            'governorate_id' => 15
        ]);
        City::create([
            'id' => 258,
            'name' => ['en' => 'Nasr Al Nuba', 'ar' => 'نصر النوبة'],
            'governorate_id' => 15
        ]);
        City::create([
            'id' => 259,
            'name' => ['en' => 'Kalabsha', 'ar' => 'كلابشة'],
            'governorate_id' => 15
        ]);
        City::create([
            'id' => 260,
            'name' => ['en' => 'Edfu', 'ar' => 'إدفو'],
            'governorate_id' => 15
        ]);
        City::create([
            'id' => 261,
            'name' => ['en' => 'Al-Radisiyah', 'ar' => 'الرديسية'],
            'governorate_id' => 15
        ]);
        City::create([
            'id' => 262,
            'name' => ['en' => 'Al Basilia', 'ar' => 'البصيلية'],
            'governorate_id' => 15
        ]);
        City::create([
            'id' => 263,
            'name' => ['en' => 'Al Sibaeia', 'ar' => 'السباعية'],
            'governorate_id' => 15
        ]);
        City::create([
            'id' => 264,
            'name' => ['en' => 'Abo Simbl Al Siyahia', 'ar' => 'ابوسمبل السياحية'],
            'governorate_id' => 15
        ]);
        City::create([
            'id' => 265,
            'name' => ['en' => 'Marsa Alam', 'ar' => 'مرسى علم'],
            'governorate_id' => 15
        ]);
        City::create([
            'id' => 266,
            'name' => ['en' => 'Assiut', 'ar' => 'أسيوط'],
            'governorate_id' => 16
        ]);
        City::create([
            'id' => 267,
            'name' => ['en' => 'Assiut El Gedida', 'ar' => 'أسيوط الجديدة'],
            'governorate_id' => 16
        ]);
        City::create([
            'id' => 268,
            'name' => ['en' => 'Dayrout', 'ar' => 'ديروط'],
            'governorate_id' => 16
        ]);
        City::create([
            'id' => 269,
            'name' => ['en' => 'Manfalut', 'ar' => 'منفلوط'],
            'governorate_id' => 16
        ]);
        City::create([
            'id' => 270,
            'name' => ['en' => 'Qusiya', 'ar' => 'القوصية'],
            'governorate_id' => 16
        ]);
        City::create([
            'id' => 271,
            'name' => ['en' => 'Abnoub', 'ar' => 'أبنوب'],
            'governorate_id' => 16
        ]);
        City::create([
            'id' => 272,
            'name' => ['en' => 'Abu Tig', 'ar' => 'أبو تيج'],
            'governorate_id' => 16
        ]);
        City::create([
            'id' => 273,
            'name' => ['en' => 'El Ghanaim', 'ar' => 'الغنايم'],
            'governorate_id' => 16
        ]);
        City::create([
            'id' => 274,
            'name' => ['en' => 'Sahel Selim', 'ar' => 'ساحل سليم'],
            'governorate_id' => 16
        ]);
        City::create([
            'id' => 275,
            'name' => ['en' => 'El Badari', 'ar' => 'البداري'],
            'governorate_id' => 16
        ]);
        City::create([
            'id' => 276,
            'name' => ['en' => 'Sidfa', 'ar' => 'صدفا'],
            'governorate_id' => 16
        ]);
        City::create([
            'id' => 277,
            'name' => ['en' => 'Bani Sweif', 'ar' => 'بني سويف'],
            'governorate_id' => 17
        ]);
        City::create([
            'id' => 278,
            'name' => ['en' => 'Beni Suef El Gedida', 'ar' => 'بني سويف الجديدة'],
            'governorate_id' => 17
        ]);
        City::create([
            'id' => 279,
            'name' => ['en' => 'Al Wasta', 'ar' => 'الواسطى'],
            'governorate_id' => 17
        ]);
        City::create([
            'id' => 280,
            'name' => ['en' => 'Naser', 'ar' => 'ناصر'],
            'governorate_id' => 17
        ]);
        City::create([
            'id' => 281,
            'name' => ['en' => 'Ehnasia', 'ar' => 'إهناسيا'],
            'governorate_id' => 17
        ]);
        City::create([
            'id' => 282,
            'name' => ['en' => 'beba', 'ar' => 'ببا'],
            'governorate_id' => 17
        ]);
        City::create([
            'id' => 283,
            'name' => ['en' => 'Fashn', 'ar' => 'الفشن'],
            'governorate_id' => 17
        ]);
        City::create([
            'id' => 284,
            'name' => ['en' => 'Somasta', 'ar' => 'سمسطا'],
            'governorate_id' => 17
        ]);
        City::create([
            'id' => 285,
            'name' => ['en' => 'Alabbaseri', 'ar' => 'الاباصيرى'],
            'governorate_id' => 17
        ]);
        City::create([
            'id' => 286,
            'name' => ['en' => 'Mokbel', 'ar' => 'مقبل'],
            'governorate_id' => 17
        ]);
        City::create([
            'id' => 287,
            'name' => ['en' => 'PorSaid', 'ar' => 'بورسعيد'],
            'governorate_id' => 18
        ]);
        City::create([
            'id' => 288,
            'name' => ['en' => 'Port Fouad', 'ar' => 'بورفؤاد'],
            'governorate_id' => 18
        ]);
        City::create([
            'id' => 289,
            'name' => ['en' => 'Alarab', 'ar' => 'العرب'],
            'governorate_id' => 18
        ]);
        City::create([
            'id' => 290,
            'name' => ['en' => 'Zohour', 'ar' => 'حى الزهور'],
            'governorate_id' => 18
        ]);
        City::create([
            'id' => 291,
            'name' => ['en' => 'Alsharq', 'ar' => 'حى الشرق'],
            'governorate_id' => 18
        ]);
        City::create([
            'id' => 292,
            'name' => ['en' => 'Aldawahi', 'ar' => 'حى الضواحى'],
            'governorate_id' => 18
        ]);
        City::create([
            'id' => 293,
            'name' => ['en' => 'Almanakh', 'ar' => 'حى المناخ'],
            'governorate_id' => 18
        ]);
        City::create([
            'id' => 294,
            'name' => ['en' => 'Mubarak', 'ar' => 'حى مبارك'],
            'governorate_id' => 18
        ]);
        City::create([
            'id' => 295,
            'name' => ['en' => 'Damietta', 'ar' => 'دمياط'],
            'governorate_id' => 19
        ]);
        City::create([
            'id' => 296,
            'name' => ['en' => 'New Damietta', 'ar' => 'دمياط الجديدة'],
            'governorate_id' => 19
        ]);
        City::create([
            'id' => 297,
            'name' => ['en' => 'Ras El Bar', 'ar' => 'رأس البر'],
            'governorate_id' => 19
        ]);
        City::create([
            'id' => 298,
            'name' => ['en' => 'Faraskour', 'ar' => 'فارسكور'],
            'governorate_id' => 19
        ]);
        City::create([
            'id' => 299,
            'name' => ['en' => 'Zarqa', 'ar' => 'الزرقا'],
            'governorate_id' => 19
        ]);
        City::create([
            'id' => 300,
            'name' => ['en' => 'alsaru', 'ar' => 'السرو'],
            'governorate_id' => 19
        ]);
        City::create([
            'id' => 301,
            'name' => ['en' => 'alruwda', 'ar' => 'الروضة'],
            'governorate_id' => 19
        ]);
        City::create([
            'id' => 302,
            'name' => ['en' => 'Kafr El-Batikh', 'ar' => 'كفر البطيخ'],
            'governorate_id' => 19
        ]);
        City::create([
            'id' => 303,
            'name' => ['en' => 'Azbet Al Burg', 'ar' => 'عزبة البرج'],
            'governorate_id' => 19
        ]);
        City::create([
            'id' => 304,
            'name' => ['en' => 'Meet Abou Ghalib', 'ar' => 'ميت أبو غالب'],
            'governorate_id' => 19
        ]);
        City::create([
            'id' => 305,
            'name' => ['en' => 'Kafr Saad', 'ar' => 'كفر سعد'],
            'governorate_id' => 19
        ]);
        City::create([
            'id' => 306,
            'name' => ['en' => 'Zagazig', 'ar' => 'الزقازيق'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 307,
            'name' => ['en' => 'Al Ashr Men Ramadan', 'ar' => 'العاشر من رمضان'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 308,
            'name' => ['en' => 'Minya Al Qamh', 'ar' => 'منيا القمح'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 309,
            'name' => ['en' => 'Belbeis', 'ar' => 'بلبيس'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 310,
            'name' => ['en' => 'Mashtoul El Souq', 'ar' => 'مشتول السوق'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 311,
            'name' => ['en' => 'Qenaiat', 'ar' => 'القنايات'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 312,
            'name' => ['en' => 'Abu Hammad', 'ar' => 'أبو حماد'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 313,
            'name' => ['en' => 'El Qurain', 'ar' => 'القرين'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 314,
            'name' => ['en' => 'Hehia', 'ar' => 'ههيا'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 315,
            'name' => ['en' => 'Abu Kabir', 'ar' => 'أبو كبير'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 316,
            'name' => ['en' => 'Faccus', 'ar' => 'فاقوس'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 317,
            'name' => ['en' => 'El Salihia El Gedida', 'ar' => 'الصالحية الجديدة'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 318,
            'name' => ['en' => 'Al Ibrahimiyah', 'ar' => 'الإبراهيمية'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 319,
            'name' => ['en' => 'Deirb Negm', 'ar' => 'ديرب نجم'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 320,
            'name' => ['en' => 'Kafr Saqr', 'ar' => 'كفر صقر'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 321,
            'name' => ['en' => 'Awlad Saqr', 'ar' => 'أولاد صقر'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 322,
            'name' => ['en' => 'Husseiniya', 'ar' => 'الحسينية'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 323,
            'name' => ['en' => 'san alhajar alqablia', 'ar' => 'صان الحجر القبلية'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 324,
            'name' => ['en' => 'Manshayat Abu Omar', 'ar' => 'منشأة أبو عمر'],
            'governorate_id' => 20
        ]);
        City::create([
            'id' => 325,
            'name' => ['en' => 'Al Toor', 'ar' => 'الطور'],
            'governorate_id' => 21
        ]);
        City::create([
            'id' => 326,
            'name' => ['en' => 'Sharm El-Shaikh', 'ar' => 'شرم الشيخ'],
            'governorate_id' => 21
        ]);
        City::create([
            'id' => 327,
            'name' => ['en' => 'Dahab', 'ar' => 'دهب'],
            'governorate_id' => 21
        ]);
        City::create([
            'id' => 328,
            'name' => ['en' => 'Nuweiba', 'ar' => 'نويبع'],
            'governorate_id' => 21
        ]);
        City::create([
            'id' => 329,
            'name' => ['en' => 'Taba', 'ar' => 'طابا'],
            'governorate_id' => 21
        ]);
        City::create([
            'id' => 330,
            'name' => ['en' => 'Saint Catherine', 'ar' => 'سانت كاترين'],
            'governorate_id' => 21
        ]);
        City::create([
            'id' => 331,
            'name' => ['en' => 'Abu Redis', 'ar' => 'أبو رديس'],
            'governorate_id' => 21
        ]);
        City::create([
            'id' => 332,
            'name' => ['en' => 'Abu Zenaima', 'ar' => 'أبو زنيمة'],
            'governorate_id' => 21
        ]);
        City::create([
            'id' => 333,
            'name' => ['en' => 'Ras Sidr', 'ar' => 'رأس سدر'],
            'governorate_id' => 21
        ]);
        City::create([
            'id' => 334,
            'name' => ['en' => 'Kafr El Sheikh', 'ar' => 'كفر الشيخ'],
            'governorate_id' => 22
        ]);
        City::create([
            'id' => 335,
            'name' => ['en' => 'Kafr El Sheikh Downtown', 'ar' => 'وسط البلد كفر الشيخ'],
            'governorate_id' => 22
        ]);
        City::create([
            'id' => 336,
            'name' => ['en' => 'Desouq', 'ar' => 'دسوق'],
            'governorate_id' => 22
        ]);
        City::create([
            'id' => 337,
            'name' => ['en' => 'Fooh', 'ar' => 'فوه'],
            'governorate_id' => 22
        ]);
        City::create([
            'id' => 338,
            'name' => ['en' => 'Metobas', 'ar' => 'مطوبس'],
            'governorate_id' => 22
        ]);
        City::create([
            'id' => 339,
            'name' => ['en' => 'Burg Al Burullus', 'ar' => 'برج البرلس'],
            'governorate_id' => 22
        ]);
        City::create([
            'id' => 340,
            'name' => ['en' => 'Baltim', 'ar' => 'بلطيم'],
            'governorate_id' => 22
        ]);
        City::create([
            'id' => 341,
            'name' => ['en' => 'Masief Baltim', 'ar' => 'مصيف بلطيم'],
            'governorate_id' => 22
        ]);
        City::create([
            'id' => 342,
            'name' => ['en' => 'Hamol', 'ar' => 'الحامول'],
            'governorate_id' => 22
        ]);
        City::create([
            'id' => 343,
            'name' => ['en' => 'Bella', 'ar' => 'بيلا'],
            'governorate_id' => 22
        ]);
        City::create([
            'id' => 344,
            'name' => ['en' => 'Riyadh', 'ar' => 'الرياض'],
            'governorate_id' => 22
        ]);
        City::create([
            'id' => 345,
            'name' => ['en' => 'Sidi Salm', 'ar' => 'سيدي سالم'],
            'governorate_id' => 22
        ]);
        City::create([
            'id' => 346,
            'name' => ['en' => 'Qellen', 'ar' => 'قلين'],
            'governorate_id' => 22
        ]);
        City::create([
            'id' => 347,
            'name' => ['en' => 'Sidi Ghazi', 'ar' => 'سيدي غازي'],
            'governorate_id' => 22
        ]);
        City::create([
            'id' => 348,
            'name' => ['en' => 'Marsa Matrouh', 'ar' => 'مرسى مطروح'],
            'governorate_id' => 23
        ]);
        City::create([
            'id' => 349,
            'name' => ['en' => 'El Hamam', 'ar' => 'الحمام'],
            'governorate_id' => 23
        ]);
        City::create([
            'id' => 350,
            'name' => ['en' => 'Alamein', 'ar' => 'العلمين'],
            'governorate_id' => 23
        ]);
        City::create([
            'id' => 351,
            'name' => ['en' => 'Dabaa', 'ar' => 'الضبعة'],
            'governorate_id' => 23
        ]);
        City::create([
            'id' => 352,
            'name' => ['en' => 'Al-Nagila', 'ar' => 'النجيلة'],
            'governorate_id' => 23
        ]);
        City::create([
            'id' => 353,
            'name' => ['en' => 'Sidi Brani', 'ar' => 'سيدي براني'],
            'governorate_id' => 23
        ]);
        City::create([
            'id' => 354,
            'name' => ['en' => 'Salloum', 'ar' => 'السلوم'],
            'governorate_id' => 23
        ]);
        City::create([
            'id' => 355,
            'name' => ['en' => 'Siwa', 'ar' => 'سيوة'],
            'governorate_id' => 23
        ]);
        City::create([
            'id' => 356,
            'name' => ['en' => 'Marina', 'ar' => 'مارينا'],
            'governorate_id' => 23
        ]);
        City::create([
            'id' => 357,
            'name' => ['en' => 'North Coast', 'ar' => 'الساحل الشمالى'],
            'governorate_id' => 23
        ]);
        City::create([
            'id' => 358,
            'name' => ['en' => 'Luxor', 'ar' => 'الأقصر'],
            'governorate_id' => 24
        ]);
        City::create([
            'id' => 359,
            'name' => ['en' => 'New Luxor', 'ar' => 'الأقصر الجديدة'],
            'governorate_id' => 24
        ]);
        City::create([
            'id' => 360,
            'name' => ['en' => 'Esna', 'ar' => 'إسنا'],
            'governorate_id' => 24
        ]);
        City::create([
            'id' => 361,
            'name' => ['en' => 'New Tiba', 'ar' => 'طيبة الجديدة'],
            'governorate_id' => 24
        ]);
        City::create([
            'id' => 362,
            'name' => ['en' => 'Al ziynia', 'ar' => 'الزينية'],
            'governorate_id' => 24
        ]);
        City::create([
            'id' => 363,
            'name' => ['en' => 'Al Bayadieh', 'ar' => 'البياضية'],
            'governorate_id' => 24
        ]);
        City::create([
            'id' => 364,
            'name' => ['en' => 'Al Qarna', 'ar' => 'القرنة'],
            'governorate_id' => 24
        ]);
        City::create([
            'id' => 365,
            'name' => ['en' => 'Armant', 'ar' => 'أرمنت'],
            'governorate_id' => 24
        ]);
        City::create([
            'id' => 366,
            'name' => ['en' => 'Al Tud', 'ar' => 'الطود'],
            'governorate_id' => 24
        ]);
        City::create([
            'id' => 367,
            'name' => ['en' => 'Qena', 'ar' => 'قنا'],
            'governorate_id' => 25
        ]);
        City::create([
            'id' => 368,
            'name' => ['en' => 'New Qena', 'ar' => 'قنا الجديدة'],
            'governorate_id' => 25
        ]);
        City::create([
            'id' => 369,
            'name' => ['en' => 'Abu Tesht', 'ar' => 'ابو طشت'],
            'governorate_id' => 25
        ]);
        City::create([
            'id' => 370,
            'name' => ['en' => 'Nag Hammadi', 'ar' => 'نجع حمادي'],
            'governorate_id' => 25
        ]);
        City::create([
            'id' => 371,
            'name' => ['en' => 'Deshna', 'ar' => 'دشنا'],
            'governorate_id' => 25
        ]);
        City::create([
            'id' => 372,
            'name' => ['en' => 'Alwaqf', 'ar' => 'الوقف'],
            'governorate_id' => 25
        ]);
        City::create([
            'id' => 373,
            'name' => ['en' => 'Qaft', 'ar' => 'قفط'],
            'governorate_id' => 25
        ]);
        City::create([
            'id' => 374,
            'name' => ['en' => 'Naqada', 'ar' => 'نقادة'],
            'governorate_id' => 25
        ]);
        City::create([
            'id' => 375,
            'name' => ['en' => 'Farshout', 'ar' => 'فرشوط'],
            'governorate_id' => 25
        ]);
        City::create([
            'id' => 376,
            'name' => ['en' => 'Quos', 'ar' => 'قوص'],
            'governorate_id' => 25
        ]);
        City::create([
            'id' => 377,
            'name' => ['en' => 'Arish', 'ar' => 'العريش'],
            'governorate_id' => 26
        ]);
        City::create([
            'id' => 378,
            'name' => ['en' => 'Sheikh Zowaid', 'ar' => 'الشيخ زويد'],
            'governorate_id' => 26
        ]);
        City::create([
            'id' => 379,
            'name' => ['en' => 'Nakhl', 'ar' => 'نخل'],
            'governorate_id' => 26
        ]);
        City::create([
            'id' => 380,
            'name' => ['en' => 'Rafah', 'ar' => 'رفح'],
            'governorate_id' => 26
        ]);
        City::create([
            'id' => 381,
            'name' => ['en' => 'Bir al-Abed', 'ar' => 'بئر العبد'],
            'governorate_id' => 26
        ]);
        City::create([
            'id' => 382,
            'name' => ['en' => 'Al Hasana', 'ar' => 'الحسنة'],
            'governorate_id' => 26
        ]);
        City::create([
            'id' => 383,
            'name' => ['en' => 'Sohag', 'ar' => 'سوهاج'],
            'governorate_id' => 27
        ]);
        City::create([
            'id' => 384,
            'name' => ['en' => 'Sohag El Gedida', 'ar' => 'سوهاج الجديدة'],
            'governorate_id' => 27
        ]);
        City::create([
            'id' => 385,
            'name' => ['en' => 'Akhmeem', 'ar' => 'أخميم'],
            'governorate_id' => 27
        ]);
        City::create([
            'id' => 386,
            'name' => ['en' => 'Akhmim El Gedida', 'ar' => 'أخميم الجديدة'],
            'governorate_id' => 27
        ]);
        City::create([
            'id' => 387,
            'name' => ['en' => 'Albalina', 'ar' => 'البلينا'],
            'governorate_id' => 27
        ]);
        City::create([
            'id' => 388,
            'name' => ['en' => 'El Maragha', 'ar' => 'المراغة'],
            'governorate_id' => 27
        ]);
        City::create([
            'id' => 389,
            'name' => ['en' => 'almunshaa', 'ar' => 'المنشأة'],
            'governorate_id' => 27
        ]);
        City::create([
            'id' => 390,
            'name' => ['en' => 'Dar AISalaam', 'ar' => 'دار السلام'],
            'governorate_id' => 27
        ]);
        City::create([
            'id' => 391,
            'name' => ['en' => 'Gerga', 'ar' => 'جرجا'],
            'governorate_id' => 27
        ]);
        City::create([
            'id' => 392,
            'name' => ['en' => 'Jahina Al Gharbia', 'ar' => 'جهينة الغربية'],
            'governorate_id' => 27
        ]);
        City::create([
            'id' => 393,
            'name' => ['en' => 'Saqilatuh', 'ar' => 'ساقلته'],
            'governorate_id' => 27
        ]);
        City::create([
            'id' => 394,
            'name' => ['en' => 'Tama', 'ar' => 'طما'],
            'governorate_id' => 27
        ]);
        City::create([
            'id' => 395,
            'name' => ['en' => 'Tahta', 'ar' => 'طهطا'],
            'governorate_id' => 27
        ]);
        City::create([
            'id' => 396,
            'name' => ['en' => 'Alkawthar', 'ar' => 'الكوثر'],
            'governorate_id' => 27
        ]);

    }
}
