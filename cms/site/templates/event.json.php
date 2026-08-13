<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = [];

$json['title'] = $page->title()->value();
$json['slug'] = $page->slug();

$coverFile = $page->cover()->toFile();
$json['cover'] = $coverFile ? Utils::getJsonEncodeImageData($coverFile) : null;

$json['description'] = $page->description()->value();
$json['dateStart'] = $page->dateStart()->toDate('Y-m-d');
$json['dateEnd'] = $page->dateEnd()->toDate('Y-m-d');
$json['location'] = $page->location()->value();
$json['registration'] = $page->registration()->value();

$json['body'] = $page->body()->toBlocks()->toArray();

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
