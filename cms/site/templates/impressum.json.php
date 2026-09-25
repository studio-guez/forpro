<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'impressum');

$resolveLink = fn(\Kirby\Cms\StructureObject $item) => $item->link()->isNotEmpty() ? [
    'label' => $item->linkLabel()->or($item->link())->value(),
    'url'   => $item->link()->value(),
] : null;

$json['partnersTitle'] = $page->partnersTitle()->value();

$json['partners'] = $page->partners()->toStructure()->map(fn($partner) => [
    'image' => Utils::getJsonEncodeImageDataOrNull($partner->image()->toFile()),
    'title' => $partner->title()->value(),
    'link'  => $resolveLink($partner),
])->values();

$json['sections'] = $page->sections()->toStructure()->map(fn($section) => [
    'title'   => $section->title()->value(),
    'credits' => $section->credits()->toStructure()->map(fn($credit) => [
        'role'  => $credit->role()->value(),
        'names' => $credit->names()->toStructure()->map(fn($item) => $item->name()->value())->values(),
        'link'  => $resolveLink($credit),
    ])->values(),
])->values();

$json['body'] = Utils::getBodyBlocks($page->body());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
