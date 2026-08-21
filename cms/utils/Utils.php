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


    static function getJsonEncodeImageData(\Kirby\Cms\File $file): array
    {
        return [
            'focus' => $file->content()->focus()->value(),
            'caption'       => $file->caption()->value(),
            'alt'           => $file->alt()->value(),
            'link'          => $file->link()->value(),
            'photoCredit'   => $file->photoCredit()->value(),
            // `width`/`height` are the intrinsic dimensions so the frontend can
            // reserve space (avoid CLS). `url` is a mid-size WebP fallback for
            // `src`; `srcset` lets the browser pick per viewport × pixel density.
            'width'         => $file->width(),
            'height'        => $file->height(),
            'url'           => $file->resize(1920)->url(),
            'srcset'        => $file->srcset('default'),
        ];
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
    private static function getEventDateFields(\Kirby\Cms\Page $page): array
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
}
