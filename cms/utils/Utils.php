<?php

class Utils
{
    static function getHeroFromPage(\Kirby\Cms\Page $kirbyPage): array
    {
        $hero = $kirbyPage->hero()->toStructure()?->get(0);

        return $hero ? [
            'text' => $hero->text()->value(),
            'backgroundcolor' => $hero->backgroundcolor()->value(),
            'textcolor' => $hero->textcolor()->value(),
        ] : [];
    }

    static function getImageArrayDataInPage(\Kirby\Cms\Files $files): array|null
    {
        return $files->map(function (\Kirby\Cms\File $item): array {
            return self::getJsonEncodeImageData($item);
        })->data();
    }

    static function muteImageFilesDataIfBlocksHasKeyValue(string $contentTypeKey, &$content): void
    {
        if (!isset($content['content'][$contentTypeKey])) return;

        foreach ($content['content'][$contentTypeKey] as &$itemArray) {
            //todo: images with s for profiles importation | change images to image in dataBase and profiles json result
            if (isset($itemArray['images']))    $itemArray['imageData'] = self::getImageArrayDataInArray($itemArray, 'images');
            if (isset($itemArray['image']))     $itemArray['imageData'] = self::getImageArrayDataInArray($itemArray, 'image');
        }
    }

    static function getImageArrayDataInArray(array &$itemArray, string $keyNameForImage): array
    {
        $getImageArrayData = Utils::getImageArrayDataInPage(new \Kirby\Cms\Files($itemArray[$keyNameForImage]));
        return $itemArray['imageData'] = array_values($getImageArrayData);
    }


    /**
     * `{focus, caption, alt, link, photoCredit, width, height, url, srcset}` shape
     * shared by every image payload; `$rendition` supplies the sizing/urls and
     * can override any metadata key.
     */
    private static function getImageData(\Kirby\Cms\File $file, array $rendition): array
    {
        return [
            'focus'         => $file->content()->focus()->value(),
            'caption'       => $file->caption()->value(),
            'alt'           => $file->alt()->value(),
            'link'          => $file->link()->value(),
            'photoCredit'   => $file->photoCredit()->value(),
            ...$rendition,
        ];
    }

    static function getJsonEncodeImageData(\Kirby\Cms\File $file): array
    {
        return self::getImageData($file, [
            // `width`/`height` are the intrinsic dimensions so the frontend can
            // reserve space (avoid CLS). `url` is a mid-size WebP fallback for
            // `src`; `srcset` lets the browser pick per viewport × pixel density.
            'width'         => $file->width(),
            'height'        => $file->height(),
            'url'           => $file->resize(1920)->url(),
            'srcset'        => $file->srcset('default'),
        ]);
    }

    /**
     * Serializes a single media file (image or video) for JSON output.
     * Videos can't be resized/srcset, so they only expose a direct URL + mime.
     */
    static function getJsonEncodeMediaData(\Kirby\Cms\File $file): array
    {
        if ($file->type() === 'video') {
            return [
                'type'          => 'video',
                'alt'           => $file->alt()->value(),
                'caption'       => $file->caption()->value(),
                'photoCredit'   => $file->photoCredit()->value(),
                'link'          => $file->link()->value(),
                'url'           => $file->url(),
                'mime'          => $file->mime(),
            ];
        }

        return ['type' => 'image'] + self::getJsonEncodeImageData($file);
    }

    /**
     * Serializes a media Files collection (image or video) to a list for JSON output.
     */
    static function getJsonEncodeMediaArray(\Kirby\Cms\Files $files): array
    {
        return array_values($files->map(fn(\Kirby\Cms\File $file) => self::getJsonEncodeMediaData($file))->data());
    }

    /**
     * Serializes an optional file (e.g. a `cover` that may not be set).
     */
    static function getJsonEncodeImageDataOrNull(?\Kirby\Cms\File $file): ?array
    {
        return $file ? self::getJsonEncodeImageData($file) : null;
    }


