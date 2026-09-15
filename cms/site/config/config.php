<?php

header("Access-Control-Allow-Origin: *");

$frontendUrl = rtrim(getenv('KIRBY_FRONTEND_URL') ?: 'https://for-pro.ch', '/');
$cmsUrl = getenv('KIRBY_URL') ? rtrim(getenv('KIRBY_URL'), '/') : null;

$noIndex = getenv('KIRBY_ROBOTS_INDEX') === 'false';

return [
    'debug' => getenv('KIRBY_DEBUG') === 'true',
    'home' => 'pages/home',
    // Without a pinned url, Kirby derives media/file URLs from the internal Docker host (Host: cms), which browsers cannot resolve.
    ...($cmsUrl ? ['url' => $cmsUrl] : []),
    'thumbs' => [
        'driver' => 'gd',
        'quality' => 80,
        'format' => 'webp',
        'srcsets' => [
            'default' => [480, 768, 1024, 1366, 1600, 1920, 2560, 3840],
        ],
    ],
    // 180 = apple-touch-icon, 192/512 = PWA manifest.
    'favicon' => [
        'resize' => [16, 32, 48, 180, 192, 512],
    ],
    'tobimori.seo' => [
        'canonicalBase' => $frontendUrl,
        'lang' => 'fr_CH',
        'robots' => [
            'index' => !$noIndex,
        ],
        'sitemap' => [
            'active' => true,
            // The default generator builds `/pages/<slug>` URLs for the whole index; only page/faq have a frontend route, at their virtualPath.
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
            // The home page is titled after the site, so the suffix would render "ForPro - ForPro".
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
                // The frontend adds a 5th column itself (external links + socials).
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
                        'socialLinks'     => $socialLinks,
                        'menuTitle'       => $orNull($site->footerMenuTitle()),
                        'menuLinks'       => $footerMenuLinks,
                        'newsletter' => [
                            'title'        => $orNull($site->footerNewsletterTitle()),
                            'placeholder'  => $orNull($site->newsletterPlaceholder()),
                            'submitLabel'  => $orNull($site->newsletterSubmitLabel()),
                            'messages'     => [
                                'success'      => $orNull($site->newsletterSuccessMessage()),
                                'error'        => $orNull($site->newsletterErrorMessage()),
                                'invalidEmail' => $orNull($site->newsletterInvalidEmailMessage()),
                            ],
                        ],
                    ],
                    // Kept out of `footer` so +layout.server.ts never forwards it to the browser; the frontend's /api/newsletter route proxies the post.
                    'newsletter' => [
                        'actionUrl'      => $orNull($site->newsletterActionUrl()),
                        'challengeUrl'   => $orNull($site->newsletterChallengeUrl()),
                        'key'            => $orNull($site->newsletterKey()),
                        'webformId'      => $orNull($site->newsletterWebformId()),
                        'emailFieldName' => $orNull($site->newsletterEmailFieldName()),
                        'honeypotFields' => array_values(array_filter(array_map(
                            'trim',
                            explode(',', (string)$site->newsletterHoneypotFields())
                        ), fn($name) => $name !== '')),
                    ],
                    'banner'  => $bannerAnnouncements,
                    'favicon' => Utils::getFaviconData($site),
                    // Page schemas reference these by @id, so they are emitted once in the layout.
                    'schemas' => Utils::getSiteSchemas(),
                    'cookies' => [
                        'text' => $orNull($site->cookiesText()),
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
            "pattern" => "search.json",
            "action" => function () {
                require_once 'utils/Utils.php';

                $query = (string)(get('q') ?? '');
                $group = (string)(get('group') ?? 'all');
                $offset = max((int)(get('offset') ?? 0), 0);
                $limit = min(max((int)(get('limit') ?? 10), 1), 50);

                return \Kirby\Http\Response::json(
                    Utils::searchPages(mb_substr($query, 0, 100), $group, $offset, $limit)
                );
            },
        ],
        // Taxonomy params are already-expanded term slugs: the frontend narrows a parent to its selected sub-terms, re-expanding here would undo that.
        // Returning null falls through to Kirby's own routing (404 page).
        [
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
                    (string)(get('month') ?? ''),
                    max((int)(get('offset') ?? 0), 0),
                    min(max((int)(get('limit') ?? 10), 1), 50)
                ));
            },
        ],
        [
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
            // Sorting has to happen here, not on the frontend: it decides what lands in a page.
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
            // The path is the parent-page virtualPath, not a Kirby id: /pages/entreprendre/mentorat.json resolves pages/mentorat.
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
