<?php

/**
 * Page-level payloads: hero, event dates, card data, the shared event/project
 * base serialization, and the paginated index lists (past events, projects,
 * missions) behind the frontends' infinite scroll.
 */
trait UtilsPages
{
    /**
     * Resolves a frontend path to the page it addresses. The frontend routes on
     * `virtualPath` (real ancestors, minus the top-level containers, then the
     * `parentPage` chain), which is *not* the Kirby page id: the missions index
     * lives at `pages/missions` but is published at `programme-campus/missions`.
     *
     * `$template` guards a route that only makes sense for one index, so that
     * `?path=` cannot be pointed at an unrelated page. Returns null when
     * nothing matches, which lets a route fall through to Kirby's own 404.
     */
    static function findPageByVirtualPath(string $path, ?string $template = null): ?\Kirby\Cms\Page
    {
        $page = kirby()->site()->index()->filter(
            fn($candidate) => $candidate->virtualPath() === $path
        )->first();

        if ($page === null) {
            return null;
        }

        return $template === null || $page->intendedTemplate()->name() === $template ? $page : null;
    }

    /**
     * One page of a filtered list, in the envelope the three index lists share
     * (`PaginatedList<T>` on the frontend).
     *
     * `$serialize` is applied to the returned slice only, never to the whole
     * set: card payloads generate thumbnails, and doing that for an archive the
     * visitor is about to scroll past one page of is the cost this pagination
     * exists to avoid.
     *
     * @param  array<int, mixed> $items Already filtered and ordered.
     * @return array{offset:int, total:int, hasMore:bool, items:array<int, array>}
     */
    private static function paginate(array $items, int $offset, int $limit, \Closure $serialize): array
    {
        $total = count($items);
        $offset = max(0, $offset);
        $slice = array_slice($items, $offset, $limit);

        return [
            'offset'  => $offset,
            'total'   => $total,
            'hasMore' => $offset + count($slice) < $total,
            'items'   => array_map($serialize, $slice),
        ];
    }