    /**
     * Resolves the URL from a structure item using the type/page/url pattern.
     */
    static function resolvePageOrUrlItem(\Kirby\Cms\StructureObject $item): ?string
    {
        if ($item->type()->value() === 'page') {
            $linkedPage = $item->page()->toPage();
            if ($linkedPage) {
                return $linkedPage->isHomePage() ? '/' : '/' . $linkedPage->virtualPath();
            }
            return null;
        }
        if ($item->type()->value() === 'mailto') {
            return $item->email()->isNotEmpty() ? 'mailto:' . $item->email()->value() : null;
        }
        if ($item->type()->value() === 'tel') {
            // "+41 (0) 21 ..." — the parenthesised trunk prefix is only for national
            // dialling and must be dropped, not just stripped of its parentheses.
            // Only after a country code: without one the 0 is part of the number.
            $raw   = preg_replace('/(\+\s*\d[\d\s.-]*)\(\s*0\s*\)/', '$1', (string)$item->phone()->value());
            $phone = preg_replace('/[^0-9+]/', '', $raw);
            // tel: URIs allow a single leading "+" only
            $phone = preg_replace('/(?<!^)\+/', '', $phone);
            return preg_match('/\d/', $phone) === 1 ? 'tel:' . $phone : null;
        }
        return $item->url()->isNotEmpty() ? $item->url()->value() : null;
    }

    /**
     * Resolves the label from a structure item, falling back to the page title or URL.
     */
    static function resolvePageOrUrlLabel(\Kirby\Cms\StructureObject $item): ?string
    {
        if ($item->label()->isNotEmpty()) {
            return $item->label()->value();
        }
        if ($item->type()->value() === 'page') {
            $linkedPage = $item->page()->toPage();
            return $linkedPage?->title()->value();
        }
        if ($item->type()->value() === 'mailto') {
            return $item->email()->isNotEmpty() ? $item->email()->value() : null;
        }
        if ($item->type()->value() === 'tel') {
            return $item->phone()->isNotEmpty() ? $item->phone()->value() : null;
        }
        return $item->url()->isNotEmpty() ? $item->url()->value() : null;
    }

    /**
     * Resolves a single CTA structure item, or null when it has no label or no resolvable URL.
     */
    private static function resolveCtaItem(\Kirby\Cms\StructureObject $item): ?array
    {
        if ($item->label()->isEmpty()) return null;
        $url = self::resolvePageOrUrlItem($item);
        if (!$url) return null;
        return [
            'label' => $item->label()->value(),
            'url'   => $url,
            'icon'  => $item->icon()->isNotEmpty() ? $item->icon()->value() : null,
        ];
    }

    /**
     * Resolves a CTA structure field (max 1) to an array for JSON output, or null if empty.
     */
    static function resolveCtaStructure(\Kirby\Content\Field $field): ?array
    {
        return self::resolveCtaStructures($field)[0] ?? null;
    }

    /**
     * Resolves a multi-entry CTA structure field to a list, dropping unresolvable entries.
     */
    static function resolveCtaStructures(\Kirby\Content\Field $field): array
    {
        $ctas = [];
        foreach ($field->toStructure() as $item) {
            if ($cta = self::resolveCtaItem($item)) {
                $ctas[] = $cta;
            }
        }
        return $ctas;
    }

    /**
     * Resolves taxonomy term UUIDs stored in a tags field to a stable
     * `{slug, title}` representation. Terms are stored as UUIDs (e.g.
     * `page://xxx`) so selections survive slug changes; each UUID is resolved
     * to its term page under content/taxonomies/<taxonomy> here. Unresolvable
     * UUIDs (e.g. deleted terms) are skipped.
     */
    static function resolveTaxonomyTerms(\Kirby\Content\Field $field, string $taxonomy): array
    {
        return array_values(array_filter(array_map(
            fn(string $uuid): ?array => ($term = page($uuid)) ? self::getTaxonomyTermData($term) : null,
            $field->split(',')
        )));
    }

