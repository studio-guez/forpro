<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = [];

$json['title'] = $page->title()->value();
$json['slug'] = $page->slug();

$json['body'] = $page->body()->toBlocks()->toArray();

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
