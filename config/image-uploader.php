<?php

return [
    'route-prefix' => '',
    'middleware' => ['web'],
    'middleware-api' => ['api'],
    'notify-channels' => ['image-uploader'],
    'notify-name' => 'image-resized',

    'tmp-storage' => 'local',
    'tmp-save-path' => 'image-uploader',
    'storage' => 's3',
    'save-path' => '',
    'queue-connection' => 'database',
    'queue' => 'image-uploader',
    'sizes' => [
        'thumbnail' => ['width' => 100, 'height' => 100, 'type' => 'gif'],
        'small' => ['width' => 300, 'height' => 150, 'type' => 'jpeg'],
        'full' => ['width' => 0, 'height' => 0, 'type' => 'jpeg'],
    ]
];
