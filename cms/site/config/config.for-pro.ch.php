<?php

header("Access-Control-Allow-Origin: *");

return [
    "debug" => false,
    "tobimori.seo.canonicalBase" => "https://for-pro.ch",
    "url_frontend" => "https://for-pro.ch/",
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
            "password" => "***REMOVED***",
        ],
    ],
];
