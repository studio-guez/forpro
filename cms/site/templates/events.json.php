<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'events');

$json['noResultsText']     = $page->noResultsText()->value();
$json['noUpcomingText']    = $page->noUpcomingText()->value();
$json['noPastResultsText'] = $page->noPastResultsText()->value();

$json['programs'] = Utils::getTaxonomyTerms('programs');
$json['publics']  = Utils::getTaxonomyTerms('publics');

$events   = $page->children()->listed();
$upcoming = Utils::splitEventsByDate($events)['upcoming'];

// The publics filter only narrows the agenda, so it only offers terms an upcoming event carries.
$json['usedPublics'] = Utils::getUsedTaxonomySlugs($upcoming, 'publics');

$json['upcomingEvents'] = array_values(
    $upcoming->map(fn($event) => Utils::getEventCardData($event))->data()
);

// Filters come from the query string so a shared/reloaded URL doesn't first paint unrelated events and swap them on hydration.
// The archive search is `pastQ` here (`q` is the upcoming search) but plain `q` on the past-events.json route.
$json['pastEvents'] = Utils::getPastEvents(
    $events,
    mb_substr((string)(get('pastQ') ?? ''), 0, 100),
    (string)(get('month') ?? '')
);

$json['body'] = Utils::getBodyBlocks($page->body());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
