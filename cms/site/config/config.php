<?php

header("Access-Control-Allow-Origin: *");

$frontendUrl = rtrim(getenv('KIRBY_FRONTEND_URL') ?: 'https://for-pro.ch', '/');

return [
    'debug' => getenv('KIRBY_DEBUG') === 'true',
    'home' => 'pages/home',
    "tobimori.seo.canonicalBase" => $frontendUrl,
    "tobimori.seo.lang" => "fr_CH",
    "tobimori.seo.default.metaTemplate" => fn($page) => $page->site()->title()->isNotEmpty()
        ? '{{ title }} - {{ site.title }}'
        : '{{ title }}',
    "url_frontend" => $frontendUrl . "/",
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
            "host" => getenv('KIRBY_SMTP_HOST') ?: 'localhost',
            "port" => (int)(getenv('KIRBY_SMTP_PORT') ?: 587),
            "security" => getenv('KIRBY_SMTP_SECURITY') === 'true',
            "auth" => getenv('KIRBY_SMTP_AUTH') === 'true',
            "username" => getenv('KIRBY_SMTP_USERNAME') ?: null,
            "password" => getenv('KIRBY_SMTP_PASSWORD') ?: null,
        ],
    ],
];
