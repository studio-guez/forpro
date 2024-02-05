<?php

header('Access-Control-Allow-Origin: *');

return [
    'debug' => true,
    'url_frontend' => 'https://forpro-website.sdrvl.ch',
    'url' => 'https://forpro-website.sdrvl.ch',
    'routes' => [
        [
            'pattern' => '/',
            'action' => function() {
                go('/panel');
            }
        ]
    ]
];
