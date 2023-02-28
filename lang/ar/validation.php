<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted'             => 'يجب قبول حقل :attribute',
    'active_url'           => 'حقل :attribute لا يُمثّل رابطًا صحيحًا',
    'after'                => 'يجب على حقل :attribute أن يكون تاريخًا لاحقًا للتاريخ :date.',
    'after_or_equal'       => 'حقل :attribute يجب أن يكون تاريخاً لاحقاً أو مطابقاً للتاريخ :date.',
    'alpha'                => 'يجب أن لا يحتوي حقل :attribute سوى على حروف',
    'alpha_dash'           => 'يجب أن لا يحتوي حقل :attribute على حروف، أرقام ومطّات.',
    'alpha_num'            => 'يجب أن يحتوي :attribute على حروفٍ وأرقامٍ فقط',
    'array'                => 'يجب أن يكون حقل :attribute ًمصفوفة',
    'before'               => 'يجب على حقل :attribute أن يكون تاريخًا سابقًا لتاريخ :date.',
    'before_or_equal'      => 'حقل :attribute يجب أن يكون تاريخا سابقا أو مطابقا للتاريخ :date',
    'between'              => [
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
        'file'    => 'يجب أن يكون حجم الملف :attribute بين :min و :max كيلوبايت.',
        'string'  => 'يجب أن يكون عدد حروف النّص :attribute بين :min و :max',
        'array'   => 'يجب أن يحتوي :attribute على عدد من العناصر بين :min و :max',
    ],
    'boolean'              => 'يجب أن تكون قيمة حقل :attribute إما true أو false ',
    'confirmed'            => 'حقل التأكيد غير مُطابق للحقل :attribute',
    'date'                 => 'حقل :attribute ليس تاريخًا صحيحًا',
    'date_format'          => 'لا يتوافق حقل :attribute مع الشكل :format.',
    'different'            => 'يجب أن يكون حقلان :attribute و :other مُختلفان',
    'digits'               => 'يجب أن يحتوي حقل :attribute على :digits رقمًا/أرقام',
    'digits_between'       => 'يجب أن يحتوي حقل :attribute بين :min و :max رقمًا/أرقام ',
    'dimensions'           => 'الـ :attribute يحتوي على أبعاد صورة غير صالحة.',
    'distinct'             => 'للحقل :attribute قيمة مُكرّرة.',
    'email'                => 'يجب أن يحتوي :attribute على علامة @',
    'exists'               => 'قيمة حقل :attribute غير موجودة بقاعدة البيانات',
    'file'                 => 'الـ :attribute يجب أن يكون من ملفا.',
    'filled'               => 'حقل :attribute إجباري',
    'image'                => 'يجب أن يكون حقل :attribute صورةً',
    'in'                   => 'حقل :attribute الذي تم اختياره غير موجود بقاعدة البيانات',
    'in_array'             => 'حقل :attribute غير موجود في :other.',
    'integer'              => 'يجب أن يكون حقل :attribute عددًا صحيحًا',
    'ip'                   => 'يجب أن يكون حقل :attribute عنوان IP ذا بُنية صحيحة',
    'ipv4'                 => 'يجب أن يكون حقل :attribute عنوان IPv4 ذا بنية صحيحة.',
    'ipv6'                 => 'يجب أن يكون حقل :attribute عنوان IPv6 ذا بنية صحيحة.',
    'json'                 => 'يجب أن يكون حقل :attribute نصا من نوع JSON.',
    'lte' => [
        'numeric' => 'حقل :attribute لابد أن يكون أقل من أو يساوي :value.',
        'file' => 'حقل :attribute لابد أن يكون أقل من أو يساوي :value كيلوبايت.',
        'string' => 'حقل :attribute لابد أن يكون أقل من أو يساوي :value حرف.',
        'array' => 'حقل :attribute لا يمكن أن يحتوي على أكثر من :value عناصر/عنصر.',
    ],
    'max'                  => [
        'numeric' => 'يجب أن تكون قيمة حقل :attribute مساوية أو أصغر من :max.',
        'file'    => 'يجب أن لا يتجاوز حجم ملف :attribute :max كيلوبايت',
        'photo'    => 'يجب أن لا يتجاوز حجم ملف :attribute :max كيلوبايت',
        'string'  => 'يجب أن لا يتجاوز طول نص :attribute :max حروفٍ/حرفًا',
        'array'   => 'يجب أن لا يحتوي حقل :attribute على أكثر من :max عناصر/عنصر.',
    ],
    'mimes'                => 'يجب أن يكون الحقل ملفًا من نوع : :values.',
    'mimetypes'            => 'يجب أن يكون الحقل ملفًا من نوع : :values.',
    'min'                  => [
        'numeric' => 'يجب أن تكون قيمة حقل :attribute مساوية أو أكبر من :min.',
        'file'    => 'يجب أن يكون حجم ملف :attribute على الأقل :min كيلوبايت',
        'string'  => 'يجب أن يكون طول نص :attribute على الأقل :min حروفٍ/حرفًا',
        'array'   => 'يجب أن يحتوي حقل :attribute على الأقل على :min عُنصرًا/عناصر',
    ],
    'not_in'               => 'حقل :attribute لاغٍ',
    'numeric'              => 'يجب أن يكون حقل :attribute عبارة عن أرقام',
    'present'              => 'يجب تقديم حقل :attribute',
    'regex'                => 'صيغة حقل :attribute غير صحيحة',
    'required'             => 'حقل :attribute مطلوب.',
    'required_if'          => 'حقل :attribute مطلوب في حال ما إذا كان :other يساوي :value.',
    'required_unless'      => 'حقل :attribute مطلوب في حال ما لم يكن :other يساوي :values.',
    'required_with'        => 'حقل :attribute مطلوب إذا توفّر :values.',
    'required_with_all'    => 'حقل :attributeمطلوب إذا توفّر :values.',
    'required_without'     => 'حقل :attribute مطلوب إذا لم يتوفّر :values.',
    'required_without_all' => 'حقل :attribute مطلوب إذا لم يتوفّر :values.',
    'same'                 => 'يجب أن يتطابق حقل :attribute مع :other',
    'size'                 => [
        'numeric' => 'يجب أن تكون قيمة حقل :attribute مساوية لـ :size',
        'file'    => 'يجب أن يكون حجم ملف :attribute :size كيلوبايت',
        'string'  => 'يجب أن يحتوي نص :attribute على :size حروفٍ/حرفًا بالظبط',
        'array'   => 'يجب أن يحتوي حقل :attribute على :size عنصرٍ/عناصر بالظبط',
    ],
    'string'               => 'يجب أن يكون حقل :attribute نصآ.',
    'timezone'             => 'يجب أن يكون :attribute نطاقًا زمنيًا صحيحًا',
    'unique'               => 'قيمة حقل :attribute مُستخدمه من قبل',
    'uploaded'             => 'فشل في تحميل :attribute',
    'url'                  => 'صيغة رابط :attribute غير صحيحة',
    'The Email Address is required when role is admin.' => 'حقل البريد الالكتروني مطلوب عندما يكون الدور مدير',
    'The phone numbers must contain digits between 8 & 11' => 'يجب أن تحتوي أرقام التليفونات على أرقم تتكون من 8 إلى 11 رقم',
    "maxif" => ":attribute لابد أن تكون أقل من 100%",
    "The offer is required." => "العرض مطلوب.",
    "Products are required." => "المنتجات مطلوبة.",
    "Banners are required." => "البنرات مطلوبة.",
    'The phone number field is required' => 'حقل رقم الهاتف مطلوب',
    'The phone numbers must start with 010, 011, 012 or 015' => 'حقل رقم الهاتف لابد أن يبدأ بـ 010 أو 011 أو 012 أو 015',
    'arabic name required' => 'الاسم بالعربية مطلوب',
    'english name required' => 'الاسم بالإنجليزية مطلوب',
    'url required' => 'الرابط مطلوب',


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name'                  => 'الاسم',
        'name.ar'               => 'الاسم بالعربية',
        'name.en'               => 'الاسم بالإنجليزية',
        'first_name_en'         => 'الاسم الأول بالإنجليزية',
        'f_name.en'             => 'الاسم الأول بالإنجليزية',
        'last_name_en'          => 'اسم العائلة بالإنجليزية',
        'l_name.en'             => 'اسم العائلة بالإنجليزية',
        'first_name_ar'         => 'الاسم الأول بالعربية',
        'f_name.ar'             => 'الاسم الأول بالعربية',
        'last_name_ar'          => 'اسم العائلة بالعربية',
        'l_name.ar'             => 'اسم العائلة بالعربية',
        'username'              => 'اسم المُستخدم',
        'email'                 => 'البريد الالكتروني',
        'first_name'            => 'الاسم الأول',
        'last_name'             => 'اسم العائلة',
        'password'              => 'كلمة المرور',
        'password_confirmation' => 'تأكيد كلمة المرور',
        'new_password' => 'كلمة المرور الجديدة',
        'old_password' => 'كلمة المرور القديمة',
        'new_password_confirmation' => 'تأكيد كلمة المرور',
        'city'                  => 'المدينة',
        'country'               => 'الدولة',
        'address'               => 'العنوان',
        'phone'                 => 'الهاتف',
        'mobile'                => 'الجوال',
        'age'                   => 'العمر',
        'sex'                   => 'الجنس',
        'role'                  => 'الدور',
        'role_id'               => 'الدور',
        'gender'                => 'النوع',
        'day'                   => 'اليوم',
        'month'                 => 'الشهر',
        'year'                  => 'السنة',
        'hour'                  => 'ساعة',
        'minute'                => 'دقيقة',
        'second'                => 'ثانية',
        'content'               => 'المُحتوى',
        'description'           => 'الوصف',
        'excerpt'               => 'المُلخص',
        'date'                  => 'التاريخ',
        'time'                  => 'الوقت',
        'available'             => 'مُتاح',
        'size'                  => 'الحجم',
        'price'                 => 'السعر',
        'desc'                  => 'نبذه',
        'title'                 => 'العنوان',
        'q'                     => 'البحث',
        'link'                  => ' ',
        'slug'                  => ' ',
        'photo'                 => 'الصورة',
        'birth_date'            => 'تاريخ الميلاد',
        'choseCountry'          =>  'البلد',
        'choseGovernorate'      =>  'المحافظة',
        'choseCity'             =>  'المدينة',
        'addresses.*.country_id'        =>  'الدولة',
        'addresses.*.governorate_id'    =>  'المحافظ',
        'addresses.*.city_id'           =>  'المدينة',
        'addresses.*.details'           =>  'تفاصيل العنوان',
        'addresses.*.landmarks'    =>  'العلامة المميزة',
        'selectedPermissions' => 'الصلاحيات',
        'phones.*.phone' => 'الهاتف',
        'name.ar' => "الاسم بالعربية",
        'name.en' => "الاسم بالإنجليزية",
        'zones.*.name.ar' => 'الاسم بالعربية',
        'zones.*.name.en' => 'الاسم بالإنجليزية',
        'zones.*.min_charge' => 'المصاريف الأساسية',
        'zones.*.min_weight' => 'الوزن الأساسي',
        'zones.*.kg_charge' => 'المصاريف لكل كجم زائد',
        'zones.*.destinations.*.country_id'     => 'البلد',
        'zones.*.destinations.*.governorate_id' => 'المحافظة',
        'country_id'            => 'البلد',
        'governorate_id'        => 'المحافظة',
        'video'                 => 'رابط الفيديو',
        'brand_id'              => 'العلامة التجارية',
        'subcategory_id'        => 'القسم المباشر',
        'model'                 => 'الموديل',
        'barcode'               => 'الباركود',
        'base_price'            => 'السعر قبل الخصم',
        'discount'              => 'الخصم',
        'final_price'           => 'السعر النهائي',
        'points'                => 'النقاط',
        'quantity'              => 'الكمية',
        'low_stock'             => 'الحد الطلب',
        'title'                 => 'العنوان',
        'weight'                => 'الوزن',
        'logo'                  => 'الشعار',
        'supercategory_id'      => 'القسم الرئيسي',
        'category_id'           => 'القسم الفرعي',
        'parentCategories.*.supercategory_id'     => 'القسم الرئيسي',
        'parentCategories.*.category_id'          => 'القسم الفرعي',
        'parentCategories.*.subcategory_id'       => 'القسم المباشر',
        'code'                  => 'الكود',
        'type'                  => 'النوع',
        'value'                 => 'القيمة',
        'expire_at'             => 'تاريخ الإنتهاء',
        'number'                => 'عدد قسائم الشراء',
        'title.ar'              => 'العنوان بالعربية',
        'title.en'              => 'العنوان بالإنجليزية',
        'date_range.start'      => 'تاريخ بدءالعرض',
        'date_range.end'        => 'تاريخ إنتهاء العرض',
        'offer_number'          => 'عدد العروض',
        'items.*.brand_id'      => 'العلامة التجارية',
        'items.*.supercategory_id'     => 'القسم الرئيسي',
        'items.*.category_id'          => 'القسم الفرعي',
        'items.*.subcategory_id'       => 'القسم المباشر',
        'items.*.products_id.*'        => 'المنتج',
        'items.*.type'          => 'نوع الخصم',
        'items.*.value'         => 'قيمة الخصم',
        'items.*.offer_number'  => 'عدد العروض',
        'description.ar'        => 'الوصف بالعربية',
        'description.en'        => 'الوصف بالإنجليزية',
        'banner'                => 'البنر',
        'rank'                  => 'الترتيب',
        'banner_name'           => 'البنر',
        'selected_offer'        => 'العرض',
        'selected_banners'      => 'البنرات',
        'selected_products'     => 'المنتجات',
        'address.country_id'    => 'الدولة',
        'address.governorate_id'=> 'المحافظة',
        'address.city_id'       => 'المدينة',
        'balance'               => 'الرصيد',
        'newPhone'              => 'رقم الهاتف',
        'original_price'        => 'سعر الشراء',
        'nav_links.*.name.ar'   => "الاسم بالعربية",
        'nav_links.*.name.en'   => "الاسم بالإنجليزية",
        'nav_links.*.url'       => 'الرابط',
        'nav_links.*.active'    => 'حالة الزر',


    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'values' => [
        // or whatever fields you wanna translate
        'birth_date' => [
            'today' => 'اليوم'
        ],
        'expire_at' => [
            'today' => 'اليوم'
        ]
    ],

];
