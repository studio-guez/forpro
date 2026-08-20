<?php

header("Access-Control-Allow-Origin: *");

$frontendUrl = rtrim(getenv('KIRBY_FRONTEND_URL') ?: 'https://for-pro.ch', '/');
$cmsUrl = getenv('KIRBY_URL') ? rtrim(getenv('KIRBY_URL'), '/') : null;

$noIndex = getenv('KIRBY_ROBOTS_INDEX') === 'false';

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
    'tobimori.seo' => [
        // Site-config overrides must sit under this exact literal key: Kirby stores
        // plugin options at `$plugin->prefix()` ("tobimori.seo", the slash->dot'd
        // plugin name), so a nested `'tobimori' => ['seo' => [...]]` array lives at
        // a different, disconnected path and is silently ignored by option().
        'canonicalBase' => $frontendUrl,
        'lang' => 'fr_CH',
        'robots' => [
            // disallow everything in /robots.txt (and noindex the meta default)
            // when explicitly turned off (e.g. preprod), editable in cms.env
            // without rebuilding the image.
            'index' => !$noIndex,
        ],
        'sitemap' => [
            'active' => true,
            // The default generator walks the whole Kirby index and builds URLs
            // from the content structure. Neither matches the decoupled
            // frontend: only `page`/`faq` entries have a route there (the
            // containers and taxonomies don't), and their canonical path is the
            // parentPage-based `virtualPath`, not `/pages/<slug>`.
            'generator' => function (\tobimori\Seo\Sitemap\SitemapIndex $sitemap) {
                $urls = $sitemap->create('pages');

                $pages = site()->index()->filter(
                    fn($page) => in_array($page->intendedTemplate()->name(), ['page', 'faq', 'events', 'event', 'projects', 'project'], true)
                        && $page->metadata()->robotsIndex()->toBool()
                );

                foreach ($pages as $page) {
                    $urls->createUrl($page->frontendUrl())
                        ->lastmod($page->modified() ?? time())
                        ->changefreq('weekly')
                        ->priority(number_format(
                            $page->isHomePage() ? 1 : max(1 - 0.2 * count($page->parentChain()), 0.2),
                            1
                        ));
                }
            },
        ],
        'default' => [
            'metaTemplate' => fn($page) => $page->site()->title()->isNotEmpty()
                ? '{{ title }} - {{ site.title }}'
                : '{{ title }}',
        ],
    ],
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
        [
            // The frontend routes on `virtualPath` (real ancestors, minus the
            // top-level containers, then the `parentPage` chain), which is not
            // a Kirby page id: `/pages/evenements/evenement-de-test.json` has
            // to resolve `pages/evenements/evenement-de-test`, while
            // `/pages/entreprendre/mentorat.json` has to resolve `pages/mentorat`.
            // Returning null falls through to Kirby's own routing (404 page).
            "pattern" => "pages/(:all).json",
            "action" => function (string $path) {
                $page = site()->index()->filter(
                    fn($candidate) => $candidate->virtualPath() === $path
                )->first();

                if ($page === null) {
                    return null;
                }

                return new \Kirby\Http\Response(
                    $page->render([], 'json'),
                    'application/json'
                );
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
