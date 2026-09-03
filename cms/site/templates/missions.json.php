<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'missions');

$json['overtitle'] = $page->overtitle()->value();
$json['theme']     = $page->theme()->or('default')->value();
$json['headerType'] = $page->headerType()->or('full')->value();
$json['cover']     = Utils::getJsonEncodeImageDataOrNull($page->cover()->toFile());

$json['introTitle'] = $page->introTitle()->value();
$json['intro']      = $page->intro()->value();

$parentPage = $page->parentPage()->toPage();
$json['parentPage'] = $parentPage ? [
    'title' => $parentPage->title()->value(),
    'slug'  => $parentPage->slug(),
    'path'  => $parentPage->virtualPath(),
] : null;

// All terms in their CMS-defined order, so the frontend can order filters accordingly.
$json['categories'] = Utils::getTaxonomyTerms('categories');

// A mission closed to applications keeps its page but leaves the index, so it
// is out before the filters and the counts are built from what is left.
$missions = Utils::filterOpenToApplications($page->children()->listed());

// Only the terms actually carried by a mission, so the frontend can offer
// filters that lead somewhere without being shipped the whole list.
$json['usedCategories'] = Utils::getUsedTaxonomySlugs($missions, 'categories');

// First page of the list. The filters are read off the query string so a shared
// or reloaded `?categories=…&sort=…` URL renders the missions it actually asks
// for — the sort especially, since it decides which ones land in the page at
// all. Later pages come from `/missions.json`.
$json['missions'] = Utils::getMissions(
    $missions,
    array_filter(explode(',', (string)(get('categories') ?? ''))),
    (string)(get('sort') ?? '')
);

$json['body'] = Utils::getBodyBlocks($page->body());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
