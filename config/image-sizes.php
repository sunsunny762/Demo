<?php
/*
|--------------------------------------------------------------------------
| Default images which will generate while image upload
|--------------------------------------------------------------------------
|
| This option contains all available resized images
|
*/

use Spatie\Image\Manipulations;

return [
    'profile_image' => [
        'fit-32x32' => [
            'width' => 32,
            'height' => 32,
            'method' => 'fit',
            'size' => 10,
            'type' => Manipulations::FIT_CONTAIN,
            'is_recommended' => true
        ],
        'fit-36x36' => [
            'width' => 36,
            'height' => 36,
            'method' => 'fit',
            'size' => 10,
            'type' => Manipulations::FIT_CONTAIN
        ]
    ],
];
