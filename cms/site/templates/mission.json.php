<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'mission');

$json['parentPage'] = Utils::getParentPageData($page);

$json['categories'] = Utils::resolveTaxonomyTerms($page->categories(), 'categories');

// Closed missions stay reachable: the frontend shows a notice instead of the apply button.
$json['openToApplications'] = Utils::isOpenToApplications($page);

$json['announcer'] = $page->announcer()->value();
$json['date']      = $page->date()->value();
$json['location']  = $page->location()->value();
// Closed missions ship no way to apply at all, not even in the page payload.
$json['applyCta']  = $json['openToApplications']
    ? Utils::resolveCtaStructure($page->applyCta())
    : null;

$json['cover']      = Utils::getJsonEncodeImageDataOrNull($page->cover()->toFile());
$json['introTitle'] = $page->introTitle()->value();
$json['shortDesc']  = $page->shortDesc()->value();

$json['profile']  = $page->profile()->value();
$json['tasks']    = $page->tasks()->value();
$json['planning'] = $page->planning()->value();

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
