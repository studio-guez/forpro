<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'job-offers');

$json['cover'] = Utils::getJsonEncodeImageDataOrNull($page->cover()->toFile());

$json['shortDesc'] = $page->shortDesc()->value();

$json['body'] = Utils::getBodyBlocks($page->body());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