    /**
     * `{slug, title, color}` shape shared by every taxonomy term payload.
     */
    private static function getTaxonomyTermData(\Kirby\Cms\Page $term): array
    {
        return [
            'slug'  => $term->slug(),
            'title' => $term->title()->value(),
            'color' => $term->color()->isNotEmpty() ? $term->color()->value() : null,
        ];
    }

    /**
     * Returns all terms of a taxonomy in their CMS-defined order (the order of
     * the term pages under content/taxonomies/<taxonomy>), in the same
     * `{slug, title, color}` shape as resolveTaxonomyTerms().
     */
    static function getTaxonomyTerms(string $taxonomy): array
    {
        $parent = page('taxonomies/' . $taxonomy);
        if (!$parent) return [];

        return $parent->children()->listed()->map(fn($term) => self::getTaxonomyTermData($term))->values();
    }

    /**
     * Predicate keeping items tagged with any of the given term slugs on a
     * tags-based taxonomy field. Stored values are term UUIDs, so they are
     * resolved to slugs (cached per predicate) before comparison.
     */
    private static function taxonomyMatcher(string $fieldName, array $slugs): \Closure
    {
        $slugCache = [];

        return function ($item) use ($fieldName, $slugs, &$slugCache): bool {
            $itemSlugs = array_map(function (string $uuid) use (&$slugCache) {
                if (!array_key_exists($uuid, $slugCache)) {
                    $slugCache[$uuid] = page($uuid)?->slug();
                }
                return $slugCache[$uuid];
            }, $item->{$fieldName}()->split(','));

            return array_intersect($slugs, $itemSlugs) !== [];
        };
    }

    /**
     * Filters structure items on a tags-based taxonomy field, keeping items
     * tagged with any of the given term slugs. Stored values are term UUIDs,
     * so they are resolved to slugs before comparison. An empty $slugs returns
     * all items, so callers can pass through an optional filter directly.
     *
     * Usage:
     *   $faqs = page('faq')->faqs()->toStructure();                                     // all FAQs
     *   $faqs = Utils::filterStructureByTaxonomy($faqs, 'domains', ['architecture']);   // one domain
     *   $faqs = Utils::filterStructureByTaxonomy($faqs, 'domains', ['a', 'b']);         // any of multiple domains
     */
    static function filterStructureByTaxonomy(\Kirby\Cms\Structure $items, string $fieldName, array $slugs): \Kirby\Cms\Structure
    {
        if ($slugs === []) return $items;

        return $items->filter(self::taxonomyMatcher($fieldName, $slugs));
    }

    /**
     * Same as filterStructureByTaxonomy(), for a pages collection (events,
     * projects, ...). An empty $slugs returns all pages.
     */
    static function filterPagesByTaxonomy(\Kirby\Cms\Pages $pages, string $fieldName, array $slugs): \Kirby\Cms\Pages
    {
        if ($slugs === []) return $pages;

        return $pages->filter(self::taxonomyMatcher($fieldName, $slugs));
    }

    /**
     * Builds the query string carrying an index page's taxonomy pre-filters,
     * e.g. ['domains' => ['campus'], 'eventThemes' => []] -> "?domains=campus".
     * Returns an empty string when no filter is set.
     */
    static function buildTaxonomyQuery(array $filters): string
    {
        $parts = [];
        foreach ($filters as $field => $slugs) {
            if ($slugs !== []) {
                $parts[] = $field . '=' . implode(',', $slugs);
            }
        }

        return $parts === [] ? '' : '?' . implode('&', $parts);
    }

    /**
     * Returns the "going" end DateTime for an event using field priority:
     * [dateEnd+timeEnd, dateEnd+timeStart, dateStart+timeEnd, dateStart+timeStart, dateStart].
     * Returns null if dateStart is not set.
     */
    static function getEventGoingDatetime(\Kirby\Cms\Page $page): ?\DateTime
    {
        ['dateStart' => $dateStart, 'dateEnd' => $dateEnd, 'timeStart' => $timeStart, 'timeEnd' => $timeEnd]
            = self::getEventDateFields($page);

        if (!$dateStart) return null;

        $baseDate = $dateEnd ?? $dateStart;
        $baseTime = $timeEnd ?? $timeStart ?? '23:59';

        return new \DateTime("{$baseDate}T{$baseTime}");
    }

