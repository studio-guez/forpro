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
                require_once 'utils/Utils.php';

                $urls = $sitemap->create('pages');

                foreach (Utils::getIndexablePages() as $page) {
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
            // The home page is titled after the site itself, so appending the
            // site title there would render "ForPro - ForPro".
            'metaTemplate' => fn($page) => $page->site()->title()->isNotEmpty()
                && $page->title()->value() !== $page->site()->title()->value()
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
                $logoEntrepriseFormatriceFile = $site->logoEntrepriseFormatrice()->toFile();

                $mainMenu = $site->mainMenu()->toStructure()->map(fn($item) => [
                    'label'  => Utils::resolvePageOrUrlLabel($item),
                    'url'    => Utils::resolvePageOrUrlItem($item),
                    'target' => Utils::resolvePageOrUrlTarget($item),
                ])->values();

                $secondaryMenu = [];
                // The burger menu has 4 CMS-managed columns (see blueprints/tabs/navigation.yml);
                // the frontend adds a 5th column with external links and social medias.
                foreach ([1, 2, 3, 4] as $index) {
                    $groups = $site->{"secondaryColumn{$index}Groups"}()->toBlocks()->map(fn($block) => [
                        'title' => $block->title()->isEmpty() ? null : $block->title()->value(),
                        'links' => $block->links()->toStructure()->map(fn($link) => [
                            'label'  => Utils::resolvePageOrUrlLabel($link),
                            'url'    => Utils::resolvePageOrUrlItem($link),
                            'target' => Utils::resolvePageOrUrlTarget($link),
                            'level'  => (int)$link->level()->or(1)->value(),
                        ])->values(),
                    ])->values();

                    $columnTitle = $site->{"secondaryColumn{$index}Title"}();
                    $secondaryMenu[] = [
                        'title'  => $columnTitle->isEmpty() ? null : $columnTitle->value(),
                        'groups' => $groups,
                    ];
                }

                $bannerAnnouncements = [];
                foreach ($site->bannerAnnouncements()->toStructure() as $item) {
                    $title = $item->title();
                    if ($title->isEmpty()) {
                        continue;
                    }
                    $description = $item->description();
                    $bannerAnnouncements[] = [
                        'title'       => $title->value(),
                        'description' => $description->isEmpty() ? null : $description->value(),
                        'url'         => Utils::resolvePageOrUrlItem($item),
                        'target'      => Utils::resolvePageOrUrlTarget($item),
                    ];
                }

                $externalLinksTitle = $site->externalLinksTitle();

                $externalLinks = $site->externalLinks()->toStructure()->map(fn($item) => [
                    'label' => $item->label()->value(),
                    'url'   => $item->url()->value(),
                ])->values();

                $knownPlatforms = ['facebook', 'instagram', 'linkedin', 'youtube', 'tiktok', 'snapchat', 'x'];

                $socialLinks = [];
                foreach ($site->socialLinks()->toStructure() as $item) {
                    $platform = $item->platform()->value();
                    $url      = $item->url();

                    if (in_array($platform, $knownPlatforms, true) === false || $url->isEmpty()) {
                        continue;
                    }

                    $socialLinks[] = [
                        'platform' => $platform,
                        'url'      => $url->value(),
                    ];
                }

                $footerMenuLinks = $site->footerMenuLinks()->toStructure()->map(fn($item) => [
                    'label'  => Utils::resolvePageOrUrlLabel($item),
                    'url'    => Utils::resolvePageOrUrlItem($item),
                    'target' => Utils::resolvePageOrUrlTarget($item),
                ])->values();

                // `null` rather than `""` for every optional footer string, so the frontend
                // can drop the whole line/column instead of rendering an empty node.
                $orNull = fn(\Kirby\Content\Field $field) => $field->isEmpty() ? null : $field->value();

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
                    'footer' => [
                        // Same file as the header logo: it is managed once, in the Website tab.
                        'logo'                     => Utils::getJsonEncodeImageData($logoFile),
                        'logoEntrepriseFormatrice' => Utils::getJsonEncodeImageDataOrNull($logoEntrepriseFormatriceFile),
                        'address' => [
                            'name'       => $orNull($site->addressName()),
                            'street'     => $orNull($site->addressStreet()),
                            'postalCode' => $orNull($site->addressPostalCode()),
                            'locality'   => $orNull($site->addressLocality()),
                            'region'     => $orNull($site->addressRegion()),
                            'country'    => $orNull($site->addressCountry()),
                            'mapUrl'     => $orNull($site->addressMapUrl()),
                        ],
                        'email'           => $orNull($site->contactEmail()),
                        'phone'           => $orNull($site->contactPhone()),
                        'phoneUrl'        => Utils::telHref($site->contactPhone()),
                        'socialsTitle'    => $orNull($site->footerSocialsTitle()),
                        // Same list as the burger menu's 5th column: the links themselves
                        // are managed once, in the Social networks tab.
                        'socialLinks'     => $socialLinks,
                        'menuTitle'       => $orNull($site->footerMenuTitle()),
                        'menuLinks'       => $footerMenuLinks,
                        'newsletterTitle' => $orNull($site->footerNewsletterTitle()),
                    ],
                    'banner'  => $bannerAnnouncements,
                    'favicon' => Utils::getFaviconData($site),
                    // Site-wide JSON-LD (Organization, WebSite). Every page schema
                    // links back to these by `@id`, so they are emitted once, in
                    // the layout, rather than repeated on every page.
                    'schemas' => Utils::getSiteSchemas(),
                    'cookies' => [
                        'text' => $orNull($site->cookiesText()),
                        // Null when no page is picked: the banner then drops the link
                        // rather than pointing at a 404.
                        'privacyPolicyUrl' => Utils::pageUrl($site->privacyPolicyPage()->toPage()),
                    ],
                ]);
            },
        ],
        [
            "pattern" => "llms.txt",
            "action" => function () {
                require_once 'utils/Utils.php';

                return new \Kirby\Http\Response(Utils::getLlmsTxt(), 'text/plain');
            },
        ],
        [
            // Site-wide search over every page that has a frontend route.
            "pattern" => "search.json",
            "action" => function () {
                require_once 'utils/Utils.php';

                $query = (string)(get('q') ?? '');
                // `searchPages()` owns the group vocabulary and rejects unknown values.
                $group = (string)(get('group') ?? 'all');
                $offset = max((int)(get('offset') ?? 0), 0);
                $limit = min(max((int)(get('limit') ?? 10), 1), 50);

                return \Kirby\Http\Response::json(
                    Utils::searchPages(mb_substr($query, 0, 100), $group, $offset, $limit)
                );
            },
        ],
        // The three paginated index lists behind the frontends' infinite scroll.
        // Each takes `?path=`, the index page's `virtualPath` (not its Kirby id,
        // see `pages/(:all).json` below), and returns one page of the envelope
        // `{offset, total, hasMore, items}`. Every taxonomy parameter is an
        // *already expanded* selection of term slugs, because the frontend
        // narrows a selected parent down to its selected sub-terms and
        // re-expanding here would undo that. Returning null on an unknown or
        // wrong-template path falls through to Kirby's own routing (404 page).
        [
            // Paginated archive of an events page, filtered the way the agenda
            // filters it.
            "pattern" => "past-events.json",
            "action" => function () {
                require_once 'utils/Utils.php';

                $page = Utils::findPageByVirtualPath((string)(get('path') ?? ''), 'events');
                if ($page === null) {
                    return null;
                }

                return \Kirby\Http\Response::json(Utils::getPastEvents(
                    $page->children()->listed(),
                    mb_substr((string)(get('q') ?? ''), 0, 100),
                    array_filter(explode(',', (string)(get('publics') ?? ''))),
                    (string)(get('month') ?? ''),
                    max((int)(get('offset') ?? 0), 0),
                    min(max((int)(get('limit') ?? 10), 1), 50)
                ));
            },
        ],
        [
            // Paginated projects of a projects index, filtered the way the
            // projects page filters them.
            "pattern" => "projects.json",
            "action" => function () {
                require_once 'utils/Utils.php';

                $page = Utils::findPageByVirtualPath((string)(get('path') ?? ''), 'projects');
                if ($page === null) {
                    return null;
                }

                return \Kirby\Http\Response::json(Utils::getProjects(
                    $page->children()->listed(),
                    mb_substr((string)(get('q') ?? ''), 0, 100),
                    array_filter(explode(',', (string)(get('programs') ?? ''))),
                    array_filter(explode(',', (string)(get('years') ?? ''))),
                    max((int)(get('offset') ?? 0), 0),
                    min(max((int)(get('limit') ?? 12), 1), 50)
                ));
            },
        ],
        [
            // Paginated missions of a missions index. `?sort=` has to be applied
            // here rather than on the frontend: it decides what lands in a page.
            "pattern" => "missions.json",
            "action" => function () {
                require_once 'utils/Utils.php';

                $page = Utils::findPageByVirtualPath((string)(get('path') ?? ''), 'missions');
                if ($page === null) {
                    return null;
                }

                return \Kirby\Http\Response::json(Utils::getMissions(
                    $page->children()->listed(),
                    array_filter(explode(',', (string)(get('categories') ?? ''))),
                    (string)(get('sort') ?? ''),
                    max((int)(get('offset') ?? 0), 0),
                    min(max((int)(get('limit') ?? 24), 1), 50)
                ));
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
                require_once 'utils/Utils.php';

                $page = Utils::findPageByVirtualPath($path);

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
