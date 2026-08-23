<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'mission');

// The index the "back" link points to, i.e. the real Kirby parent (missions).
$parent = $page->parent();
$json['parentPage'] = $parent ? [
    'title' => $parent->title()->value(),
    'slug'  => $parent->slug(),
    'path'  => $parent->virtualPath(),
] : null;

$json['domains'] = Utils::resolveTaxonomyTerms($page->domains(), 'domains');

$json['announcer']     = $page->announcer()->value();
$json['publishedDate'] = $page->publishedDate()->toDate('Y-m-d');
$json['date']          = $page->date()->toDate('Y-m-d');
$json['location']      = $page->location()->value();
$json['applyCta']      = Utils::resolveCtaStructure($page->applyCta());

$json['cover']      = Utils::getJsonEncodeImageDataOrNull($page->cover()->toFile());
$json['introTitle'] = $page->introTitle()->value();
$json['shortDesc']  = $page->shortDesc()->value();

$json['profile']  = $page->profile()->value();
$json['tasks']    = $page->tasks()->value();
$json['planning'] = $page->planning()->value();

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
