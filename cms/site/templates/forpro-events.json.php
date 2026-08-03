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
$body = $page->body()->toBlocks()->map(function ($item){

    $content = $item->toArray();

    Utils::muteImageFilesDataIfBlocksHasKeyValue('capsules', $content);
    Utils::muteImageFilesDataIfBlocksHasKeyValue('cards', $content);
    Utils::muteImageFilesDataIfBlocksHasKeyValue('profiles', $content);

    return [
        'image'     => array_values( Utils::getImageArrayDataInPage($item->image()->toFiles()) ),
        'content'   => $content,
    ];
})->data();


$json['options'] = [
    'showMenu' => $showMenu,
    'showNewsletter' => $showNewsletter,
    'gradientColor' => $page->gradientColor()->value(),
    'hero' => Utils::getHeroFromPage($page),
];

$json['body'] = $body;

$json['seo'] = Utils::getSeoDataFromPage($page);






$pageChildren = $page->getQueryFilterValue($kirby) == FilterType::upcoming ?
    $page->getUpcomingEvents()
    : $page->children()->listed();


$children = $pageChildren->map(function ($item){

    $content = $item->content();

    return [
        'cover' => array_values( Utils::getImageArrayDataInPage( $content->cover()->toFiles() ) ),
        'pageContent'     => $item->toArray(),
    ];
})->data();

$json['childrenDetails'] = array_values( $children );

echo json_encode($json);





