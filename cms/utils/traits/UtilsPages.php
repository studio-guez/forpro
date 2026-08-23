<?php

/**
 * Page-level payloads: hero, event dates, card data and the shared
 * event/project base serialization.
 */
trait UtilsPages
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
            'shortDesc'      => $page->shortDesc()->value(),
            'cover'          => self::getJsonEncodeImageDataOrNull($page->cover()->toFile()),
            'collectiveName' => $page->collectiveName()->isNotEmpty() ? $page->collectiveName()->value() : null,
            'year'           => (int)$page->year()->value(),
            'themes'         => self::resolveTaxonomyTerms($page->projectThemes(), 'project-themes'),
            'types'          => self::resolveTaxonomyTerms($page->projectTypes(), 'project-types'),
        ];
    }

    /**
     * Card payload for a job offer listed on the job offers index.
     */
    static function getJobOfferCardData(\Kirby\Cms\Page $page): array
    {
        return [
            'title'    => $page->title()->value(),
            'url'      => '/' . $page->virtualPath(),
            'location' => $page->location()->value(),
            'deadline' => $page->deadline()->toDate('Y-m-d'),
            ...self::getActivityRate($page),
            'terms'    => array_merge(
                self::resolveTaxonomyTerms($page->domains(), 'domains'),
                self::resolveTaxonomyTerms($page->jobOfferCategories(), 'job-offer-categories')
            ),
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
            'date'      => $page->date()->toDate('Y-m-d'),
            'location'  => $page->location()->value(),
            'shortDesc' => $page->shortDesc()->value(),
            'terms'     => self::resolveTaxonomyTerms($page->domains(), 'domains'),
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
}
