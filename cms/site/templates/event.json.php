<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getEventProjectBaseData($page);

$json['template'] = 'event';

$json += Utils::getEventDateFields($page);

$json['programs'] = Utils::resolveTaxonomyTerms($page->programs(), 'programs');
$json['publics']  = Utils::resolveTaxonomyTerms($page->publics(), 'publics');

echo json_encode($json);
