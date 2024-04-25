<?php

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

foreach ($pages as $page) {
    $menu[] = [
        'title' => $page->title()->value(),
        'slug' => $page->slug(),
        'url' => $page->url(),
    ];
}

function getValueNotEmpty($pageAttribute, $siteAttribute) {
    if($pageAttribute->isNotEmpty()) {
        return $pageAttribute->value();
    } elseif($siteAttribute->isNotEmpty()) {
        return $siteAttribute->value();
    }
    return "";
}

$json['website'] = [
    'title' => $site->title()->value(),
    'menu' => $menu
];

$json['options'] = [
    'showMenu' => $showMenu,
    'gradientColor' => $hero->gradientColor()->value(),
    'showNewsletter' => $showNewsletter,
    'hero' => $hero ? [
        'text' => $hero->text()->value(),
        'backgroundcolor' => $hero->backgroundcolor()->value(),
        'textcolor' => $hero->textcolor()->value(),
    ] : [],
];

$json['body'] = $body;

$json['seo'] = [
    // General ===========================================================
    'metaTemplate'      => getValueNotEmpty($page->metaTemplate(), $site->metaTemplate()),
    'metaDescription'   => getValueNotEmpty($page->metaDescription(), $site->metaDescription()),
    'metaAuthor'        => getValueNotEmpty($page->metaAuthor(), $site->metaAuthor()),
    'metaImage'         => getValueNotEmpty($page->metaImage(), $site->metaImage()),
    'metaPhoneNumber'   => getValueNotEmpty($page->metaPhoneNumber(), $site->metaPhoneNumber()),
    // Facebook ===========================================================
    'ogTemplate'        => getValueNotEmpty($page->ogTemplate(), $site->ogTemplate()),
    'ogDescription'     => getValueNotEmpty($page->ogDescription(), $site->ogDescription()),
    'ogImage'           => getValueNotEmpty($page->ogImage(), $site->ogImage()),
    'ogSiteName'        => getValueNotEmpty($page->ogSiteName(), $site->ogSiteName()),
    // Twitter ===========================================================
    'twitterTemplate'   => getValueNotEmpty($page->twitterTemplate(), $site->twitterTemplate()),
    'twitterDescription'=> getValueNotEmpty($page->twitterDescription(), $site->twitterDescription()),
    'twitterImage'      => getValueNotEmpty($page->twitterImage(), $site->twitterImage()),
    'twitterCardType'   => getValueNotEmpty($page->twitterCardType(), $site->twitterCardType()),
    'twitterSite'       => getValueNotEmpty($page->twitterSite(), $site->twitterSite()),
    'twitterCreator'    => getValueNotEmpty($page->twitterCreator(), $site->twitterCreator()),
];

echo json_encode($json);
