<?php

require_once 'utils/Utils.php';

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Cms\Site;

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = [];

$hero = $page->hero()->toStructure()?->get(0);
$showMenu = $page->showMenu()->toBool();
$showNewsletter = $page->showNewsletter()->toBool();
$body = $page->body()->toBlocks()->toArray();
$pages = $site->children();

foreach ($pages as $child) {
    $menu[] = [
        'title' => $child->title()->value(),
        'slug' => $child->slug(),
        'url' => $child->url(),
    ];
}

$json['website'] = [
    'title' => $site->title()->value(),
    'menu' => $menu
];

$json['options'] = [
    'showMenu' => $showMenu,
    'showNewsletter' => $showNewsletter,
    'hero' => $hero ? [
        'text' => $hero->text()->value(),
        'backgroundcolor' => $hero->backgroundcolor()->value(),
        'textcolor' => $hero->textcolor()->value(),
    ] : [],
];

$json['body'] = $body;

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
