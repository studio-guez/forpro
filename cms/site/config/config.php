<?php

header('Access-Control-Allow-Origin: *');

return [
    'debug' => true,
    'url_frontend' => 'https://for-pro.ch/',
//    'url' => 'https://forpro-admin.sdrvl.ch/',
    'routes' => [
        [
            'pattern' => '/',
            'action' => function() {
                go('/panel');
            }
        ]
    ],
    'email' => [
        'transport' => [
            'type' => 'smtp',
            'host' => 'mail.mediumsans.studio',
            'port' => 465,
            'security' => true,
            'auth' => true,
            'username' => 'forpro@mediumsans.studio',
            'password' => 'FtTYCN{8sFN?',
        ]
    ]
];
