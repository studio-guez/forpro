<?php

header("Access-Control-Allow-Origin: *");

return [
    "tobimori.seo.canonicalBase" => "https://for-pro.ch",
    "url_frontend" => "https://for-pro.ch/",
    'panel' => [
        'css' => '_custom-panel/main.css',
    ],
    "routes" => [
        [
            "pattern" => "/",
            "action" => function () {
                go("/panel");
            },
        ],
    ],
    "email" => [
        "transport" => [
            "type" => "smtp",
            "host" => "mail.infomaniak.com",
            "port" => 465,
            "security" => true,
            "auth" => true,
            "username" => "ne-pas-repondre@for-pro.ch",
            "password" => "b.PCS#/.b163rf",
        ],
    ],
];
