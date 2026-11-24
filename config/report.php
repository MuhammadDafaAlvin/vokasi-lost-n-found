<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Item Categories
    |--------------------------------------------------------------------------
    |
    | This array defines the available categories for lost and found items.
    | These categories are used when users create or report an item.
    | Add or remove categories here to customize the available options.
    |
    */
    'categories' => [
        'Elektronik',
        'Dokumen',
        'Aksesori',
        'Lainnya',
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Upload Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines the validation rules for uploaded item images.
    | The maximum file size is specified in kilobytes, while the allowed
    | MIME types determine which image formats can be uploaded.
    |
    */
    'image' => [
        // Maximum allowed image size in kilobytes (2 MB).
        'max_kilobytes' => 2000,

        // Allowed image file extensions.
        'mimes' => [
            'jpg',
            'jpeg',
            'png',
            'webp',
        ],
    ],
];
