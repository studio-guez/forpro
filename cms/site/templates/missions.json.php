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

$json['categories'] = Utils::getTaxonomyTerms('categories');

$missions = Utils::filterOpenToApplications($page->children()->listed());

$json['usedCategories'] = Utils::getUsedTaxonomySlugs($missions, 'categories');

// Filters (the sort especially) come from the query string so a shared/reloaded URL renders the missions it asks for.
$json['missions'] = Utils::getMissions(
    $missions,
    array_filter(explode(',', (string)(get('categories') ?? ''))),
    (string)(get('sort') ?? '')
);

$json['body'] = Utils::getBodyBlocks($page->body());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
