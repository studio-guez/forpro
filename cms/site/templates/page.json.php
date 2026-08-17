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
$introTitleImageFile = $page->introTitleImage()->toFile();
$json['introTitleImage'] = $introTitleImageFile ? Utils::getJsonEncodeImageData($introTitleImageFile) : null;
$json['introCta'] = Utils::resolveCtaStructure($page->introCta());

$coverFile = $page->cover()->toFile();
$json['cover'] = $coverFile ? Utils::getJsonEncodeImageData($coverFile) : null;

$blocks = [];
foreach ($page->body()->toBlocks() as $block) {
    $content = [];
    if ($block->type() === 'module-titre-texte-image') {
        $imageFile  = $block->image()->toFile();
        $content = [
            'title'         => $block->title()->value(),
            'description'   => $block->description()->value(),
            'image'         => $imageFile ? Utils::getJsonEncodeImageData($imageFile) : null,
            'imagePosition' => $block->content()->get('image_position')->or('right')->value(),
            'variant'       => $block->variant()->or('default')->value(),
            'cta'           => Utils::resolveCtaStructure($block->cta()),
        ];
    } else {
        $content = $block->toArray()['content'] ?? [];
    }
    $blocks[] = [
        'id'       => $block->id(),
        'type'     => $block->type(),
        'isHidden' => $block->isHidden(),
        'content'  => $content,
    ];
}
$json['body'] = $blocks;

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
