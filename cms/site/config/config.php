<?php

header("Access-Control-Allow-Origin: *");

$frontendUrl = rtrim(getenv('KIRBY_FRONTEND_URL') ?: 'https://for-pro.ch', '/');
$cmsUrl = getenv('KIRBY_URL') ? rtrim(getenv('KIRBY_URL'), '/') : null;

return [
    'debug' => getenv('KIRBY_DEBUG') === 'true',
    'home' => 'pages/home',
    // Pin the base URL when set: the frontends fetch the API over the internal
    // Docker network (Host: cms), and Kirby would otherwise derive media/file
    // URLs from that internal host, which browsers cannot resolve.
    ...($cmsUrl ? ['url' => $cmsUrl] : []),
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
    // Sizes (px) downscaled from the 512×512 favicon PNG masters to cover every
    // standard favicon `<link>` (16/32/48 browsers, 180 apple-touch, 192/512 PWA).
    'favicon' => [
        'resize' => [16, 32, 48, 180, 192, 512],
    ],
    "tobimori.seo.canonicalBase" => $frontendUrl,
    "tobimori.seo.lang" => "fr_CH",
    // /robots.txt: disallow everything when explicitly turned off (e.g. preprod),
    // editable in cms.env without rebuilding the image.
    "tobimori.seo.robots.index" => getenv('KIRBY_ROBOTS_INDEX') !== 'false',
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
                    'label' => Utils::resolvePageOrUrlLabel($item),
                    'url'   => Utils::resolvePageOrUrlItem($item),
                ])->values();

                $secondaryMenu = [];
                // The burger menu has 4 CMS-managed columns (see blueprints/tabs/navigation.yml);
                // the frontend adds a 5th column with external links and social medias.
                foreach ([1, 2, 3, 4] as $index) {
                    $groups = $site->{"secondaryColumn{$index}Groups"}()->toBlocks()->map(fn($block) => [
                        'title' => $block->title()->isEmpty() ? null : $block->title()->value(),
                        'links' => $block->links()->toStructure()->map(fn($link) => [
                            'label' => Utils::resolvePageOrUrlLabel($link),
                            'url'   => Utils::resolvePageOrUrlItem($link),
                            'level' => (int)$link->level()->or(1)->value(),
                        ])->values(),
                    ])->values();

                    $columnTitle = $site->{"secondaryColumn{$index}Title"}();
                    $secondaryMenu[] = [
                        'title'  => $columnTitle->isEmpty() ? null : $columnTitle->value(),
                        'groups' => $groups,
                    ];
                }

                $externalLinksTitle = $site->externalLinksTitle();

                $externalLinks = $site->externalLinks()->toStructure()->map(fn($item) => [
                    'label' => $item->label()->value(),
                    'url'   => $item->url()->value(),
                ])->values();

                $socialLinks = [];
                foreach (['facebook', 'instagram', 'linkedin', 'youtube', 'tiktok', 'snapchat', 'x'] as $platform) {
                    $url = $site->{$platform}();
                    if ($url->isNotEmpty()) {
                        $socialLinks[] = [
                            'platform' => $platform,
                            'url'      => $url->value(),
                        ];
                    }
                }

                return \Kirby\Http\Response::json([
                    'header' => [
                        'siteTitle'     => $site->title()->value(),
                        'logo'          => Utils::getJsonEncodeImageData($logoFile),
                        'mainMenu'      => $mainMenu,
                        'secondaryMenu' => $secondaryMenu,
                        'externalLinksTitle' => $externalLinksTitle->isEmpty() ? null : $externalLinksTitle->value(),
                        'externalLinks' => $externalLinks,
                        'socialLinks'   => $socialLinks,
                    ],
                    'favicon' => Utils::getFaviconData($site),
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
