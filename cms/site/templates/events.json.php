<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'events');

// All terms in their CMS-defined order, so the frontend can order filters accordingly.
$json['programs'] = Utils::getTaxonomyTerms('programs');
$json['publics']  = Utils::getTaxonomyTerms('publics');

['upcoming' => $upcoming, 'past' => $past] = Utils::splitEventsByDate($page->children()->listed());

$json['upcomingEvents'] = array_values($upcoming->map(fn($event) => Utils::getEventCardData($event))->data());
$json['pastEvents']     = array_values($past->map(fn($event) => Utils::getEventCardData($event))->data());

$json['body'] = Utils::getBodyBlocks($page->body());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
