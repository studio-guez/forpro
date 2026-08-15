<?php

header("Access-Control-Allow-Origin: *");

$frontendUrl = rtrim(getenv('KIRBY_FRONTEND_URL') ?: 'https://for-pro.ch', '/');

return [
    'debug' => getenv('KIRBY_DEBUG') === 'true',
    'home' => 'pages/home',
    // WebP for every generated thumb (GD driver, installed --with-webp).
    // `default` is the width ladder used for srcset: it spans small phones
    // up to 4K / high-DPR displays so the browser can pick per viewport × dpr.
    'thumbs' => [
        'driver' => 'gd',
        'quality' => 80,
        'format' => 'webp',
        'srcsets' => [
            'default' => [480, 768, 1024, 1366, 1600, 1920, 2560, 3840],
        ],
    ],
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
        [
            "pattern" => "global.json",
            "action" => function () {
                require_once 'utils/Utils.php';

                $site = site();

                $logoFile = $site->logo()->toFile();

                $mainMenu = $site->mainMenu()->toStructure()->map(fn($item) => [
                    'label' => $item->label()->value(),
                    'url'   => Utils::resolveLinkField($item->link()),
                ])->values();

                $secondaryMenu = [];
                foreach ([1, 2, 3, 4] as $index) {
                    $groups = $site->{"secondaryColumn{$index}Groups"}()->toBlocks()->map(fn($block) => [
                        'title' => $block->title()->or(null)->value(),
                        'links' => $block->links()->toStructure()->map(fn($link) => [
                            'label' => $link->label()->value(),
                            'url'   => Utils::resolveLinkField($link->link()),
                            'level' => (int)$link->level()->or(1)->value(),
                        ])->values(),
                    ])->values();

                    $secondaryMenu[] = [
                        'title'  => $site->{"secondaryColumn{$index}Title"}()->or(null)->value(),
                        'groups' => $groups,
                    ];
                }

                $externalLinks = $site->externalLinks()->toStructure()->map(fn($item) => [
                    'label' => $item->label()->value(),
                    'url'   => $item->url()->value(),
                ])->values();

                $socialLinks = $site->socialLinks()->toStructure()->map(fn($item) => [
                    'platform' => $item->platform()->value(),
                    'url'      => $item->url()->value(),
                ])->values();

                return \Kirby\Http\Response::json([
                    'header' => [
                        'siteTitle'     => $site->title()->value(),
                        'logo'          => $logoFile ? Utils::getJsonEncodeImageData($logoFile) : null,
                        'mainMenu'      => $mainMenu,
                        'secondaryMenu' => $secondaryMenu,
                        'externalLinks' => $externalLinks,
                        'socialLinks'   => $socialLinks,
                    ],
                ]);
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
