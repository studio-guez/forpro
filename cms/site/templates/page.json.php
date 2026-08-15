<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = [];

$json['title'] = $page->title()->value();
$json['slug'] = $page->slug();

$json['overtitle'] = $page->overtitle()->value();
$json['theme'] = $page->theme()->or('default')->value();

$json['introTitle'] = $page->introTitle()->value();
$json['intro'] = $page->intro()->value();
$json['introLayout'] = $page->introLayout()->or('1col')->value();
$ctaData = $page->introCta()->toObject();
$json['introCta'] = ($ctaData->label()->isNotEmpty() && $ctaData->url()->isNotEmpty()) ? [
    'label' => $ctaData->label()->value(),
    'url'   => $ctaData->url()->value(),
    'icon'  => $ctaData->icon()->or(null)->value(),
] : null;

$coverFile = $page->cover()->toFile();
$json['cover'] = $coverFile ? Utils::getJsonEncodeImageData($coverFile) : null;

$json['body'] = $page->body()->toBlocks()->toArray();

$json['seo'] = Utils::getSeoDataFromPage($page);

$json['trackWithMatomo'] = $page->trackWithMatomo()->toBool();

$json['path'] = $page->virtualPath();

$parentPage = $page->parentPage()->toPage();
$json['parentPage'] = $parentPage ? [
    'title' => $parentPage->title()->value(),
    'slug'  => $parentPage->slug(),
    'path'  => $parentPage->virtualPath(),
] : null;

echo json_encode($json);
