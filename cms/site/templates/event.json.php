<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getEventProjectBaseData($page);

$json['template'] = 'event';

$json += Utils::getEventDateFields($page);

$json['domains']     = Utils::resolveTaxonomyTerms($page->domains(), 'domains');
$json['eventThemes'] = Utils::resolveTaxonomyTerms($page->eventThemes(), 'event-themes');

echo json_encode($json);
