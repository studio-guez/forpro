<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getEventProjectBaseData($page);

$json['template'] = 'event';

$json['dateStart'] = $page->dateStart()->isNotEmpty() ? $page->dateStart()->toDate('Y-m-d') : null;
$json['dateEnd']   = $page->dateEnd()->isNotEmpty()   ? $page->dateEnd()->toDate('Y-m-d')   : null;
$json['timeStart'] = $page->timeStart()->isNotEmpty() ? $page->timeStart()->value()          : null;
$json['timeEnd']   = $page->timeEnd()->isNotEmpty()   ? $page->timeEnd()->value()            : null;

$json['domains']     = Utils::resolveTaxonomyTerms($page->domains(), 'domains');
$json['eventThemes'] = Utils::resolveTaxonomyTerms($page->eventThemes(), 'event-themes');

echo json_encode($json);
