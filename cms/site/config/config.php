<?php

header("Access-Control-Allow-Origin: *");

return [
    "tobimori.seo.canonicalBase" => "https://for-pro.ch",
    "tobimori.seo.lang" => "fr_CH",
    "tobimori.seo.default.metaTemplate" => fn($page) => $page->site()->title()->isNotEmpty()
        ? '{{ title }} - {{ site.title }}'
        : '{{ title }}',
    "url_frontend" => "https://for-pro.ch/",
    'content' => [
        'salt' => getenv('KIRBY_CONTENT_SALT'),
    ],
    'cookie' => [
        'key' => getenv('KIRBY_COOKIE_KEY'),
    ],
    'panel' => [
        'css' => '_custom-panel/main.css',
        'vue' => [
            'compiler' => getenv('KIRBY_VUE_COMPILER') === 'true',
        ],
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
            "password" => "***REMOVED***",
        ],
    ],
];