    /**
     * Normalized `dateStart/dateEnd/timeStart/timeEnd` of an event, unset fields as null.
     */
    static function getEventDateFields(\Kirby\Cms\Page $page): array
    {
        return [
            'dateStart' => $page->dateStart()->isNotEmpty() ? $page->dateStart()->toDate('Y-m-d') : null,
            'dateEnd'   => $page->dateEnd()->isNotEmpty()   ? $page->dateEnd()->toDate('Y-m-d')   : null,
            'timeStart' => $page->timeStart()->isNotEmpty() ? $page->timeStart()->value()         : null,
            'timeEnd'   => $page->timeEnd()->isNotEmpty()   ? $page->timeEnd()->value()           : null,
        ];
    }

    /**
     * Card payload for an event listed in the agenda module carousel.
     */
    static function getEventCardData(\Kirby\Cms\Page $page): array
    {
        return [
            'title'     => $page->title()->value(),
            'url'       => '/' . $page->virtualPath(),
            'shortDesc' => $page->shortDesc()->value(),
            'cover'     => self::getJsonEncodeImageDataOrNull($page->cover()->toFile()),
            ...self::getEventDateFields($page),
            'terms'     => array_merge(
                self::resolveTaxonomyTerms($page->domains(), 'domains'),
                self::resolveTaxonomyTerms($page->eventThemes(), 'event-themes')
            ),
        ];
    }

    /**
     * Card payload for a project listed in the projects module carousel.
     */
    static function getProjectCardData(\Kirby\Cms\Page $page): array
    {
        return [
            'title'          => $page->title()->value(),
            'url'            => '/' . $page->virtualPath(),
            'cover'          => self::getJsonEncodeImageDataOrNull($page->cover()->toFile()),
            'collectiveName' => $page->collectiveName()->isNotEmpty() ? $page->collectiveName()->value() : null,
            'themes'         => self::resolveTaxonomyTerms($page->projectThemes(), 'project-themes'),
            'types'          => self::resolveTaxonomyTerms($page->projectTypes(), 'project-types'),
        ];
    }

    /**
     * Resolves the page metadata through Kirby SEO's cascade
     * (page fields -> parent -> site -> plugin defaults) and returns it
     * in the shape consumed by the SvelteKit frontend.
     */
    static function getSeoDataFromPage(\Kirby\Cms\Page $kirbyPage): array
    {
        $meta = $kirbyPage->metadata();

        $schemas = [];
        if (option('tobimori.seo.generateSchema', false)) {
            // The hook (page.render:before) only builds WebSite with page-level
            // data, which is wrong for content pages. Build WebPage ourselves.
            $webPage = $kirbyPage->schema('WebPage')
                ->url($meta->canonicalUrl())
                ->name($meta->metaTitle()->value())
                ->description($meta->get('metaDescription')->value());
            if ($ogImage = $meta->ogImage()) {
                $webPage->image($ogImage);
            }
            // Serialize only the WebPage schema; skip WebSite (built by hook).
            $schemas = [json_decode(json_encode($webPage), true)];
        }

        return [
            // General
            'title'           => $meta->metaTitle()->value(),
            'description'     => $meta->get('metaDescription')->value(),
            'canonicalUrl'    => $meta->canonicalUrl(),
            'robots'          => $meta->robots(),
            'locale'          => $meta->get('lang')->value(),
            // Open Graph
            'ogTitle'         => $meta->ogTitle()->value(),
            'ogDescription'   => $meta->get('ogDescription')->value(),
            'ogSiteName'      => $meta->get('ogSiteName')->value(),
            'ogType'          => $meta->get('ogType')->value(),
            'ogImage'         => $meta->ogImage(),
            // Twitter
            'twitterCardType' => $meta->get('twitterCardType')->value(),
            'twitterSite'     => $meta->twitterSite()->value(),
            'twitterCreator'  => $meta->get('twitterCreator')->value(),
            // Schema.org JSON-LD
            'schemas'         => $schemas,
        ];
    }

