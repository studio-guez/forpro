<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'missions');

$json['overtitle'] = $page->overtitle()->value();
$json['theme']     = $page->theme()->or('default')->value();
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
$json['domains'] = Utils::getTaxonomyTerms('domains');

$json['missions'] = array_values($page->children()->listed()
    ->map(fn($mission) => Utils::getMissionCardData($mission))->data());

$json['body'] = Utils::getBodyBlocks($page->body());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
