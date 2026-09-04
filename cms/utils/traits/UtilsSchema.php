<?php

/**
 * schema.org JSON-LD nodes, shipped inside the JSON payloads and printed by the
 * frontends into a `<script type="application/ld+json">`.
 *
 * They are built here rather than in the frontends because only Kirby knows the
 * public URLs: a page is published under its `virtualPath`, not under its page
 * id, so every `@id`, `url` and breadcrumb entry has to go through
 * `frontendUrl()`. The frontends only print the arrays out.
 *
 * The nodes are linked, not repeated: the Organization and the WebSite are
 * emitted once in `global.json` with a stable `@id`, and every page node points
 * back at them with `['@id' => ...]` instead of restating the data.
 */
trait UtilsSchema
{
    /**
     * Site-wide nodes, shipped once in `global.json`.
     */
    static function getSiteSchemas(): array
    {
        $site = site();
        $base = self::schemaBaseUrl();

        $organization = self::withoutEmpty([
            '@context'  => 'https://schema.org',
            '@type'     => 'Organization',
            '@id'       => self::organizationId(),
            'name'      => $site->title()->value(),
            'url'       => $base . '/',
            'logo'      => self::schemaImage($site->logo()->toFile()),
            'email'     => $site->contactEmail()->value(),
            'telephone' => $site->contactPhone()->value(),
            'address'   => self::getPostalAddressSchema(),
            'sameAs'    => $site->socialLinks()->toStructure()
                ->filter(fn($item) => $item->url()->isNotEmpty())
                ->values(fn($item) => $item->url()->value()),
        ]);

        $website = self::withoutEmpty([
            '@context'   => 'https://schema.org',
            '@type'      => 'WebSite',
            '@id'        => self::websiteId(),
            'url'        => $base . '/',
            'name'       => $site->title()->value(),
            'inLanguage' => $site->lang(),
            'publisher'  => ['@id' => self::organizationId()],
        ]);

        return [$organization, $website];
    }

    /**
     * Every node describing a page: the WebPage itself, its breadcrumb, and the
     * entity its template is about (Event, JobPosting, ...).
     *
     * `$meta` is the already-resolved head payload from
     * `getSeoDataFromPage()` (title, description, canonical URL, og:image), so
     * the structured data can never contradict the meta tags.
     */
    static function getPageSchemas(\Kirby\Cms\Page $page, array $meta): array
    {
        $canonical = $meta['canonicalUrl'];
        $webPageId = $canonical . '#webpage';
        $template  = $page->intendedTemplate()->name();

        // FAQPage needs its questions on the page node itself, so they are
        // resolved before the type is decided: no questions, no FAQPage.
        $questions = $template === 'faq' ? self::getFaqQuestionSchemas($page) : [];

        $webPage = self::withoutEmpty([
            '@context'           => 'https://schema.org',
            '@type'              => self::getWebPageTypes($template, $questions !== []),
            '@id'                => $webPageId,
            'url'                => $canonical,
            'name'               => $meta['title'],
            'description'        => $meta['description'],
            'inLanguage'         => $page->site()->lang(),
            'isPartOf'           => ['@id' => self::websiteId()],
            'publisher'          => ['@id' => self::organizationId()],
            'primaryImageOfPage' => $meta['ogImage']
                ? ['@type' => 'ImageObject', 'url' => $meta['ogImage']]
                : null,
            'dateModified'       => date('c', $page->modified() ?: time()),
            'mainEntity'         => $questions,
        ]);

        return array_values(array_filter([
            $webPage,
            self::getBreadcrumbSchema($page, $canonical),
            self::getTemplateSchema($page, $template, $meta, $webPageId),
        ]));
    }

