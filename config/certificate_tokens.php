<?php

return [

    'tokens' => [
        'holder.full_name' => [
            'label' => 'نام کامل شرکت‌کننده',
            'description' => 'نام و نام خانوادگی (ترکیبی)',
            'path' => 'holder.full_name',
        ],

        'holder.first_name' => [
            'label' => 'نام',
            'description' => 'نام شرکت‌کننده',
            'path' => 'holder.first_name',
        ],

        'holder.last_name' => [
            'label' => 'نام خانوادگی',
            'description' => 'نام خانوادگی شرکت‌کننده',
            'path' => 'holder.last_name',
        ],

        'holder.mobile' => [
            'label' => 'موبایل',
            'description' => 'شماره موبایل شرکت‌کننده',
            'path' => 'holder.mobile',
        ],

        'holder.national_code' => [
            'label' => 'کد ملی',
            'description' => 'کد ملی شرکت‌کننده (اگر موجود باشد)',
            'path' => 'holder.national_code',
        ],

        'holder.issued_at' => [
            'label' => 'تاریخ صدور',
            'description' => 'تاریخ صدور گواهینامه',
            'path' => 'holder.issued_at',
        ],


        'event.title' => [
            'label' => 'عنوان رویداد',
            'description' => 'عنوان رویداد',
            'path' => 'event.title',
        ],

        'event.level' => [
            'label' => 'سطح رویداد',
            'description' => 'سطح برگزاری رویداد',
            'path' => 'event.level',
        ],

        'event.location' => [
            'label' => 'مکان رویداد',
            'description' => 'محل برگزاری رویداد',
            'path' => 'event.location',
        ],

        'event.status' => [
            'label' => 'وضعیت رویداد',
            'description' => 'وضعیت رویداد',
            'path' => 'event.status',
        ],

        'event.starts_at' => [
            'label' => 'تاریخ شروع',
            'description' => 'تاریخ شروع رویداد',
            'path' => 'event.starts_at',
        ],

        'organization.name' => [
            'label' => 'نام سازمان',
            'description' => 'نام سازمان برگزارکننده',
            'path' => 'organization.name',
        ],

        'signatories.first_name' => [
            'label' => 'نام امضاکننده اول',
            'description' => 'نام امضاکننده اول رویداد',
            'path' => 'signatories.first_name',
        ],
        'signatories.second_name' => [
            'label' => 'نام امضاکننده دوم',
            'description' => 'نام امضاکننده دوم رویداد',
            'path' => 'signatories.second_name',
        ],
        'signatories.third_name' => [
            'label' => 'نام امضاکننده سوم',
            'description' => 'نام امضاکننده سوم رویداد',
            'path' => 'signatories.third_name',
        ],

        [
            'signatories.count' =>
            [
                'label' => 'تعداد امضاکنندگان',
                'description' => 'تعداد امضاکنندگان',
                'path' => 'signatories.count',]
        ],


        'certificate.serial' => [
            'label' => 'سریال گواهی',
            'description' => 'شماره سریال گواهی',
            'path' => 'certificate.serial',
        ],

        'certificate.status' => [
            'label' => 'وضعیت گواهی',
            'description' => 'draft / active / revoked',
            'path' => 'certificate.status',
        ],
    ],
];
