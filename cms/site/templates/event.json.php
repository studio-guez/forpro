<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getEventProjectBaseData($page);

$json['template'] = 'event';

$json += Utils::getEventDateFields($page);

// Optional venue; null means the event is held at the foundation's own address.
$json['location'] = $page->location()->isNotEmpty() ? $page->location()->value() : null;

$json['programs'] = Utils::resolveTaxonomyTerms($page->programs(), 'programs');
$json['resourcesTaxonomy'] = Utils::resolveTaxonomyTerms($page->resourcesTaxonomy(), 'resourcesTaxonomy');
$json['publics']  = Utils::resolveTaxonomyTerms($page->publics(), 'publics');

echo json_encode($json);
