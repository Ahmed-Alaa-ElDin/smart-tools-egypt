<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'accepted_if' => 'The :attribute must be accepted when :other is :value.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute must only contain letters.',
    'alpha_dash' => 'The :attribute must only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute must only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'declined' => 'The :attribute must be declined.',
    'declined_if' => 'The :attribute must be declined when :other is :value.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal to :value.',
        'file' => 'The :attribute must be greater than or equal to :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal to :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal to :value.',
        'file' => 'The :attribute must be less than or equal to :value kilobytes.',
        'string' => 'The :attribute must be less than or equal to :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'mac_address' => 'The :attribute must be a valid MAC address.',
    'max' => [
        'numeric' => 'The :attribute must not be greater than :max.',
        'file' => 'The :attribute must not be greater than :max kilobytes.',
        'photo' => 'The :attribute must not be greater than :max kilobytes.',
        'string' => 'The :attribute must not be greater than :max characters.',
        'array' => 'The :attribute must not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'multiple_of' => 'The :attribute must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'password' => [
        'mixed' => 'The :attribute must be a mix of letters, numbers, and symbols.',
        'letters' => 'The :attribute must contain at least one letter.',
        'numbers' => 'The :attribute must contain at least one number.',
        'symbols' => 'The :attribute must contain at least one symbol.',
        'uncompromised' => 'The :attribute must not be compromised.',
    ],
    'present' => 'The :attribute field must be present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_array_keys' => 'The :attribute field must contain entries for: :values.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid timezone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute must be a valid URL.',
    'uuid' => 'The :attribute must be a valid UUID.',
    'The Email Address is required when role is admin.' => 'The Email Address is required when role is admin.',
    'The phone numbers must contain digits between 8 & 11' => 'The phone numbers must contain digits between 8 & 11',
    "maxif" => "The :attribute must be less than or equal 100%",
    "The offer is required." => "The offer is required",
    "Products are required." => "Products are required.",
    "Banners are required." => "Banners are required.",
    'The phone number field is required' => 'The phone number field is required',
    'The phone numbers must start with 010, 011, 012 or 015' => 'The phone numbers must start with 010, 011, 012 or 015',
    'arabic name required' => 'Arabic Name is required',
    'english name required' => 'English Name is required',
    'url required' => 'Link is required',
    'The details field is required' => 'The details field is required',

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
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'selectedPermissions'   => 'Permissions',
        'phones.*.phone'        => 'phone',
        'zones.*.name.ar'       => 'Name in Arabic',
        'zones.*.name.en'       => 'Name in English',
        'zones.*.min_charge'    => 'Minimum Fees',
        'zones.*.min_weight'      => 'Minimum Size',
        'zones.*.kg_charge'     => 'Fees/Kg',
        'zones.*.destinations.*.country_id'     => 'Country',
        'zones.*.destinations.*.governorate_id' => 'Governorate',
        'selectedPermissions'   => 'Permissions',
        'selectedPermissions'   => 'Permissions',
        'name.ar'               => 'name in Arabic',
        'name.en'               => 'name in English',
        'country_id'            => 'Country',
        'governorate_id'        => 'Governorate',
        'video'                 => 'Video',
        'brand_id'              => 'Brand',
        'subcategory_id'        => 'Subcategory',
        'model'                 => 'Model',
        'barcode'               => 'Barcode',
        'gallery_images.*'      => 'gallery images',
        'weight'                => 'weight',
        'logo'                  => 'Logo',
        'supercategory_id'      => 'Main Category',
        'category_id'           => 'Category',
        'parentCategories.*.supercategory_id'         => 'Main Category',
        'parentCategories.*.category_id'              => 'Category',
        'parentCategories.*.subcategory_id'           => 'Subcategory',
        'code'                  => 'Code',
        'type'                  => 'Type',
        'value'                 => 'Value',
        'expire_at'             => 'Expiration Date',
        'number'                => 'Number',
        'title.ar'              => 'Title in Arabic',
        'title.en'              => 'Title in English',
        'date_range.start'      => 'Start Date of Offer',
        'date_range.end'        => 'End Date of Offer',
        'offer_number'          => 'Number of Offers',
        'items.*.brand_id'      => 'Brand',
        'items.*.supercategory_id'     => 'Supercategory',
        'items.*.category_id'          => 'Category',
        'items.*.subcategory_id'       => 'Subcategory',
        'items.*.products_id.*'        => 'Product',
        'items.*.type'          => 'Type',
        'items.*.value'         => 'Value',
        'items.*.offer_number'  => 'Number of Offers',
        'description.ar'        => 'description.ar',
        'description.en'        => 'description.en',
        'rank'                  => 'Rank',
        'banner_name'           => 'Banner',
        'selected_offer'        => 'Offer',
        'selected_banners'      => 'Banners',
        'auth_id'               => 'Social Media ID',
        'address.country_id'    => 'Country',
        'address.governorate_id'=> 'Governorate',
        'address.city_id'       => 'City',
        'newPhone'              => 'Phone',
        'original_price'        => 'Original Price',
        'nav_links.*.name.ar'   => "Link's Arabic Name",
        'nav_links.*.name.en'   => "Link's English Name",
        'nav_links.*.url'       => 'Link',
        'nav_links.*.active'    => 'Active Status',
        'addresses.*.details'   => 'Address Details',
        'addresses.*.landmarks' => 'Address Landmarks',
        'address.details'       => 'Address Details',
        'address.landmarks'     => 'Address Landmarks',
        'newAddress.details'    => 'Address Details',
        'newAddress.landmarks'  => 'Address Landmarks',
        'resetPasswordPhone'    => 'Phone',
        'resetPasswordCode'     => 'Reset Password Code',
        'newPassword'           => 'New Password',
        'newPasswordConfirmation' => 'New Password Confirmation',
        'oldPassword'           => 'Old Password',
        'old_password'          => 'Old Password',
        'new_password'          => 'New Password',
        'new_password_confirmation' => 'New Password Confirmation',

    ],

];