    /**
     * Extracts the 11-character YouTube video id from any common URL shape
     * (watch?v=, youtu.be/, /shorts/, /embed/, /v/). Returns null when the URL
     * is not a recognizable YouTube link.
     */
    static function parseYoutubeId(string $url): ?string
    {
        if (preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|shorts/|embed/|v/)|youtu\.be/)([A-Za-z0-9_-]{11})~i', $url, $m) === 1) {
            return $m[1];
        }
        return null;
    }

    /**
     * Resolves an `embedVideos` structure (each item exposing a `url` field)
     * to a JSON-ready list of YouTube embeds. Only YouTube links are kept;
     * Shorts are flagged with `type => 'short'` (vertical), everything else is
     * `type => 'video'` (16:9). `embedUrl` is the privacy-friendly nocookie URL.
     */
    static function getYoutubeEmbeds(\Kirby\Content\Field $field): array
    {
        $embeds = [];
        foreach ($field->toStructure() as $item) {
            $url = $item->url()->value();
            $id  = self::parseYoutubeId((string)$url);
            if (!$id) continue;
            $embeds[] = [
                'id'       => $id,
                'type'     => stripos((string)$url, '/shorts/') !== false ? 'short' : 'video',
                'url'      => $url,
                'embedUrl' => 'https://www.youtube-nocookie.com/embed/' . $id,
            ];
        }
        return $embeds;
    }

    /**
     * Resolves the shared event/project content blocks structure
     * (repeatable `title` + rich-text `description`) to a JSON-ready list.
     */
    static function getContentBlocks(\Kirby\Content\Field $field): array
    {
        return array_values($field->toStructure()->map(fn($item) => [
            'title'       => $item->title()->value(),
            'description' => $item->description()->value(),
        ])->data());
    }

    /**
     * Resolves an external links structure (`title` + `link`) to a JSON-ready
     * list of `{title, url}` items, skipping entries without a URL.
     */
    static function getExternalLinks(\Kirby\Content\Field $field): array
    {
        $links = [];
        foreach ($field->toStructure() as $item) {
            if ($item->link()->isEmpty()) continue;
            $links[] = [
                'title' => $item->title()->value(),
                'url'   => $item->link()->value(),
            ];
        }
        return $links;
    }

    /**
     * Builds the shared payload for event and project pages: the fields defined
     * by pages/event-project-base.yml plus the standard page metadata used by
     * the decoupled frontend router (path, seo).
     */
    static function getEventProjectBaseData(\Kirby\Cms\Page $page): array
    {
        return [
            'title'         => $page->title()->value(),
            'slug'          => $page->slug(),
            'path'          => $page->virtualPath(),
            'subtitle'      => $page->subtitle()->value(),
            'shortDesc'     => $page->shortDesc()->value(),
            'cover'         => self::getJsonEncodeImageDataOrNull($page->cover()->toFile()),
            'medias'        => self::getJsonEncodeMediaArray($page->medias()->toFiles()),
            'embedVideos'   => self::getYoutubeEmbeds($page->embedVideos()),
            'blocks'        => self::getContentBlocks($page->blocks()),
            'externalLinks' => self::getExternalLinks($page->externalLinks()),
            'seo'           => self::getSeoDataFromPage($page),
        ];
    }

    /**
     * Builds the favicon payload for the frontend head.
     * SVGs are served as-is (scalable, one per color scheme); the 512×512 PNG
     * masters are downscaled to every size in `option('favicon.resize')` so the
     * frontend can emit all standard favicon `<link>` tags.
     */
    static function getFaviconData(\Kirby\Cms\Site $site): array
    {
        $sizes = option('favicon.resize', [16, 32, 48, 180, 192, 512]);

        $variant = function (?\Kirby\Cms\File $svg, ?\Kirby\Cms\File $png) use ($sizes): array {
            return [
                'svg' => $svg?->url(),
                'png' => $png
                    ? array_map(fn(int $size) => [
                        'size' => $size,
                        'url'  => $png->resize($size)->url(),
                    ], $sizes)
                    : [],
            ];
        };

        return [
            'light' => $variant(
                $site->faviconLightSvg()->toFile(),
                $site->faviconLightPng()->toFile()
            ),
            'dark' => $variant(
                $site->faviconDarkSvg()->toFile(),
                $site->faviconDarkPng()->toFile()
            ),
        ];
    }

    /**
     * Templates that have a route on the decoupled frontend (i.e. whose pages
     * can be linked to from a search result), mapped to their public label.
     * Taxonomy terms and the `pages` container are structural only.
     */
    private const SEARCHABLE_TEMPLATES = [
        'page'     => 'Page',
        'faq'      => 'Page',
        'event'    => 'Événement',
        'project'  => 'Projet',
    ];

    /** Field types whose raw value is human-readable text. */
    private const SEARCH_TEXT_TYPES = ['text', 'textarea', 'writer', 'markdown', 'list', 'tags'];

    /** Result groups the frontend tabs filter on; every other template is a page. */
    private const SEARCH_GROUPS = [
        'event'   => 'events',
        'project' => 'projects',
    ];

    /** Already covered by the title/lead buckets, skipped when collecting the body. */
    private const SEARCH_SKIPPED_FIELDS = ['title', 'overtitle', 'subtitle', 'introtitle', 'intro', 'shortdesc'];

    /**
     * Nested keys (inside blocks/structures) whose value is human-readable text.
     * Everything else there is machine data (uuids, colors, layout toggles, ...).
     */
    private const SEARCH_TEXT_KEYS = [
        'title',
        'subtitle',
        'overtitle',
        'description',
        'intro',
        'introtitle',
        'shortdesc',
        'question',
        'answer',
        'label',
        'text',
        'caption',
        'collectivename',
    ];

    /**
     * Every page the frontend can link to, i.e. a potential search result.
     */
    static function getSearchablePages(): \Kirby\Cms\Pages
    {
        return site()->index()->filter(
            fn(\Kirby\Cms\Page $page) => isset(self::SEARCHABLE_TEMPLATES[$page->intendedTemplate()->name()])
        );
    }

    /**
     * Full-text search across every page with a public URL. All words of the
     * query must be found; matches in the title outrank matches in the intro,
     * which outrank matches in the body. `counts` always covers every group so
     * the frontend tabs keep their totals while a single group is displayed.
     *
     * @return array{query: string, group: string, offset: int, counts: array<string, int>, total: int, hasMore: bool, results: array<int, array>}
     */
    static function searchPages(string $query, string $group = 'all', int $offset = 0, int $limit = 10): array
    {
        $query = trim(preg_replace('/\s+/u', ' ', $query) ?? '');
        $normalizedQuery = self::normalizeForSearch($query);
        $words = array_values(array_unique(array_filter(
            explode(' ', $normalizedQuery),
            fn(string $word) => mb_strlen($word) >= 2
        )));

        $counts = ['all' => 0, 'pages' => 0, 'events' => 0, 'projects' => 0];

        if ($words === []) {
            return [
                'query'   => $query,
                'group'   => $group,
                'offset'  => $offset,
                'counts'  => $counts,
                'total'   => 0,
                'hasMore' => false,
                'results' => [],
            ];
        }

        $matches = [];

        foreach (self::getSearchablePages() as $page) {
            ['title' => $title, 'lead' => $lead, 'body' => $body] = self::getSearchHaystacks($page);

            $normalizedTitle = self::normalizeForSearch($title);
            $normalizedLead = self::normalizeForSearch($lead);
            $content = trim($lead . ' ' . $body);
            $normalizedContent = self::normalizeForSearch($content);

            $score = 0;
            foreach ($words as $word) {
                if (str_contains($normalizedTitle, $word)) {
                    $score += 10;
                } elseif (str_contains($normalizedLead, $word)) {
                    $score += 4;
                } elseif (str_contains($normalizedContent, $word)) {
                    $score += 1;
                } else {
                    continue 2;
                }
            }

            // Whole-query matches rank above pages that only match word by word.
            if ($normalizedTitle === $normalizedQuery) {
                $score += 50;
            } elseif (str_contains($normalizedTitle, $normalizedQuery)) {
                $score += 20;
            } elseif (str_contains($normalizedContent, $normalizedQuery)) {
                $score += 5;
            }

            $template = $page->intendedTemplate()->name();
            $pageGroup = self::SEARCH_GROUPS[$template] ?? 'pages';
            $counts['all']++;
            $counts[$pageGroup]++;

            $matches[] = [
                'score' => $score,
                'group' => $pageGroup,
                'page'  => $page,
                'result' => [
                    'id'         => $page->id(),
                    'title'      => $page->title()->value(),
                    'url'        => $page->isHomePage() ? '/' : '/' . $page->virtualPath(),
                    'type'       => $template,
                    'typeLabel'  => self::SEARCHABLE_TEMPLATES[$template],
                    'group'      => $pageGroup,
                    'excerpt'    => self::buildSearchExcerpt($content, $normalizedContent, $words),
                ],
            ];
        }

        if ($group !== 'all') {
            $matches = array_values(array_filter($matches, fn(array $match) => $match['group'] === $group));
        }

        usort($matches, fn(array $a, array $b) => $b['score'] <=> $a['score']
            ?: strcmp($a['result']['title'], $b['result']['title']));

        $total = count($matches);
        $offset = max(0, $offset);
        $slice = array_slice($matches, $offset, $limit);

        return [
            'query'   => $query,
            'group'   => $group,
            'offset'  => $offset,
            'counts'  => $counts,
            'total'   => $total,
            'hasMore' => $offset + count($slice) < $total,
            // Thumbnails are only generated for the returned slice, not the whole index.
            'results' => array_map(
                fn(array $match) => $match['result'] + ['cover' => self::getSearchCover($match['page'])],
                $slice
            ),
        ];
    }

    /**
     * Square thumbnail of a result's cover, in the shared image payload shape.
     * Deliberately lighter than `getJsonEncodeImageData()`: a result row only
     * needs the crop and its retina variant, never the full srcset.
     */
    private static function getSearchCover(\Kirby\Cms\Page $page): ?array
    {
        $file = $page->cover()->toFile();

        if ($file === null || $file->type() !== 'image') {
            return null;
        }

        return self::getImageData($file, [
            // The crop already honours the file's focus point.
            'focus'       => null,
            'width'       => 240,
            'height'      => 240,
            'url'         => $file->crop(240, 240)->url(),
            'srcset'      => $file->crop(240, 240)->url() . ' 240w, ' . $file->crop(480, 480)->url() . ' 480w',
        ]);
    }

    /**
     * Splits a page's indexable text into the three ranking buckets.
     * The body is driven by the blueprint field types, so a new text field or
     * block is indexed without touching this helper.
     *
     * @return array{title: string, lead: string, body: string}
     */
    private static function getSearchHaystacks(\Kirby\Cms\Page $page): array
    {
        $title = self::joinSearchParts([
            $page->title()->value(),
            $page->overtitle()->value(),
            $page->subtitle()->value(),
        ]);

        $lead = self::joinSearchParts([
            $page->introTitle()->value(),
            $page->intro()->value(),
            $page->shortDesc()->value(),
        ]);

        $body = [];
        foreach ($page->blueprint()->fields() as $name => $blueprint) {
            $type = $blueprint['type'] ?? '';
            if (in_array(strtolower((string)$name), self::SEARCH_SKIPPED_FIELDS, true)) {
                continue;
            }

            $field = $page->content()->get($name);
            if ($field->isEmpty()) {
                continue;
            }

            if ($type === 'blocks') {
                foreach ($field->toBlocks() as $block) {
                    self::collectSearchText($block->content()->toArray(), $body);
                }
            } elseif ($type === 'structure') {
                foreach ($field->toStructure() as $item) {
                    self::collectSearchText($item->content()->toArray(), $body);
                }
            } elseif (in_array($type, self::SEARCH_TEXT_TYPES, true)) {
                $body[] = $field->value();
            }
        }

        return ['title' => $title, 'lead' => $lead, 'body' => self::joinSearchParts($body)];
    }

    /**
     * Recursively pulls the human-readable strings out of a block/structure
     * content array. Numeric keys only carry nested rows, never text.
     */
    private static function collectSearchText(array $data, array &$parts): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                self::collectSearchText($value, $parts);
            } elseif (is_string($value) && $value !== '' && in_array(strtolower((string)$key), self::SEARCH_TEXT_KEYS, true)) {
                $parts[] = $value;
            }
        }
    }

    /**
     * Strips markup out of the given values and joins them into one haystack.
     * UUID references (taxonomy tags, page links) are swapped for the target
     * title, and values without a single letter (times, dates, ids) dropped.
     */
    private static function joinSearchParts(array $parts): string
    {
        $text = implode(' ', array_filter(
            $parts,
            fn($part) => is_string($part) && preg_match('/\p{L}/u', $part) === 1
        ));

        $text = preg_replace_callback(
            '#\b(?:page|file|site|user)://[a-zA-Z0-9._-]+#',
            fn(array $match) => page($match[0])?->title()->value() ?? '',
            $text
        ) ?? $text;

        $text = html_entity_decode(preg_replace('/<[^>]*+>/', ' ', $text) ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(preg_replace('/\s+/u', ' ', $text) ?? '');
    }

    /**
     * Lowercased, accent-folded copy of the text. Every replacement is one
     * character long, so offsets stay aligned with the original string and can
     * be reused to cut an excerpt out of it.
     */
    private static function normalizeForSearch(string $text): string
    {
        $map = [
            'À' => 'a',
            'Á' => 'a',
            'Â' => 'a',
            'Ã' => 'a',
            'Ä' => 'a',
            'Å' => 'a',
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ä' => 'a',
            'å' => 'a',
            'Ç' => 'c',
            'ç' => 'c',
            'È' => 'e',
            'É' => 'e',
            'Ê' => 'e',
            'Ë' => 'e',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'Ì' => 'i',
            'Í' => 'i',
            'Î' => 'i',
            'Ï' => 'i',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'Ñ' => 'n',
            'ñ' => 'n',
            'Ò' => 'o',
            'Ó' => 'o',
            'Ô' => 'o',
            'Õ' => 'o',
            'Ö' => 'o',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ö' => 'o',
            'Ù' => 'u',
            'Ú' => 'u',
            'Û' => 'u',
            'Ü' => 'u',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ü' => 'u',
            'Ý' => 'y',
            'ý' => 'y',
            'ÿ' => 'y',
            '’' => "'",
            '‘' => "'",
            '–' => '-',
            '—' => '-',
        ];

        return mb_strtolower(strtr($text, $map), 'UTF-8');
    }

    /**
     * Text snippet around the first matching word, cut on word boundaries.
     */
    private static function buildSearchExcerpt(string $text, string $normalized, array $words, int $length = 180): string
    {
        if ($text === '') {
            return '';
        }

        $position = 0;
        foreach ($words as $word) {
            $found = mb_strpos($normalized, $word);
            if ($found !== false) {
                $position = $found;
                break;
            }
        }

        $start = max(0, $position - 60);
        $excerpt = mb_substr($text, $start, $length);

        if ($start > 0) {
            // Drop the partial first word left by the cut.
            $excerpt = '…' . ltrim(mb_substr($excerpt, (int)mb_strpos($excerpt, ' ')));
        }
        if (mb_strlen($text) > $start + $length) {
            $excerpt = rtrim(mb_substr($excerpt, 0, (int)mb_strrpos($excerpt, ' ') ?: null)) . '…';
        }

        return $excerpt;
    }
}
