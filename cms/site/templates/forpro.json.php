<?php

require_once 'utils/Utils.php';

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Cms\Site;

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = [];

$showMenu = $page->showMenu()->toBool();
$showNewsletter = $page->showNewsletter()->toBool();
$body = $page->body()->toBlocks()->map(function ($item) {

    $content = $item->toArray();

    Utils::muteImageFilesDataIfBlocksHasKeyValue('capsules', $content);
    Utils::muteImageFilesDataIfBlocksHasKeyValue('cards', $content);
    Utils::muteImageFilesDataIfBlocksHasKeyValue('profiles', $content);

    return [
        'image'     => array_values(Utils::getImageArrayDataInPage($item->image()->toFiles())),
        'content'   => $content,
    ];
})->data();

$json['options'] = [
    'showMenu' => $showMenu,
    'showNewsletter' => $showNewsletter,
    'gradientColor' => $page->gradientColor()->value(),
    'hero' => Utils::getHeroFromPage($page),
    'trackWithMatomo' => $page->trackWithMatomo()->isEmpty() ? true : $page->trackWithMatomo()->toBool(), // Defaults to true 

];

$json['body'] = $body;

$json['seo'] = $page->seoData();

echo json_encode($json);
