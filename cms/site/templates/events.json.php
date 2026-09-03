<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'events');

// All terms in their CMS-defined order, so the frontend can order filters accordingly.
$json['programs'] = Utils::getTaxonomyTerms('programs');
$json['publics']  = Utils::getTaxonomyTerms('publics');

$events = $page->children()->listed();

// Only the terms at least one event carries, so the frontend can offer filters
// that lead somewhere without holding the whole archive.
$json['usedPublics'] = Utils::getUsedTaxonomySlugs($events, 'publics');

$json['upcomingEvents'] = array_values(
    Utils::splitEventsByDate($events)['upcoming']->map(fn($event) => Utils::getEventCardData($event))->data()
);

// Past events are paginated: this is the first page. The agenda's filters are
// read off the query string so a shared or reloaded `?q=…&publics=…&month=…`
// URL renders the archive it actually asks for — otherwise the first paint
// would show ten unrelated events and swap them out on hydration. Every later
// page comes from the `past-events.json` route.
$json['pastEvents'] = Utils::getPastEvents(
    $events,
    mb_substr((string)(get('q') ?? ''), 0, 100),
    array_filter(explode(',', (string)(get('publics') ?? ''))),
    (string)(get('month') ?? '')
);

$json['body'] = Utils::getBodyBlocks($page->body());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