    static function getHeroFromPage(\Kirby\Cms\Page $kirbyPage): array
    {
        $hero = $kirbyPage->hero()->toStructure()?->get(0);

        return $hero ? [
            'text' => $hero->text()->value(),
            'backgroundcolor' => $hero->backgroundcolor()->value(),
            'textcolor' => $hero->textcolor()->value(),
        ] : [];
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
     * Splits an events collection into upcoming and past events, based on the
     * "going" datetime (an event stays upcoming until it is over). Upcoming
     * events are sorted soonest first, past events most recent first. Events
     * without a start date are dropped.
     *
     * Returns `['upcoming' => Pages, 'past' => Pages]`.
     */
    static function splitEventsByDate(\Kirby\Cms\Pages $events, ?\DateTime $now = null): array
    {
        $now ??= new \DateTime();

        $isUpcoming = function ($event) use ($now): ?bool {
            $endDt = self::getEventGoingDatetime($event);
            return $endDt === null ? null : $endDt >= $now;
        };

        return [
            'upcoming' => $events->filter(fn($event) => $isUpcoming($event) === true)->sortBy('dateStart', 'asc'),
            'past'     => $events->filter(fn($event) => $isUpcoming($event) === false)->sortBy('dateStart', 'desc'),
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
            'programs'  => self::resolveTaxonomyTerms($page->programs(), 'programs'),
            'sectors'   => self::resolveTaxonomyTerms($page->sectors(), 'sectors'),
            'publics'   => self::resolveTaxonomyTerms($page->publics(), 'publics'),
        ];
    }

    /**
     * Filtered, paginated past events of an events page, most recent first.
     *
     * The filters mirror the frontend ones: `$query` is the archive's own
     * search (the agenda's publics filter and search only narrow upcoming
     * events), matched as a whole phrase, accent- and case-insensitively,
     * against the title only; `$month` is a `YYYY-MM` key on `dateStart`.
     *
     * `total` counts the events the returned page is sliced out of, so it
     * drives the pagination. `matchTotal` ignores the search and the month:
     * it says whether the archive has anything to search or browse at all,
     * which is what shows the archive, its search box included, so a search
     * with no result cannot make its own box disappear. `months` ignores the
     * month only, so the dropdown keeps offering the months the selection
     * excludes, but follows the search: it only offers months with a match.
     *
     * Month keys, not labels: formatting them is the frontend's job.
     *
     * @return array{offset:int, total:int, matchTotal:int, hasMore:bool, months:array<int,string>, items:array<int,array>}
     */
    static function getPastEvents(
        \Kirby\Cms\Pages $events,
        string $query = '',
        string $month = '',
        int $offset = 0,
        int $limit = 10
    ): array {
        $past = self::splitEventsByDate($events)['past'];

        $matchTotal = $past->count();

        // Titles only, by design: descriptions and the dropdown's terms are not searched.
        $past = $past->filter(
            fn($event) => self::matchesSearchFields($query, [$event->title()->value()])
        );

        $matched = [];
        foreach ($past as $event) {
            $matched[] = [
                'page'  => $event,
                'month' => mb_substr((string)self::getEventDateFields($event)['dateStart'], 0, 7),
            ];
        }

        $months = array_values(array_unique(array_filter(array_column($matched, 'month'))));

        // An unknown ?month= is ignored, not matched, like the frontend dropdown: a stale one widens the archive instead of emptying it.
        $month = in_array($month, $months, true) ? $month : '';

        $forMonth = $month === ''
            ? $matched
            : array_values(array_filter($matched, fn(array $entry) => $entry['month'] === $month));

        return [
            ...self::paginate(
                $forMonth,
                $offset,
                $limit,
                fn(array $entry) => self::getEventCardData($entry['page'])
            ),
            'matchTotal' => $matchTotal,
            'months'     => $months,
        ];
    }

    /**
     * Projects most recent first, the order of every projects listing.
     *
     * Sorting has to happen here rather than on the frontend: it decides
     * which projects land in a page at all.
     */
    static function sortProjects(\Kirby\Cms\Pages $projects): \Kirby\Cms\Pages
    {
        return $projects->sortBy('date', 'desc');
    }

    /** Year of a project's `date`, null while the date is not set. */
    static function getProjectYear(\Kirby\Cms\Page $project): ?int
    {
        $year = $project->date()->toDate('Y');

        return $year === null ? null : (int)$year;
    }

    /** Years carried by at least one project, most recent first. */
    static function getProjectYears(\Kirby\Cms\Pages $projects): array
    {
        $years = array_values(array_unique(array_filter(
            $projects->values(fn($project) => self::getProjectYear($project)),
            fn(?int $year) => $year !== null
        )));
        rsort($years);

        return $years;
    }

    /**
     * Filtered, paginated projects of a projects index, most recent first.
     *
     * The filters mirror the frontend ones: `$query` is matched as a whole
     * phrase against the title only; `$sectors` and `$categories` are **raw**
     * selections of term slugs; `$years` is a list of years as strings, matched
     * on the year of the `date` field.
     *
     * @return array{offset:int, total:int, hasMore:bool, items:array<int,array>}
     */
    static function getProjects(
        \Kirby\Cms\Pages $projects,
        string $query = '',
        array $sectors = [],
        array $categories = [],
        array $years = [],
        int $offset = 0,
        int $limit = 12
    ): array {
        // resolveTaxonomySelection() mirrors the frontend rule, so a URL means the same on both ends.
        foreach (['sectors' => $sectors, 'categories' => $categories] as $taxonomy => $selection) {
            $projects = self::filterPagesByTaxonomy(
                $projects,
                $taxonomy,
                self::resolveTaxonomySelection($taxonomy, $selection),
                false
            );
        }

        if ($years !== []) {
            $projects = $projects->filter(
                fn($project) => in_array((string)self::getProjectYear($project), $years, true)
            );
        }

        // Titles only, by design: descriptions and the dropdown's terms are not searched.
        $projects = $projects->filter(
            fn($project) => self::matchesSearchFields($query, [$project->title()->value()])
        );

        return self::paginate(
            self::sortProjects($projects)->values(),
            $offset,
            $limit,
            fn(\Kirby\Cms\Page $project) => self::getProjectCardData($project)
        );
    }

    /**
     * Whether a mission / job offer still accepts applications.
     *
     * A closed one stays online — its page keeps its URL and says so — but it
     * leaves the index and the sitemap. The field is missing from every page
     * saved before it existed, and those have to keep counting as open, hence
     * the `true` default rather than a bare `toBool()`.
     */
    static function isOpenToApplications(\Kirby\Cms\Page $page): bool
    {
        return $page->openToApplications()->toBool(true);
    }

    /** The pages of a listing that still accept applications. */
    static function filterOpenToApplications(\Kirby\Cms\Pages $pages): \Kirby\Cms\Pages
    {
        return $pages->filter(fn(\Kirby\Cms\Page $page) => self::isOpenToApplications($page));
    }

    /** Accepted `?sort=` values of the missions index; anything else falls back to the default. */
    private const MISSION_SORTS = ['dateDesc', 'dateAsc', 'titleAsc'];

    /** Order of the missions index when `?sort=` is absent or unknown: most recent first. */
    private const MISSION_SORT_DEFAULT = 'dateDesc';

    /**
     * Filtered, sorted, paginated missions of a missions index.
     *
     * Sorting has to happen here rather than on the frontend: it decides which
     * missions land in a page at all. `$categories` is a **raw** selection of
     * term slugs; an unknown or empty `$sort` means the default one, and
     * `usort()` being stable keeps ties in CMS order, like `Array.sort()` does.
     *
     * @return array{offset:int, total:int, hasMore:bool, items:array<int,array>}
     */
    static function getMissions(
        \Kirby\Cms\Pages $missions,
        array $categories = [],
        string $sort = '',
        int $offset = 0,
        int $limit = 24
    ): array {
        $missions = self::filterOpenToApplications($missions);

        // resolveTaxonomySelection() mirrors the frontend rule, so a URL means the same on both ends.
        $missions = self::filterPagesByTaxonomy(
            $missions,
            'categories',
            self::resolveTaxonomySelection('categories', $categories),
            false
        );

        // The mission `date` is free text, so the order comes from `publishedDate`.
        $items = $missions->values(fn($mission) => [
            'page'  => $mission,
            'date'  => (string)$mission->publishedDate()->toDate('Y-m-d'),
            'title' => (string)$mission->title()->value(),
        ]);

        if (in_array($sort, self::MISSION_SORTS, true) === false) {
            $sort = self::MISSION_SORT_DEFAULT;
        }

        // Matches the frontend's localeCompare(…, 'fr'): accented titles sort as a French reader expects, not by code point.
        $collator = class_exists('Collator') ? new \Collator('fr_FR') : null;

        usort($items, match ($sort) {
            'dateDesc' => fn(array $a, array $b) => strcmp($b['date'], $a['date']),
            'dateAsc'  => fn(array $a, array $b) => strcmp($a['date'], $b['date']),
            'titleAsc' => fn(array $a, array $b) => $collator
                ? $collator->compare($a['title'], $b['title'])
                : strcmp(self::normalizeForSearch($a['title']), self::normalizeForSearch($b['title'])),
        });

        return self::paginate(
            $items,
            $offset,
            $limit,
            fn(array $entry) => self::getMissionCardData($entry['page'])
        );
    }

    /**
     * Card payload for a project listed in the projects module carousel.
     */
    static function getProjectCardData(\Kirby\Cms\Page $page): array
    {
        return [
            'title'          => $page->title()->value(),
            'url'            => '/' . $page->virtualPath(),
            'shortDesc'      => $page->shortDesc()->value(),
            'cover'          => self::getJsonEncodeImageDataOrNull($page->cover()->toFile()),
            'collectiveName' => $page->collectiveName()->isNotEmpty() ? $page->collectiveName()->value() : null,
            'date'           => $page->date()->toDate('Y-m-d'),
            'programs'       => self::resolveTaxonomyTerms($page->programs(), 'programs'),
            'sectors'        => self::resolveTaxonomyTerms($page->sectors(), 'sectors'),
            'categories'     => self::resolveTaxonomyTerms($page->categories(), 'categories'),
        ];
    }

    /**
     * Card payload for a job offer listed on the job offers index.
     */
    static function getJobOfferCardData(\Kirby\Cms\Page $page): array
    {
        return [
            'title'         => $page->title()->value(),
            'url'           => '/' . $page->virtualPath(),
            'publishedDate' => $page->publishedDate()->toDate('Y-m-d'),
            'location'      => $page->location()->value(),
            'deadline'      => $page->deadline()->toDate('Y-m-d'),
            ...self::getActivityRate($page),
            'sector'        => $page->sector()->isNotEmpty() ? $page->sector()->value() : null,
        ];
    }

    /**
     * Card payload for a mission listed on the missions index.
     */
    static function getMissionCardData(\Kirby\Cms\Page $page): array
    {
        return [
            'title'     => $page->title()->value(),
            'url'       => '/' . $page->virtualPath(),
            'date'      => $page->date()->value(),
            'location'  => $page->location()->value(),
            'shortDesc' => $page->shortDesc()->value(),
            'terms'     => self::resolveTaxonomyTerms($page->categories(), 'categories'),
        ];
    }

    /**
     * Normalized `activityRateMin/activityRateMax` percentages of a job offer.
     * Only the maximum is optional (a fixed rate leaves it empty).
     */
    static function getActivityRate(\Kirby\Cms\Page $page): array
    {
        return [
            'activityRateMin' => (int)$page->activityRateMin()->value(),
            'activityRateMax' => $page->activityRateMax()->isNotEmpty() ? (int)$page->activityRateMax()->value() : null,
        ];
    }

    /**
     * Standard metadata of a page consumed by the decoupled frontend router,
     * shared by the index templates (faq, events, projects).
     */
    static function getPageBaseData(\Kirby\Cms\Page $page, string $template): array
    {
        return [
            'template' => $template,
            'title'    => $page->title()->value(),
            'slug'     => $page->slug(),
            'path'     => $page->virtualPath(),
        ];
    }

    /**
     * The page a "back" link points to: the real Kirby parent, resolved to the
     * public frontend path. Null on a top-level page.
     */
    static function getParentPageData(\Kirby\Cms\Page $page): ?array
    {
        $parent = $page->parent();

        return $parent ? [
            'title' => $parent->title()->value(),
            'slug'  => $parent->slug(),
            'path'  => $parent->virtualPath(),
        ] : null;
    }

    /**
     * Payload of a `module-cta` block, used both inside a body blocks field and
     * as a standalone single-block field (team page).
     */
    static function getCtaModuleData(\Kirby\Cms\Block $block): array
    {
        return [
            'title'           => $block->title()->value(),
            'subtitle'        => $block->subtitle()->isNotEmpty() ? $block->subtitle()->value() : null,
            'links'           => self::resolveCtaStructures($block->links()),
            'variant'         => $block->variant()->or('default')->value(),
            'backgroundImage' => self::getJsonEncodeImageDataOrNull($block->backgroundImage()->toFile()),
        ];
    }

    /**
     * Resolves the `fields/contentBlocks` structure (repeatable `title` +
     * rich-text `description`, basic pages) to a JSON-ready list.
     */
    static function getContentBlocks(\Kirby\Content\Field $field): array
    {
        return array_values($field->toStructure()->map(fn($item) => [
            'title'       => $item->title()->value(),
            'description' => $item->description()->value(),
        ])->data());
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
            'body'          => self::getBodyBlocks($page->body()),
            'parentPage'    => self::getParentPageData($page),
            'seo'           => self::getSeoDataFromPage($page),
        ];
    }
}
