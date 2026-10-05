<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'basic-page');

$json['blocks'] = Utils::getContentBlocks($page->blocks());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
