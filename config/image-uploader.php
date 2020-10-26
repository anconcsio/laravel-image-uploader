<?php

return [
    // will be the part of the ulr http://site/route-prefix/image-uploader
    'route-prefix' => '',

    // middleware for the package web routes
    'middleware' => ['web'],

    // middleware for the package api routes
    'middleware-api' => ['api'],

    // broadcasting channels
    'notify-channels' => ['image-uploader'],

    // event name for broadcasting
    'notify-name' => 'image-resized',

    // the place to save the original uploading file
    'tmp-storage' => 'local',
    'tmp-save-path' => 'image-uploader',

    // the place to save resized file copies
    'storage' => 's3',
    'save-path' => '',

    // queue details for resizing job scheduling
    'queue-connection' => 'database',
    'queue' => 'image-uploader',

    'sizes' => [
        'thumbnail' => ['width' => 100, 'height' => 100, 'type' => 'gif'],
        'small' => ['width' => 300, 'height' => 150, 'type' => 'jpeg'],
        'full' => ['width' => 0, 'height' => 0, 'type' => 'jpeg'],
    ]
];
