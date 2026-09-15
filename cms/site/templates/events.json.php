<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'events');

$json['programs'] = Utils::getTaxonomyTerms('programs');
$json['publics']  = Utils::getTaxonomyTerms('publics');

$events = $page->children()->listed();

$json['usedPublics'] = Utils::getUsedTaxonomySlugs($events, 'publics');

$json['upcomingEvents'] = array_values(
    Utils::splitEventsByDate($events)['upcoming']->map(fn($event) => Utils::getEventCardData($event))->data()
);

// Filters come from the query string so a shared/reloaded URL doesn't first paint unrelated events and swap them on hydration.
// The archive search is `pastQ` here (`q` is the upcoming search) but plain `q` on the past-events.json route.
$json['pastEvents'] = Utils::getPastEvents(
    $events,
    array_filter(explode(',', (string)(get('publics') ?? ''))),
    mb_substr((string)(get('pastQ') ?? ''), 0, 100),
    (string)(get('month') ?? '')
);

$json['body'] = Utils::getBodyBlocks($page->body());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