    /**
     * The entity a content page is about, on top of its WebPage node. Templates
     * with no entity of their own (plain pages, indexes, legal pages) get none.
     */
    private static function getTemplateSchema(
        \Kirby\Cms\Page $page,
        string $template,
        array $meta,
        string $webPageId
    ): ?array {
        return match ($template) {
            'event'     => self::getEventSchema($page, $meta, $webPageId),
            'job-offer' => self::getJobPostingSchema($page, $meta, $webPageId),
            'project'   => self::getProjectSchema($page, $meta, $webPageId),
            'team'      => self::getTeamSchema($page),
            default     => null,
        };
    }

    /**
     * `WebPage`, narrowed to the more specific type when the template has one.
     * An array of types is valid JSON-LD and is how a page can be both the page
     * and the collection / FAQ it renders.
     */
    private static function getWebPageTypes(string $template, bool $hasQuestions): string|array
    {
        if ($template === 'faq' && $hasQuestions) {
            return ['WebPage', 'FAQPage'];
        }

        if (in_array($template, ['events', 'projects', 'job-offers', 'missions', 'press'], true)) {
            return ['WebPage', 'CollectionPage'];
        }

        return 'WebPage';
    }

    /**
     * Breadcrumb from the home page down to this one, following the same chain
     * as the public URL: real Kirby ancestors first, then the `parentPage`
     * chain (see the `parent-page` plugin). Null on the home page, and on a
     * page that sits directly at the root.
     */
    private static function getBreadcrumbSchema(\Kirby\Cms\Page $page, string $canonical): ?array
    {
        if ($page->isHomePage() === true) {
            return null;
        }

        $trail = array_filter([$page->site()->homePage(), ...self::getBreadcrumbPages($page)]);

        if (count($trail) < 2) {
            return null;
        }

        $items = [];
        foreach ($trail as $item) {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => count($items) + 1,
                'name'     => $item->title()->value(),
                'item'     => $item->frontendUrl(),
            ];
        }

        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            '@id'             => $canonical . '#breadcrumb',
            'itemListElement' => $items,
        ];
    }

    /**
     * The pages the public path is made of, root first — the page itself
     * included. Mirrors `virtualPath()`: each real Kirby ancestor contributes
     * its own `parentPage` chain, and the structural top-level containers
     * (pages, taxonomies) are skipped since they have no URL.
     *
     * @return \Kirby\Cms\Page[]
     */
    private static function getBreadcrumbPages(\Kirby\Cms\Page $page): array
    {
        $trail = [];

        foreach ($page->parents()->flip() as $parent) {
            if ($parent->parent() === null) {
                continue;
            }
            $trail = [...$trail, ...$parent->parentChain()];
        }

        return [...$trail, ...$page->parentChain()];
    }

    private static function getEventSchema(\Kirby\Cms\Page $page, array $meta, string $webPageId): ?array
    {
        ['dateStart' => $dateStart, 'dateEnd' => $dateEnd, 'timeStart' => $timeStart, 'timeEnd' => $timeEnd]
            = self::getEventDateFields($page);

        if ($dateStart === null) {
            return null;
        }

        // An `endDate` is only emitted when the event really ends elsewhere in
        // time: repeating the start as a bare date next to a start datetime
        // reads as an event that ends before it begins.
        $start   = self::schemaDateTime($dateStart, $timeStart);
        $lastDay = $dateEnd !== null && $dateEnd !== $dateStart ? $dateEnd : null;
        $end     = $lastDay !== null || $timeEnd !== null
            ? self::schemaDateTime($lastDay ?? $dateStart, $timeEnd)
            : null;

        return self::withoutEmpty([
            '@context'            => 'https://schema.org',
            '@type'               => 'Event',
            '@id'                 => $meta['canonicalUrl'] . '#event',
            'name'                => $page->title()->value(),
            'description'         => self::schemaText($page->shortDesc()->value()),
            'startDate'           => $start,
            'endDate'             => $end === $start ? null : $end,
            'eventStatus'         => 'https://schema.org/EventScheduled',
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'location'            => self::getEventPlaceSchema($page->location()),
            'image'               => self::schemaImage($page->cover()->toFile()),
            'organizer'           => ['@id' => self::organizationId()],
            'url'                 => $meta['canonicalUrl'],
            'mainEntityOfPage'    => ['@id' => $webPageId],
        ]);
    }

    /**
     * Where an event takes place: the venue named on the event when there is
     * one, the foundation's own address otherwise — that is where an event
     * without an explicit venue is held.
     */
    private static function getEventPlaceSchema(\Kirby\Content\Field $location): ?array
    {
        if ($location->isNotEmpty()) {
            return ['@type' => 'Place', 'name' => $location->value()];
        }

        return self::withoutEmpty([
            '@type'   => 'Place',
            'name'    => site()->addressName()->value(),
            'address' => self::getPostalAddressSchema(),
        ]) ?: null;
    }

    /**
     * A closed offer keeps its page but is no longer a job posting: it leaves
     * the index and the sitemap, and must not stay in a jobs feed either.
     */
    private static function getJobPostingSchema(\Kirby\Cms\Page $page, array $meta, string $webPageId): ?array
    {
        if (self::isOpenToApplications($page) === false) {
            return null;
        }

        ['activityRateMin' => $rateMin, 'activityRateMax' => $rateMax] = self::getActivityRate($page);

        // The three rich-text sections the page shows, in page order: Google
        // reads the description as the whole offer, not just its intro.
        $description = implode('', array_filter([
            $page->description()->value(),
            $page->profile()->value(),
            $page->conditions()->value(),
        ]));

        return self::withoutEmpty([
            '@context'           => 'https://schema.org',
            '@type'              => 'JobPosting',
            '@id'                => $meta['canonicalUrl'] . '#jobposting',
            'title'              => $page->title()->value(),
            'description'        => $description,
            'datePosted'         => $page->publishedDate()->isNotEmpty()
                ? $page->publishedDate()->toDate('Y-m-d')
                : null,
            'validThrough'       => $page->deadline()->isNotEmpty()
                ? $page->deadline()->toDate('Y-m-d')
                : null,
            'employmentType'     => $rateMin >= 100 && $rateMax === null ? 'FULL_TIME' : 'PART_TIME',
            'hiringOrganization' => ['@id' => self::organizationId()],
            'jobLocation'        => self::getJobLocationSchema($page->location()),
            'url'                => $meta['canonicalUrl'],
            'mainEntityOfPage'   => ['@id' => $webPageId],
        ]);
    }

    /**
     * Place of work: a free-text locality on the offer, completed by the site's
     * country so the posting is placed on a map.
     */
    private static function getJobLocationSchema(\Kirby\Content\Field $location): ?array
    {
        $address = self::withoutEmpty([
            '@type'           => 'PostalAddress',
            'addressLocality' => $location->value(),
            'addressCountry'  => site()->addressCountry()->value(),
        ]);

        return count($address) > 1 ? ['@type' => 'Place', 'address' => $address] : null;
    }

    private static function getProjectSchema(\Kirby\Cms\Page $page, array $meta, string $webPageId): array
    {
        return self::withoutEmpty([
            '@context'         => 'https://schema.org',
            '@type'            => 'CreativeWork',
            '@id'              => $meta['canonicalUrl'] . '#project',
            'name'             => $page->title()->value(),
            'headline'         => $page->subtitle()->value(),
            'description'      => self::schemaText($page->shortDesc()->value()),
            'image'            => self::schemaImage($page->cover()->toFile()),
            'creator'          => $page->collectiveName()->isNotEmpty()
                ? ['@type' => 'Organization', 'name' => $page->collectiveName()->value()]
                : null,
            'sponsor'          => ['@id' => self::organizationId()],
            'url'              => $meta['canonicalUrl'],
            'mainEntityOfPage' => ['@id' => $webPageId],
        ]);
    }

    /**
     * The team page lists the people of the organization, so it adds the
     * `employee` property to the Organization node rather than describing a new
     * entity: same `@id`, more statements about it.
     */
    private static function getTeamSchema(\Kirby\Cms\Page $page): ?array
    {
        $members = [];

        foreach ($page->sections()->toStructure() as $section) {
            foreach ($section->members()->toStructure() as $member) {
                if ($member->name()->isEmpty()) {
                    continue;
                }

                $members[] = self::withoutEmpty([
                    '@type'    => 'Person',
                    'name'     => $member->name()->value(),
                    'jobTitle' => $member->role()->value(),
                    'sameAs'   => $member->linkedin()->isNotEmpty() ? [$member->linkedin()->value()] : null,
                    'worksFor' => ['@id' => self::organizationId()],
                ]);
            }
        }

        return $members === [] ? null : [
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            '@id'      => self::organizationId(),
            'employee' => $members,
        ];
    }

    /**
     * Every question of the FAQ page, unfiltered: the taxonomy filters live in
     * the query string, while the schema describes the canonical URL.
     */
    private static function getFaqQuestionSchemas(\Kirby\Cms\Page $page): array
    {
        $questions = [];

        foreach ($page->sections()->toStructure() as $section) {
            foreach ($section->faqs()->toStructure() as $faq) {
                if ($faq->question()->isEmpty() || $faq->answer()->isEmpty()) {
                    continue;
                }

                $questions[] = [
                    '@type'          => 'Question',
                    'name'           => $faq->question()->value(),
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => $faq->answer()->value(),
                    ],
                ];
            }
        }

        return $questions;
    }

    /** The site's postal address, or null when it is not filled in. */
    private static function getPostalAddressSchema(): ?array
    {
        $site = site();

        $address = self::withoutEmpty([
            '@type'           => 'PostalAddress',
            'streetAddress'   => $site->addressStreet()->value(),
            'postalCode'      => $site->addressPostalCode()->value(),
            'addressLocality' => $site->addressLocality()->value(),
            'addressRegion'   => $site->addressRegion()->value(),
            'addressCountry'  => $site->addressCountry()->value(),
        ]);

        return count($address) > 1 ? $address : null;
    }

    private static function schemaImage(?\Kirby\Cms\File $file): ?array
    {
        if ($file === null) {
            return null;
        }

        return self::withoutEmpty([
            '@type'  => 'ImageObject',
            'url'    => $file->url(),
            'width'  => $file->width(),
            'height' => $file->height(),
        ]);
    }

    /**
     * `YYYY-MM-DD` when the field carries no time, a full ISO 8601 datetime
     * otherwise. The offset comes from the CMS timezone, i.e. the one the times
     * were entered in.
     */
    private static function schemaDateTime(?string $date, ?string $time): ?string
    {
        if ($date === null || $date === '') {
            return null;
        }

        if ($time === null || $time === '') {
            return $date;
        }

        try {
            return (new \DateTimeImmutable("{$date} {$time}"))->format(\DateTimeInterface::ATOM);
        } catch (\Exception) {
            return $date;
        }
    }

    /** Rich-text field -> the plain, single-spaced sentence a schema wants. */
    private static function schemaText(?string $html): string
    {
        return trim(preg_replace('/\s+/', ' ', self::stripHtmlTags($html)) ?? '');
    }

    /** Public base URL of the frontend, without its trailing slash. */
    private static function schemaBaseUrl(): string
    {
        return rtrim(option('url_frontend', site()->url()), '/');
    }

    private static function organizationId(): string
    {
        return self::schemaBaseUrl() . '/#organization';
    }

    private static function websiteId(): string
    {
        return self::schemaBaseUrl() . '/#website';
    }

    /**
     * Drops the properties that carry nothing: JSON-LD has no use for a `null`,
     * an empty string or an empty list, and a validator flags them.
     */
    private static function withoutEmpty(array $data): array
    {
        return array_filter($data, fn($value) => $value !== null && $value !== '' && $value !== []);
    }
}
